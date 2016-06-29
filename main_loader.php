<?php

include "./data_workers/settings.php";

$memcache = new Memcache;
$memcache->connect($memcache_host, $memcache_port) or exit("Невозможно подключиться к серверу Memcached");

$count = intval($memcache->get("count"));

$num = $_POST['num'];
$sort_type = $_POST['sort_type'];

switch ($sort_type) {
    case "Id (ascending)":
        $sort_type = "ids_sorted_id_";
        break;
    case "Id (descending)":
        $sort_type = "ids_reversed_id_";
        break;
    case "Cost (ascending)":
        $sort_type = "ids_sorted_cost_";
        break;
    case "Cost (descending)":
        $sort_type = "ids_reversed_cost_";
        break;
    default:
        break;
}

$need_pages = $count / 100;

$page_current = $num / 100;

$ids_need_arr = $memcache->get($sort_type . $num);

$counter = 0;

echo "<div class='section'><div class='container'>";

//$inserted = 0;

foreach ($ids_need_arr as $key => $value) {
    $row = $memcache->get($value);

//    if ($row == null)
//        continue;

    if ($counter % 6 == 0) {
        echo "</div>";
        echo "<div class='row'>";
    }

    echo "<div class='col-md-2'>
                <img src='{$row['url_image']}'
                     class='img-responsive'>
                <h2>{$row['title']}</h2>
                <p>num: {$key}</p>
                <p>description: {$row['description']}</p>
                <p>cost: {$row['cost']}</p>
            </div>";

    $counter++;
}

$memcache->close();

echo "</div></div>";

echo "<div id=\"page-selection\"></div>
<script type=\"text/javascript\" src=\"http://localhost/looking_goods_system/resources/js/main_page.js\"></script>
<script>
    $('#page-selection').bootpag({
        total: $need_pages,
        page: $page_current,
        maxVisible: 28

    }).on(\"page\", function (event, num) {

        showAllFunc(num * 100, $('#sort_type').text());
    });
</script>
<p id=\"page_num\" style=\"visibility: hidden\">" . $page_current . "</p>
<p id=\"offset\" style=\"visibility: hidden\">" . $page_current . "</p>";
?>


