/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  12:48:35 PM 
 */

function nv_result_locationtop(res) {
	if (res == 'OK') {
		window.location = 'index.php?' + nv_name_variable+'='+nv_module_name+'&op=locationtop';
	} else if (res == 'EXIST') {
		alert(tieudetondai);
	} else {
		alert(tieudetondai);
	}
	return false;
}
//----------------------------------------
function nv_chang_weight_top(id) {
	var nv_timer = nv_settimeout_disable('change_weight_' + id, 5000);
	var new_weight = document.getElementById('change_weight_' + id).options[document
			.getElementById('change_weight_' + id).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&'
			+ nv_fc_variable + '=locationtop&changweight=1&id=' + id + '&new_weight='
			+ new_weight + '&num=' + nv_randomPassword(8), '', 'nv_result_locationtop');
	return;
}
//------------------------
function nv_del_top(id) {
	if (confirm(nv_is_del_confirm[0])) {
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name
				+ '&' + nv_fc_variable + '=locationtop&del=1&id=' + id, '',
				'nv_result_locationtop');
		return false;
	}
}

function search_thongke() {
    nv_settimeout_disable('search', 3000);
	var loaidiadiem = document.getElementById('loaidiadiem');
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&'
			+ nv_fc_variable + '=showlist&showlist=1&loaidiadiem=' + loaidiadiem.value
			+ '&num=' + nv_randomPassword(8), 'showlist', '');
}


function doaction() {
	var list_post = document.getElementById('list_post');
	var action = document.getElementById('action').value;
    var loaidiadiem = document.getElementById('loaidiadiem').value;
    var idhuyen = document.getElementById('idhuyen').value;
	var fa = list_post['idcheck[]'];
	var del_list = '';
	if (fa.length) {
		var k = 0;
		for ( var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				if ( k == 0 )
				{
					del_list = fa[i].value;
				}else{
					del_list = del_list + ',' + fa[i].value;
				}
				k++;
			}
		}
	}
    if (del_list == "" && fa.checked) {
       del_list = fa.value
    }
	if (del_list == "" && action == "checkbox") {
		alert(doempty);
	} else {
		if (confirm(nv_is_change_act_confirm[0])) {
			$.ajax({        
		      type: "POST",
		      url: nv_siteroot + 'diaoc/index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=thongke',
		      data: 'action=1&do=' + action + '&listid='+ del_list+'&loaidiadiem='+loaidiadiem+'&idhuyen='+idhuyen,
		      success: function(data){  
		         window.location.href = window.location.href;
		      }
		    });
		}
	}
}

// ---------------------------------------