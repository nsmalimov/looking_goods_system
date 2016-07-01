Демо версия [95.213.237.66](http://95.213.237.66/)

### Исправления:

1. Убраны лишние обращения к базе за проверкой существования записи. Оптимизирован код вставки и удаления в базу.
2. Переработаны почти полностью функции вставки, удаления и изменения, добавлен бинарный поиск, теперь операция имеет сложность O(log(n)). 
3. Для реализации поддержки 100 элементов на странице было решено увеличить количество элементов в партиции, что добавляет несколько миллисекунд на выдачу, но существенно упрощает сложность сдвига при удалении и внесении возмущений. В худшем случае, для восстановления порядка следования элементов в партиции может потребоваться 2000 операций (при 1 миллионе элементов), однако на практике существенно меньше. В любом случае, время для проведения всех действий для удаления не превышает 0.166 секунд, для вставки 0.25 секунд. 
4. Так как количество партиций изменилось, были внесены правки в основной скрипт выдачи.
5. Было убрано дубирование кода.

### Логика работы

При реализации основной логики придерживался принципа, чтобы все данные хранились в memcached, чтобы при выдаче списка товаров достичь максимальной производительности, таким образом клиент при итерации по списку товаров не подключается к mysql.

Все товары были разбиты по партициям, при 1 миллионе товаров, получаем 10000 партиций, разделяя по 100 товаров на страницу.

Данные были сгенерированы в [http://www.databasetestdata.com/](http://www.databasetestdata.com/). Затем добавлены в базу данных скриптом (insert_data_to_mysql.php).

Все записи заносятся в кеш скриптом (insert_data_to_cache.php). Теперь можем обращаться по ключу к нужному товару и получить его поля за константное время.

Для реализации операции выдачи актуальных товаров и операций изменения, удаления и вставки элементов необходимо поддерживать в кеш данные, хранящие id следующего вида: (ids_sorted_100_id, id_reversed_20000_cost ... ). Что позволит быстро узнать какие элементы необходимо показать в данный момент, то есть мы не делаем обращений к базе, а получаем актуальный список id за константное время.

Номер в ключе означает какую страницу мы запрашиваем (при 1 миллионе записей - 10000 страниц).

Такой формат хранений значений позволяет очень быстро получать номера товаров, которые нужно показать. Но при операции удаления, вставки и изменения записи, актуальность порядка id в таких записях теряется - следовательно необходимо пересчитать записи и обновить значения в кеш.

Использованный подход подразумевает, что эти данные мы можем пересчитать за O(n) (n-количество партиций). Итеративно выбираем место куда необходимо вставить, удалить или где нужно изменить порядок, опять же без обращения к базе данных. 

Но, при таком подходе размер массивов по партициям будет меняться, полагаем, что данные будут меняться равномерно и это будет несущественным, если на одной из страниц мы покажем 105 элементов, а на другой 98. К тому же в зависимости от прозводительности сервера, можно варьировать время актуализации данных в кеш, что подразумевает поддержку данных в кеше в актуальном состоянии, имеет смысл 1 раз в сутки, ночью запускать из cron актуализацию данных (insert_data_to_cache.php) - этот скрипт осуществит запросы к базе и актуализирует разбивку на партиции. 

### Технологии
Приложение является одностраничным, что подразумевает возможность итерации по списку и осуществление операций вставки, удаления и изменения данных только через навигацию внутри приложения. Клиентская логика реализована на javascript и Jquery.

Сервер: 2 ядра, 4 Гб оперативной памяти.

На сервере развернут nginx. Проведена оптимизация настроек сервера, в базе данных созданы индексы.

### Тестирование:

Включая сам index.html - 5 файлов должен выдать сервер на 1 запрос (js, css).

- Apache Bench

При варьировании разных параметров (запросы, потоки) в среднем получаем такой результат:
```markdown
Requests per second:    211.45 [#/sec] (mean)
Time per request:       47.293 [ms] (mean)
Time per request:       4.729 [ms] (mean, across all concurrent requests)
Transfer rate:          588.70 [Kbytes/sec] received
```


С учётом, что считается вся выдача сервера, то 211/5=42 - следовательно в среднем сервер выдерживает до 40 запросов на выдачу списка первых 100 товаров в секунду. С остальными партициями ситуация будет аналогичной, так как в запросе не задействуется SQL, только кеш.

- Siege
```markdown
Transaction rate:	        189.64 trans/sec
Throughput:		            0.15 MB/sec
Concurrency:		        0.25
Successful transactions:    1849
Failed transactions:	    0
```

Результаты аналогичные.

- httperf
```markdown
Connection rate: 199.2 conn/s (5.0 ms/conn, <=16 concurrent connections)
Connection time [ms]: min 36.5 avg 46.4 max 200.3 median 44.5 stddev 8.8
Connection time [ms]: connect 22.7
Connection length [replies/conn]: 1.000

Request rate: 199.2 req/s (5.0 ms/req)
Request size [B]: 66.0
```
Результаты аналогичные.

На основе тестирования можно утверждать, что проблем при 1000 запросах к списку товаров в минуту не будет.

