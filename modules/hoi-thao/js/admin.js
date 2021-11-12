/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 06:56:00 GMT
 */
 
 
 function nv_file_additem(id) {
	file_items++;
	var newitem = "<div id=\"fileupload_item_" + file_items + "\" style=\"margin-top: 5px\">&nbsp;<input readonly=\"readonly\" class=\"w300 form-control pull-left\" value=\"\" name=\"fileupload[]\" id=\"fileupload" + file_items + "\" maxlength=\"255\" />";
	newitem += "&nbsp;<input class=\"btn btn-info\" type=\"button\" value=\"" + file_selectfile + "\" name=\"selectfile\" onclick=\"nv_open_browse( '" + nv_base_adminurl + "index.php?" + nv_name_variable + "=upload&popup=1&area=fileupload" + file_items + "&path=" + file_dir + "&type=image', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no' ); return false; \" />";
	newitem += "&nbsp;<input class=\"btn btn-info\" type=\"button\" value=\"" + file_delurl + "\" onclick=\"nv_delurl( " + id + ", " + file_items + " ); \" /></div>";
	$("#fileupload_items").append(newitem);
}

function nv_delurl(id, item) {
	$("#fileupload_item_" + item).remove();
}