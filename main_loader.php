<?php

$memcache = new Memcache;
$memcache->connect('localhost', 8000) or exit("Невозможно подключиться к серверу Memcached");

$count = intval($memcache->get("count"));

$num = $_POST['num'];

$need_pages =  $count / 100;

$page_current = $num / 100;

$ids_need_arr = $memcache->get("ids_sorted_id_" . $num);

$counter = 0;

echo "<div class='section'><div class='container'>";

for ($i=0;$i<count($ids_need_arr);$i++)
{
    $row = $memcache->get($ids_need_arr[$i]);

    if ($counter % 4 == 0)
    {
        echo "</div>";
        echo "<div class='row'>";
    }

    echo "<div class='col-md-2'>
                <img src='{$row['url_image']}'
                     class='img-responsive'>
                <h2>{$row['title']}</h2>
                <p>num: {$ids_need_arr[$i]}</p>
                <p>description: {$row['description']}</p>
                <p>cost: {$row['cost']}</p>
            </div>";

    $counter ++;
}

$memcache->close();

echo "</div></div>";

echo "<div id=\"page-selection\"></div>
<script type=\"text/javascript\" src=\"http://localhost/looking_goods_system/resources/js/main_page.js\"></script>
<script>
    $('#page-selection').bootpag({
        total: $need_pages,
        page: $page_current,
        maxVisible: 15

    }).on(\"page\", function (event, num) {

        showAllFunc(num * 100);
    });
</script>";

?>


