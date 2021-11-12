<!-- BEGIN: main -->
<div class="clearfix">
    <div class="input-group">
        <input type="text" name="register_email" class="form-control required" data-mess="Vui lòng nhập email của bạn!" maxlength="160" placeholder="Nhập để nhận sản phẩm ưu đãi">
        <span class="input-group-btn"><button type="button" name="button_register_email" class="btn btn-warning" data-minlength="3" data-click="y"><em class="fa fa-thumbs-o-up fa-lg"></em></button></span>
    </div>
</div>
<script type="text/javascript">
    $('button[name=button_register_email]').click(function() {

        var register_email = $('input[name=register_email]').val();
        alert(register_email);
        if (!validateEmail(register_email) )
        {
            $('input[name=register_email]').focus();
        }
        else{
            $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={module_function}&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'registermail=1&checkss={checkss}&register_email=' + register_email, function(res) {
                var r_split = res.split('_');
                if (r_split[0] == 'OK') {
                    $('input[name=register_email]').val('');
                    window.location.href = strHref;
                } else {
                    alert(nv_is_del_confirm[2]);
                }
            });
        }
    });

    function validateEmail(sEmail) {
        var filter = '/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/';
        if (filter.test(sEmail)) {
            return true;
        }
        else {
            return false;
        }
    }​
</script>

<!-- END: main -->