var serverPath = "";
var serverHostName = window.location.hostname;
var serverProtocolName = window.location.protocol;

var portName = window.location.port;

if (portName.length == 0) {
    portName = "80";
}

if (serverHostName === "localhost") {
    serverPath = serverProtocolName + "//" + serverHostName + ":" + portName;
}
else {
    serverPath = serverProtocolName + "//" + serverHostName;
}

function serverPostFunc(serverUrl, jsonData, idWhereNeedPut) {
    $.ajax({
        type: "POST",
        url: serverUrl,
        data: jsonData,
        success: function (msg) {
            $(idWhereNeedPut).html(msg);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

function serverGetFunc(serverUrl, idWhereNeedPut) {
    $.ajax({
        url: serverUrl,
        //    cache: false,
        success: function (html) {
            $(idWhereNeedPut).html(html);
        }
    });
}

function showAllControlButton() {
    $("#createButton").show();
    $("#deleteButton").show();
    $("#changeButton").show();
    $("#showAllButton").show();
}

function createFunc() {
    showAllControlButton();
    $("#createButton").hide();
    $("#drop_down_container").hide();

    var serverUrl = serverPath + "/looking_goods_system/others/create.php";

    serverGetFunc(serverUrl, "#goodsList")
}

function deleteFunc() {
    showAllControlButton();
    $("#deleteButton").hide();
    $("#drop_down_container").hide();

    var serverUrl = serverPath + "/looking_goods_system/others/delete.php";

    serverGetFunc(serverUrl, "#goodsList");
}

function changeFunc() {
    showAllControlButton();
    $("#changeButton").hide();
    $("#drop_down_container").hide();

    var serverUrl = serverPath + "/looking_goods_system/others/change.php";
    serverGetFunc(serverUrl, "#goodsList");
}

function confirmDeleteFunc() {
    var json_create = {};

    json_create.id_num = $("#inputId").val();

    var serverUrl = serverPath + "/looking_goods_system/others/delete.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

function confirmChangeFunc() {
    var json_create = {};
    json_create.title = $("#inputTitle").val();
    json_create.description = $("#inputDescription").val();
    json_create.url_image = $("#inputImageUrl").val();
    json_create.cost = $("#inputCost").val();
    json_create.command = "change";
    json_create.id_num_original = $("#inputIdNumOriginal").val();
    json_create.id_num_need_set = $("#inputIdNumNeedSet").val();

    var serverUrl = serverPath + "/looking_goods_system/others/change.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

function confirmCreateFunc() {
    var json_create = {};
    json_create.title = $("#inputTitle").val();
    json_create.description = $("#inputDescription").val();
    json_create.url_image = $("#inputImageUrl").val();
    json_create.cost = $("#inputCost").val();

    var serverUrl = serverPath + "/looking_goods_system/others/create.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

function selectByIdNum() {
    var json_create = {};
    json_create.id_num = $("#inputIdNumOriginal").val();
    json_create.command = "select";

    var serverUrl = serverPath + "/looking_goods_system/others/change.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

function showAllFunc(need_count, type) {
    showAllControlButton();
    $("#showAllButton").hide();
    $("#drop_down_container").show();
    //$("#sorting_label").text("Sorting");
    $(".dropdown-menu li a").parents('.btn-group').find('.dropdown-toggle').html(type + ' <span class="caret"></span>');

    var json_create = {};
    json_create.num = need_count;
    json_create.sort_type = type;

    var serverUrl = serverPath + "/looking_goods_system/main_loader.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

window.onload = function () {
    //alert();
    showAllControlButton();
    showAllFunc(100, "Id (ascending)");

    $(".dropdown-menu li a").click(function () {
        var selText = $(this).text();
        $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
        $("#sort_type").text(selText);
        showAllFunc(parseInt($("#page_num").text()) * 100, $("#sort_type").text());
    });
};

function log() {
    $('#traceback').text(Array.prototype.join.call(arguments, ' '));
    console.log.apply(console, arguments);
}