/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

function copyToClipboard(obj) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('#' + obj).val()).select();
    document.execCommand("copy");
    $temp.remove();
    $('#' + obj).tooltip('show');
    setTimeout(function(){ $('#' + obj).tooltip('hide'); }, 2000);
}



$(function () {
    $('.list-group.checked-list-box .list-group-item').each(function () {

        // Settings
        var $widget = $(this),
            $checkbox = $('<input type="checkbox" name="' + $widget.data('name') + '[]" value="'+ $widget.data('class_id') +'" class="hidden" />'),
            color = ($widget.data('color') ? $widget.data('color') : "success"),
            style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
            settings = {
                on: {
                    icon: 'fa fa-check-square'
                },
                off: {
                    icon: 'fa fa-square-o'
                }
            };

        $widget.css('cursor', 'pointer')
        $widget.append($checkbox);

        // Event Handlers
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });


        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $widget.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $widget.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$widget.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $widget.addClass(style + color + ' active');
            } else {
                $widget.removeClass(style + color + ' active');
            }
        }

        // Initialization
        function init() {

            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }

            updateDisplay();

            // Inject the icon if applicable
            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span> ');
            }
        }
        init();
    });
});

$("input[name=ngaycong]").on('keyup', function (event) {
    $(this).val($(this).val().replace(/[^0.5,1,1.5\.]/gi, ''));
});
$("input[name=ngaycong]").blur(function (event) {
    var value = $(this).val();
    var old_value = $(this).attr("data_old");
    var userid = $(this).attr('data_userid');
    var datetime_key = $(this).attr('data-datetime-key');
    var id = $(this).attr('data_id');
    
    if (value != old_value){
        send_dd(userid, value, datetime_key, id);
        $(this).val(value);
    }
});
$("input[name=cophepdimuon]").click(function() {
    var checked = $(this).prop('checked');
    var value = ( checked )? 1 : 0
    var userid = $(this).attr('data_userid');
    var id = $(this).attr('data_id');
    send_cohep(userid, value, id, 0);

});
$("input[name=cophepvesom]").click(function() {
    var checked = $(this).prop('checked');
    var value = ( checked )? 1 : 0
    var userid = $(this).attr('data_userid');
    var id = $(this).attr('data_id');
    send_cohep(userid, value, id, 1);

});
$("input[name=changstatus]").click(function() {
    var checked = $(this).prop('checked');
    var value = ( checked )? 1 : 0
    var userid = $(this).attr('data_userid');
    var id = $(this).attr('data_id');
    send_status(userid, value, id);

});
function send_dd(userid, value, datetime_key, id){
    show_loading( 'Đã lưu' );
    $.ajax({
        type: "post",
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=save&ngaycong=1',
        data: 'userid=' + userid + '&value=' + value + '&datetime_key=' + datetime_key + '&id=' + id,
        success: function(data){
            data = data.split('_');
            if ( data[0] == 'ERROR') {
                show_loading(data[1]);
            }else{
                show_loading('Đã lưu');
            }
            setTimeout(function(){
                hide_loading();
            }, 2000);
        }
    });
    return false;
}
function send_cohep(userid, value, id, type){
    show_loading( 'Đang lưu...' );
    $.ajax({
        type: "post",
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=save&checkcophep=1',
        data: 'userid=' + userid + '&value=' + value + '&id=' + id + '&type=' + type,
        success: function(data){
            data = data.split('_');
            if ( data[0] == 'ERROR') {
                show_loading(data[1]);
            }else{
                show_loading('Đã lưu');
            }
            setTimeout(function(){
                hide_loading();
            }, 2000);
        }
    });
    return false;
}
function send_status(userid, value, id){
    show_loading( 'Đang lưu...' );
    $.ajax({
        type: "post",
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=save&status=1',
        data: 'userid=' + userid + '&value=' + value + '&id=' + id,
        success: function(data){
            data = data.split('_');
            if ( data[0] == 'ERROR') {
                show_loading(data[1]);
            }else{
                show_loading('Đã lưu');
            }
            setTimeout(function(){
                hide_loading();
            }, 2000);
        }
    });
    return false;
}

function save_nhanxet(){
    var userid = $('input[name=userid]').val();
    var id = $('input[name=id]').val();
    var datetime_key = $('input[name=datetime-key]').val();
    var note_name = $('textarea[name=note_name]').val();
    if( note_name == ''){
        $('textarea[name=note_name]').focus();
        return false;
    }
    else{
        show_loading( 'Đang lưu...' );
        $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=save&ghichu=1', 'id=' + id + '&userid=' + userid + '&datetime_key= ' + datetime_key + '&note_name=' + encodeURIComponent(note_name)  + '&num=' + nv_randomPassword(8), function(data) {
            data = data.split('_');
            if (data[0] == 'ERROR') {
                show_loading( data[1] );
            }else{
                show_loading( 'Cập nhật thành công!' );
                $('.modal-backdrop').hide();
                $('#exampleModal').hide();
                setTimeout(function(){
                    hide_loading()
                }, 2000);
            }
        });
    }
}

// Upload
$('#upload_fileupload').change(function() {
    $('#file_name').val($(this).val().match(/[- _\w]+[.][\w]+$/i)[0]);
});

function show_loading( html ){
    $("#bg_load").show();
    $("#set_status").html( html );
    $("#ajax_load").show();
}
function hide_loading(){
    $("#bg_load").hide();
    $("#set_status").html( '' );
    $("#ajax_load").hide();
}


/*javascript user*/
function cartorderAgency(a_ob, popup, url) {
    var id = $(a_ob).attr("id");
    $.ajax({
        type : "GET",
        url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=book-order' + '&book_order=1&id=' + id + "&nocache=" + new Date().getTime(),
        data : '',
        success : function(data) {
            var s = data.split('_');
            var strText = s[1];
            if (strText != null) {
                var intIndexOfMatch = strText.indexOf('#@#');
                while (intIndexOfMatch != -1) {
                    strText = strText.replace('#@#', '_');
                    intIndexOfMatch = strText.indexOf('#@#');
                }
                alert_msg(strText);
                linkloadcart = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
                $("#cart_" + nv_module_name).load(linkloadcart);
            }
        }
    });
}