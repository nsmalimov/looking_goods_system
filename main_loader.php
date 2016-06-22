<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'insert':
            insert();
            break;
        case 'select':
            select();
            break;
    }
}

function select() {
    echo "The select function is called.";
    exit;
}

function insert() {
    echo "The insert function is called.";
    exit;
}
?>



<div id="content">
    <div class="well well-sm">
        <h2>Heading</h2>
        <h3>22-00. 22 ноября. </h3>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus
            ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo
            sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed
            odio dui.</p>
        <a type="button" class="btn btn-primary" id="connectToChanelButton"
           onclick="connectLogin()">Присоедениться</a>
    </div>
</div>
<div id="page-selection"></div>
<script>
    // init bootpag
    $('#page-selection').bootpag({
        total: 10
    }).on("page", function(event, /* page number here */ num){
        $("#content").html("Insert content"); // some ajax content loading...
    });
</script>
