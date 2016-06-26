<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "../data_workers/settings.php";

    $memcache = new Memcache;
    $memcache->connect($memcache_host, $memcache_port) or exit("Could not connect to Memcached");

    switch ($_POST['command']) {
        case "select":
            $id = $_POST["id_num"];
            $values = $memcache->get($id);

            $description = preg_replace('/[\r\n]+/', "", $values['description']);

            echo "<script>
             $('#inputIdNumOriginal').val('{$id}');
             $('#inputIdNumNeedSet').val('{$id}');
             $('#inputTitle').val('{$values['title']}');
             $('#inputImageUrl').val('{$values['url_image']}');
             $('#inputDescription').val('{$description}');
             $('#inputCost').val('{$values['cost']}');
            </script>";

            //echo "i равно 0";
            break;

        case "change":

            include "../data_workers/chunk/chunk_create.php";
            
            $title = $_POST['title'];
            $description = $_POST['description'];
            $url_image = $_POST['url_image'];
            $cost = $_POST['cost'];
            $id_original = $_POST['id_num_original'];
            $id_need_set = $_POST['id_num_need_set'];

            $mysqli = new mysqli($mysql_dbhost, $mysql_dbuser, "", $mysql_dbname);

            if ($mysqli->connect_errno) {
                printf("Cannot connect to mysql: %s\n", $mysqli->connect_error);
                exit();
            }

            $sql = "UPDATE goods SET id = '{$id_need_set}', title = '{$title}', 
                    description = '{$description}', cost = '${cost}', url_image = '{$url_image}' 
                    WHERE id=" . $id_original;


            $result = $mysqli->query($sql);

            $new_arr = array("cost" => $cost, "description" => $description, "title" => $title,
                "url_image" => $url_image);

            $memcache->delete($id_original);

            $memcache->set($id_need_set, $new_arr);

            update_chunk($memcache, $id_need_set, $cost);

            unset($new_arr);

            if (!$result) {
                die('Could not delete data: ' . $mysqli->error);
            }

            echo "<script>alert('done');</script>";

            $mysqli->close();

            break;

        default:
            break;
    }

    $memcache->close();
}

?>

<div class="row" id="createChanelModal">
    <div class="col-md-6">

        <h3>Изменение</h3>
        <h3></h3>
        <h3></h3>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" id="inputIdNumOriginal" type="number" placeholder="Id">
            </div>
        </div>

        <a type="button" class="btn btn-primary" onclick="selectByIdNum()" style="margin-bottom: 30px">Показать</a>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" id="inputIdNumNeedSet" type="number" placeholder="Id">
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


        <a type="button" class="btn btn-primary" onclick="confirmChangeFunc()">Готово</a>
    </div>
</div>