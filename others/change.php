
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// …
}

?>

<div class="row" id="createChanelModal">
    <div class="col-md-6">

        <h3>Изменение</h3>
        <h3></h3>
        <h3></h3>

        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" id="inputId" type="number" placeholder="Id">
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


        <a type="button" class="btn btn-primary" id="createChanelButton" onclick="createChanel()">Готово</a>
    </div>
</div>