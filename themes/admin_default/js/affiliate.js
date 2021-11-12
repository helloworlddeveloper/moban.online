/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */
 

function nv_change_active( id )
{
    var new_status = $('#change_active_' + id).is(':checked') ? 1 : 0;
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable('change_active_' + id, 3000);
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&nocache=' + new Date().getTime(), 'change_active=1&id=' + id + '&new_status=' + new_status, function(res) {

        });
    }
    else
    {
        $('#change_active_' + id).prop('checked', new_status ? false : true );
    }
}


function nv_chang_users_status(vid) {
    var nv_timer = nv_settimeout_disable('change_status_' + vid, 5000);
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=users&nocache=' + new Date().getTime(), 'setactive=1&userid=' + vid, function(res) {
        if (res != 'OK') {
            alert(nv_is_change_act_confirm[2]);
            window.location.href = window.location.href;
        }
    });
    return;
}


function nv_chang_users_permission(vid) {
    var nv_timer = nv_settimeout_disable('change_permission_' + vid, 5000);
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=users&nocache=' + new Date().getTime(), 'permission=1&userid=' + vid, function(res) {
        if (res != 'OK') {
            alert(nv_is_change_act_confirm[2]);
            window.location.href = window.location.href;
        }
    });
    return;
}
function nv_menu_item_delete(id, module) {
   if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&nocache=' + new Date().getTime(), 'delete=1&id=' + id, function(res) {
            var r_split = res.split('_');
            if (r_split[0] == 'OK') {
                window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&module=' + module;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}


function nv_main_action(oForm, module, msgnocheck) {
    var fa = oForm['idcheck[]'];
    var listid = '';
    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid = listid + fa[i].value + ',';
            }
        }
    } else {
        if (fa.checked) {
            listid = listid + fa.value + ',';
        }
    }

    if (listid != '') {
        var action = document.getElementById('action').value;
        if (action == 'delete') {
            if (confirm(nv_is_del_confirm[0])) {
                $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&nocache=' + new Date().getTime(), 'delete=1&listid=' + listid, function(res) {
                    var r_split = res.split('_');
                    if (r_split[0] == 'OK') {
                        window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&module=' + module;
                    } else {
                        alert(nv_is_del_confirm[2]);
                    }
                });
            }
            return false;
        }
    } else {
        alert(msgnocheck);
    }
    return false;
}

function nv_menu_reload( module, lang_confirm ){
    if (confirm( lang_confirm ) ) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&nocache=' + new Date().getTime(), 'reload=1&module=' + module, function(res) {
            var r_split = res.split('_');
            alert( r_split[1] );
            if (r_split[0] == 'OK') {
                window.location.href = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&module=' + module;
            }
        });
    }
}
function nv_waiting_row_del(uid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=usersp&nocache=' + new Date().getTime(), 'del=1&userid=' + uid, function(res) {
            if (res == 'OK') {
                window.location.href = window.location.href;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}




/*agency
* */

function nv_chang_weight(vid, opaction) {
    var nv_timer = nv_settimeout_disable('change_weight_' + vid, 5000);
    var new_weight = $('#change_weight_' + vid).val();
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + opaction + '&nocache=' + new Date().getTime(), 'change_weight=1&id=' + vid + '&new_weight=' + new_weight, function(res) {
        nv_chang_weight_res(res);
    });
    return;
}

function nv_chang_status(vid, opaction) {
    var nv_timer = nv_settimeout_disable('change_status_' + vid, 5000);
    var new_status = $('#change_status_' + vid).val();
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + opaction + '&nocache=' + new Date().getTime(), 'change_status=1&id=' + vid + '&new_status=' + new_status, function(res) {
        nv_chang_weight_res(res);
    });
    return;
}

function nv_chang_weight_res(res) {
    var r_split = res.split("_");
    if (r_split[0] != 'OK') {
        alert(nv_is_change_act_confirm[2]);
        clearTimeout(nv_timer);
    } else {
        window.location.href = window.location.href;
    }
    return;
}

function nv_module_del(did, opaction, checkss) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + opaction + '&nocache=' + new Date().getTime(), 'del=1&id=' + did + '&checkss=' + checkss, function(res) {
            var r_split = res.split("_");
            if (r_split[0] == 'OK') {
                window.location.href = window.location.href;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}



function FormatNumber(str) {

    var strTemp = GetNumber(str);
    if (strTemp.length <= 3)
        return strTemp;
    strResult = "";
    for (var i = 0; i < strTemp.length; i++)
        strTemp = strTemp.replace(",", "");
    var m = strTemp.lastIndexOf(".");
    if (m == -1) {
        for (var i = strTemp.length; i >= 0; i--) {
            if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
                strResult = "," + strResult;
            strResult = strTemp.substring(i, i + 1) + strResult;
        }
    } else {
        var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
        var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."), strTemp.length);
        var tam = 0;
        for (var i = strphannguyen.length; i >= 0; i--) {

            if (strResult.length > 0 && tam == 4) {
                strResult = "," + strResult;
                tam = 1;
            }

            strResult = strphannguyen.substring(i, i + 1) + strResult;
            tam = tam + 1;
        }
        strResult = strResult + strphanthapphan;
    }
    return strResult;
}

function GetNumber(str) {
    var count = 0;
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == " ")
            return str.substring(0, i);
        if (temp == ".") {
            if (count > 0)
                return str.substring(0, ipubl_date);
            count++;
        }
    }
    return str;
}

function IsNumberInt(str) {
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == ",") {
            return str.substring(0, i);
        }
    }
    return str;
}
