<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// …
}

?>

<div class="row" id="createChanelModal">
    <div class="col-md-6">
        <div class="form-group row">
            <div class="col-sm-12">
                <input class="form-control" id="inputChanelTitle" type="text" placeholder="Name">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <textarea class="form-control" rows="3" placeholder="Description" id="inputChanelDescription"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" placeholder="Time and date" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                </div>
            </div>
        </div>
        <a type="button" class="btn btn-primary" id="createChanelButton" onclick="createChanel()">Готово</a>
    </div>
</div>

