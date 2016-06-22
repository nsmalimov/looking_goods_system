<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_num = $_POST['id_num'];

    $dbhost = 'localhost:3306';
    $dbuser = 'root';
    $dbpass = 've;br';
    $conn = mysql_connect($dbhost, $dbuser);

    if (!$conn) {
        die('Could not connect: ' . mysql_error());
    }

    $sql = 'DELETE FROM goods WHERE id_num = ' . $id_num;

    mysql_select_db('goods_data');
    $retval = mysql_query($sql, $conn);

    if (!$retval) {
        die('Could not delete data: ' . mysql_error());
    }

    echo "<script>alert('done');</script>";

    mysql_close($conn);

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
</div>"