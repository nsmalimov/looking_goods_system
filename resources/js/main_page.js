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

var append_path = "/looking_goods_system";

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

    var serverUrl = serverPath + append_path + "/others/create.php";

    serverGetFunc(serverUrl, "#goodsList")
}

function deleteFunc() {
    showAllControlButton();
    $("#deleteButton").hide();
    $("#drop_down_container").hide();

    var serverUrl = serverPath + append_path + "/others/delete.php";

    serverGetFunc(serverUrl, "#goodsList");
}

function changeFunc() {
    showAllControlButton();
    $("#changeButton").hide();
    $("#drop_down_container").hide();

    var serverUrl = serverPath + append_path + "/others/change.php";
    serverGetFunc(serverUrl, "#goodsList");
}

function confirmDeleteFunc() {
    var json_create = {};

    json_create.id_num = $("#inputId").val();

    if (!(Number.isInteger(parseInt(json_create.id_num)))) {
        alert("not digit");
        return;
    }

    var serverUrl = serverPath + append_path + "/others/delete.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

function confirmChangeFunc() {
    var json_create = {};

    json_create.title = $("#inputTitle").val();
    json_create.description = $("#inputDescription").val();
    json_create.url_image = $("#inputImageUrl").val();
    json_create.cost = parseFloat($("#inputCost").val());
    json_create.command = "change";
    json_create.id_num_original = $("#inputIdNumOriginal").val();
    json_create.id_num_need_set = $("#inputIdNumNeedSet").val();

    if (isNaN(parseInt(json_create.id_num_original)) ||
        isNaN(parseInt(json_create.id_num_need_set)) ||
        isNaN(json_create.cost)) {
        alert("not digit");
        return;
    }

    if (json_create.id_num_original.length == 0) {
        alert("id is empty");
        return;
    }

    if (json_create.cost < 0) {
        alert("bad cost");
        return;
    }

    var serverUrl = serverPath + append_path + "/others/change.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

function confirmCreateFunc() {
    var json_create = {};
    json_create.title = $("#inputTitle").val();
    json_create.description = $("#inputDescription").val();
    json_create.url_image = $("#inputImageUrl").val();
    json_create.cost = parseFloat($("#inputCost").val());
    json_create.id_num = $("#inputIdNum").val();

    if (isNaN(parseInt(json_create.id_num)) ||
        isNaN(json_create.cost)) {
        alert("not digit");
        return;
    }

    if (json_create.cost < 0) {
        alert("bad cost");
        return;
    }

    var serverUrl = serverPath + append_path + "/others/create.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

function selectByIdNum() {
    var json_create = {};
    json_create.id_num = $("#inputIdNumOriginal").val();
    json_create.command = "select";

    if (!(Number.isInteger(parseInt(json_create.id_num)))) {
        alert("not digit");
        return;
    }

    var serverUrl = serverPath + append_path + "/others/change.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

function showAllFunc(need_count, type) {
    showAllControlButton();
    $("#showAllButton").hide();
    $("#drop_down_container").show();
    $(".dropdown-menu li a").parents('.btn-group').find('.dropdown-toggle').html(type + ' <span class="caret"></span>');

    var json_create = {};
    json_create.num = need_count;
    json_create.sort_type = type;

    var serverUrl = serverPath + append_path + "/main_loader.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

window.onload = function () {
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