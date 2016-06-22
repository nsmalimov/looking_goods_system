
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $url_image = $_POST['url_image'];
    $cost = $_POST['cost'];
    $id_num = $_POST['id_num'];

    $dbhost = 'localhost:3306';
    $dbuser = 'root';
    $dbpass = 've;br';
    $conn = mysql_connect($dbhost, $dbuser);

    if (!$conn) {
        die('Could not connect: ' . mysql_error());
    }

    $sql = "INSERT INTO goods ".
        "(id_num,cost,title,url_image,description)".
        "VALUES ".
        "('$id_num','$cost','$title','$url_image','$description')";

    mysql_select_db('goods_data');
    $retval = mysql_query($sql, $conn);

    if (!$retval) {
        die('Could not insert data: ' . mysql_error());
    }

    echo "<script>alert('done');</script>";

    mysql_close($conn);
}

?>

<div class="row" id="createChanelModal">
    <div class="col-md-6">

        <h3>Создание</h3>
        <h3></h3>
        <h3></h3>
        
        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" id="inputIdNum" type="number" placeholder="Id">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" id="inputTitle" type="text" placeholder="Title">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <textarea class="form-control" rows="3" placeholder="Description" id="inputDescription"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" type="text" placeholder="Image URL" id="inputImageUrl">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" type="number" placeholder="Cost" id="inputCost">
            </div>
        </div>


        <a type="button" class="btn btn-primary" id="createChanelButton" onclick="confirmCreateFunc()">Готово</a>
    </div>
</div>