<?php
echo $_SERVER['REQUEST_METHOD'];

$num = $_POST['num'];

$num_under = $num - 100;

if ($num_under == 0)
{
    $num_under = 1;
}

$dbhost = 'localhost:3306';
$dbuser = 'root';
$dbpass = 've;br';
$conn = mysql_connect($dbhost, $dbuser);
if(! $conn )
{
    die('Could not connect: ' . mysql_error());
}

$sql = 'SELECT id, id_num, cost, title, url_image, description FROM goods WHERE id >= ' . $num_under . ' AND id <=' . $num;

mysql_select_db('goods_data');
$retval = mysql_query( $sql, $conn );



if(! $retval )
{
    die('Could not get data: ' . mysql_error());
}



//function select() {
//    echo "The select function is called.";
//    exit;
//}
//
//function insert() {
//    echo "The insert function is called.";
//    exit;
//}

echo "<div class='section'><div class='container'>";

$counter = 0;

while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
    if ($counter % 4 == 0)
    {
        echo "</div>";
        echo "<div class='row'>";
    }

    echo "<div class='col-md-2'>
                <img src='{$row['url_image']}'
                     class='img-responsive'>
                <h2>{$row['title']}</h2>
                <p>num: {$row['id_num']}</p>
                <p>description: {$row['description']}</p>
                <p>cost: {$row['cost']}</p>
            </div>";

    $counter ++;
}

echo "</div></div>";

$result = mysql_query("SELECT count(*) from goods;");
$need_pages =  mysql_result($result, 0) / 100;

$page_current = $num / 100;

echo "<div id=\"page-selection\"></div>
<script type=\"text/javascript\" src=\"http://localhost/looking_goods_system/resources/js/main_page.js\"></script>
<script>
    // init bootpag
    $('#page-selection').bootpag({
        total: $need_pages,
        page: $page_current,
        maxVisible: 15
        
    }).on(\"page\", function (event, num) {
    
        showAllFunc(num * 100);
    });
</script>";

mysql_close($conn);

?>


