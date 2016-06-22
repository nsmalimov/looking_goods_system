<?php
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

mysql_close($conn);

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
        echo "<div class='row'>";
    }

    echo "<div class='col-md-2' style='margin-top: 50px'>
                <img src='{$row['url_image']}'
                     class='img-responsive'>
                <h2>{$row['title']}</h2>
                <p>num: {$row['id_num']}</p>
                <p>description: {$row['description']}</p>
                <p>cost: {$row['cost']}</p>
            </div></div>";

    if ($counter % 4 == 0)
    {
        echo "</div>";
    }

    $counter ++;
}

echo "</div></div>";

exit;

?>

<div id="page-selection"></div>
<script>
    // init bootpag
    $('#page-selection').bootpag({
        total: 10
    }).on("page", function (event, /* page number here */ num) {
        $("#content").html("Insert content"); // some ajax content loading...
    });
</script>
