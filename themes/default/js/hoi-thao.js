/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 06 Aug 2014 60:43:04 GMT
 */

// Upload
$('#upload_fileupload').change(function() {
    $('#file_name').val($(this).val().match(/[- _\w]+[.][\w]+$/i)[0]);
});

$('#upload_site_image').change(function() {
    $('#site_image').val($(this).val().match(/[- _\w]+[.][\w]+$/i)[0]);
});

$('.regsite_have_code').click(function () {
    $('#input_mobile').hide();
    $('#input_code').show();
})
$('.regsite_get_code').click(function () {
    $('#input_mobile').show();
    $('#input_code').hide();
})
$('input[name=check_phone]').click(function () {
    var intRegexPhone = /^[0-9]+$/
    var fphone = trim( $('input[name=fphone]').val());
    if((fphone.length < 10 || fphone.length > 11) || (!intRegexPhone.test(fphone)))
    {
        $('#error').html('Vui lòng nhập số điện thoại liên hệ là số có từ 10-11 chữ số');
        $('input[name=fphone]').focus();
    }else{
        $('#error').html('');
        $.ajax({
            type : "POST",
            dataType: "json",
            url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
            data : 'checkphone&phone=' + fphone + '&fcode=' + $('input[name=fcode]').val() + '&g-recaptcha-response=' + $('textarea[name=g-recaptcha-response]').val(),
            success : function(data) {
                if(data.status == 'error'){
                    $('#error').html(data.mess);
                    change_captcha();
                }else{
                    $('#ok').html(data.mess);
                    $('#input_mobile').hide();
                    $('#input_code').show();
                }
            }
        });
    }
})

$('input[name=check_code]').click(function () {

    var code = trim( $('input[name=code]').val());
    if(code.length != 6)
    {
        $('#error').html('Mã kích hoạt chưa chính xác. Vui lòng kiểm tra lại');
        $('input[name=code]').focus();
    }else{
        $('#error').html('');
        $.ajax({
            type : "POST",
            dataType: "json",
            url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
            data : 'check_code&code=' + code,
            success : function(data) {
                if(data.status == 'error'){
                    $('#error').html(data.mess);
                    change_captcha();
                }else{
                    $('#ok').html(data.mess);
                    setTimeout(function(){ location.reload(); }, 2000);
                }
            }
        });
    }
})

$('input[name=domain_name]').blur(function () {

    var domain_name = trim( $('input[name=domain_name]').val());
    var preg = '^[a-z0-9]+$';
    if (!domain_name.match( preg)) {
        $('#result_domain').removeClass('ok');
        $('#result_domain').addClass('error');
        $('#result_domain').html('Tên miền chỉ chấp nhận các ký tự từ a-z và bảng chữ số');
    }else{
        $('#result_domain').html('');
        $.ajax({
            type : "POST",
            dataType: "json",
            url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
            data : 'check_domain&domain_name=' + domain_name,
            success : function(data) {
                if(data.status == 'error'){
                    $('#result_domain').removeClass('ok');
                    $('#result_domain').addClass('error');
                    $('#result_domain').html(data.mess);
                }else{
                    $('#result_domain').addClass('ok');
                    $('#result_domain').html(data.mess);
                }
            }
        });
    }
})
