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

//
// function logOutIn()
// {
//     if ($("#loginText").text() == "Войти") {
//         $("#loginPanel").modal('show');
//         $("#registrationPanel").modal('hide');
//         $("#reestablishPanel").modal('hide');
//     }
//     else
//     {
//         //delete cookies
//         //reload page
//         location.reload();
//     }
// }
//
// function reestablishShowPanel()
// {
//     $("#loginPanel").modal('hide');
//     $("#registrationPanel").modal('hide');
//     $("#reestablishPanel").modal('show');
// }
//
// function registrationShowPanel()
// {
//     $("#loginPanel").modal('hide');
//     $("#registrationPanel").modal('show');
//     $("#reestablishPanel").modal('hide');
// }
//

function serverPostFunc(serverUrl, jsonData, idWhereNeedPut) {
    //var clickBtnValue = $(this).val();
    //var ajaxurl = serverUrl,
    //    data =  {'action': "111"};
    //alert(serverUrl);
    $.post(serverUrl, jsonData, function (response) {
        // Response div goes here.
        $(idWhereNeedPut).html(response);
        //alert(response);
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

//
// function serverChanelWorkerFunc(serverUrl, jsonData) {
//     $.ajax({
//         url: serverUrl + "/discussions",
//         type: 'POST',
//         data: jsonData,
//
//         dataType: 'json',
//         async: true,
//
//         success: function (event) {
//             switch (event["answer"])
//             {
//                 case "ok create":
//                     updateChanelsList();
//                     break;
//             }
//         },
//         error: function (xhr, status, error) {
//             log(error);
//         }
//     });
// }
//
// function connectLogin()
// {
//     var json_create;
//     json_create = {};
//     json_create.email = $("#inputEmailLogin").val();
//     json_create.password = $("#inputPasswordLogin").val();
//     json_create.command = "login";
//     var json = JSON.stringify(json_create);
//
//     if (json_create.email.length == 0 || json_create.password.length == 0)
//     {
//         alert("Неправильные данные");
//         return;
//     }
//
//     serverConnectFunc(serverPath, json);
// }
//
// function connectReestablish()
// {
//     var json_create;
//     json_create = {};
//     json_create.email = $("#inputEmailReestablish").val();
//     json_create.command = "reestablish";
//
//     if (json_create.email.length == 0)
//     {
//         alert("Неправильные данные");
//         return;
//     }
//
//     var json = JSON.stringify(json_create);
//     serverConnectFunc(serverPath, json);
// }
//
// function connectRegistration()
// {
//     var json_create;
//     json_create = {};
//     json_create.email = $("#inputEmailRegistration").val();
//     json_create.username = $("#inputUsernameRegistration").val();
//
//     var password1 = $("#inputPasswordRegistration1").val();
//     var password2 = $("#inputPasswordRegistration2").val();
//
//     if (password1 != password2) {
//         alert("Пароли не совпадают");
//         return;
//     }
//
//     if (json_create.email.length == 0 || password1.length == 0 || password2.length == 0)
//     {
//         alert("Неправильные данные");
//         return;
//     }
//
//     json_create.password = password1;
//     json_create.command = "registration";
//     var json = JSON.stringify(json_create);
//
//     serverConnectFunc(serverPath, json);
// }
//
// function createChanelFieldOpen()
// {
//     if ($("#userName").text() == "")
//     {
//         $("#loginPanel").modal('show');
//         return;
//     }
//
//     $("#chanelsList").hide();
//     $("#createChanelModal").show();
// }
//
// function createChanel()
// {
//     var json_create;
//     json_create = {};
//     json_create.title = $("#inputChanelTitle").val();
//     json_create.description = $("#inputChanelDescription").val();
//     json_create.datetime = $("#datetimepicker1").data("DateTimePicker").date();
//     json_create.command = "create";
//     json_create.userName = $("#userName");
//
//     var json = JSON.stringify(json_create);
//     serverChanelWorkerFunc(serverPath, json);
// }
//

function showAllControlButton() {
    $("#createButton").show();
    $("#deleteButton").show();
    $("#changeButton").show();
    $("#showAllButton").show();
}

function createFunc() {
    showAllControlButton();
    $("#createButton").hide();

    var serverUrl = serverPath + "/looking_goods_system/others/create.php";

    serverGetFunc(serverUrl, "#goodsList")
}

function deleteFunc() {
    showAllControlButton();
    $("#deleteButton").hide();

    var serverUrl = serverPath + "/looking_goods_system/others/delete.php";

    serverGetFunc(serverUrl, "#goodsList");
}

function changeFunc() {
    showAllControlButton();
    $("#changeButton").hide();

    var serverUrl = serverPath + "/looking_goods_system/others/change.php";
    serverGetFunc(serverUrl, "#goodsList");
}

function showAllFunc() {
    showAllControlButton();
    $("#showAllButton").hide();

    var json_create = {};
    json_create.num = 100;
    //var json = JSON.stringify(json_create);

    var serverUrl = serverPath + "/looking_goods_system/main_loader.php";
    serverPostFunc(serverUrl, json_create, "#goodsList");
}

window.onload = function () {
    showAllControlButton();
    showAllFunc();
};


function log() {
    $('#traceback').text(Array.prototype.join.call(arguments, ' '));
    console.log.apply(console, arguments);
}

// function updateChanelsList()
// {
//     $("#chanelsList").show();
//     $("#createChanelModal").hide();
// }