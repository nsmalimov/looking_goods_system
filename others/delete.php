<?php
define('MYSQL_BOTH', MYSQLI_BOTH);
define('MYSQL_NUM', MYSQLI_NUM);
define('MYSQL_ASSOC', MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id_num'];

    include "../data_workers/settings.php";

    include "../data_workers/chunk/chunk_delete.php";

    $mysqli = new mysqli($mysql_dbhost, $mysql_dbuser, $mysql_dbpass, $mysql_dbname);

    $memcache = new Memcache;
    $memcache->connect($memcache_host, $memcache_port) or exit("Could not connect to Memcached");

    if ($mysqli->connect_errno) {
        printf("Cannot connect to mysql: %s\n", $mysqli->connect_error);
        exit();
    }

    $sql = 'select id FROM goods WHERE id = ' . $id;
    $result = $mysqli->query($sql);

    if ($result->fetch_array(MYSQL_NUM)[0] == null) {
        echo "<script>alert('id not exist');</script>";
    } else {
        $sql = 'DELETE FROM goods WHERE id = ' . $id;

        $result = $mysqli->query($sql);

        if (!$result) {
            die('Could not delete data: ' . $mysqli->error);
        }

        update_chunk_delete($memcache, $id);

        $memcache->delete($id);

        //echo "<script>alert('done');</script>";
    }

    $memcache->close();

    $mysqli->close();
}
?>

<div class='row' id='createChanelModal'>
    <div class='col-md-6'>

        <h3> Удаление</h3>
        <h3></h3>
        <h3></h3>

        <div class='form-group row'>
            <div class='col-sm-12'>
                <input class='form-control' id='inputId' type='number' placeholder='Id'>
            </div>
        </div>
        <a type='button' class='btn btn-primary' id='createChanelButton' onclick='confirmDeleteFunc()'> Готово</a>
    </div>
</div>