

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
