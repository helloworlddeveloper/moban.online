<!-- BEGIN: main -->

<div class="clearfix">
    <div class="input-group footer-reg-email">
        <input type="text" name="register_email" class="form-control input-register-mail required" maxlength="160" placeholder="Nhập để nhận sản phẩm ưu đãi" />
        <span class="input-group-btn"><button type="button" name="button_register_email" class="btn btn-warning" data-minlength="3" data-click="y"><em class="fa fa-thumbs-o-up fa-lg"></em></button></span>
    </div>
</div>
<script type="text/javascript">
    $('button[name=button_register_email]').click(function() {
        var register_email = $('input[name=register_email]').val();
        if ( register_email == '' )
        {
            $('input[name=register_email]').focus();
        }
        else{
            $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={module_function}&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'registermail=1&checkss={checkss}&register_email=' + register_email, function(res) {
                var r_split = res.split('_');
                if (r_split[0] == 'OK') {
                    alert('Cảm ơn bạn đã đăng ký, Chúng tôi sẽ gửi mail khi có chương trình ưu đãi mới cho bạn!')
                    $('input[name=register_email]').val('');
                } else {
                    alert(r_split[1]);
                }
            });
        }
    });
</script>

<!-- END: main -->