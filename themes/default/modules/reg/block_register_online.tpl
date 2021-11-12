<!-- BEGIN: main -->
<div id="toidk"></div>
<br>
<div  class="ss1-hotro myform">
    <div class="myform2">
        <h3>ĐĂNG KÝ LÀM ĐẠI LÝ ĐỂ KIẾM THU NHẬP TRÊN 10 TRIỆU/THÁNG </h3>
        <p>Vui lòng điền thông tin đăng ký của bạn vào bên dưới, chúng tôi sẽ liên hệ tư vấn cho bạn</p>
        <form id="fmrmodal" action="" method="post">
            <div class="row">
                <div class="col-md-3 ">
                    <div class="form-group">
                        <label for="hoten">Họ tên</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Họ tên">
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group">
                        <label for="dienthoai">Điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Điện thoại">
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group">
                        <label for="dienthoai">Email (nếu có)</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email của bạn">
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="form-group">
                        <label for="hoten">Địa chỉ( nơi bạn đang sinh sống )</label>
                        <input type="text" class="form-control" name="address" id="ghichu" placeholder="Địa chỉ">
                    </div>
                </div>
                <div class="col-md-12 text-center clear">
                    <input type="hidden" name="vitri" value="top">
                    <span id="status_loading" style="display:none;><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" /><span style="color:#fff">&nbsp;Đang gửi dữ liệu...</span></span>
                    <input class="btn btn-danger btn-lg btn-gonow" name="reg_submit" type="button" value="Gửi đăng ký tư vấn" /><br />
                </div>
            </div>
        </form>
    </div>
</div>

<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.session.js"></script>

<script type="text/javascript">
    $('input[name=reg_submit]').click(function() {

        var fullname = trim( $('input[name=fullname]').val());
        var email = trim( $('input[name=email]').val());
        var address = trim( $('input[name=address]').val());
        var phone = trim( $('input[name=phone]').val());

        var intRegexPhone = /^[0-9]+$/
        
        if( fullname == '' ){
            alert('Vui lòng nhập họ tên của bạn');
            $('input[name=fullname]').focus();
        }else if((phone.length < 10 || phone.length > 11) || (!intRegexPhone.test(phone))){
            alert('Vui lòng nhập số điện thoại liên hệ của là số có từ 10-11 chữ số');
            $('input[name=phone]').focus();
        }else if(  address == '' ){
            alert('Vui lòng nhập địa chỉ liên hệ');
            $('input[name=address]').focus();
        }else{
            $('input[name=reg_submit]').hide();
            $('#status_loading').show();
            $.ajax({
    			type : "POST",
    			url : nv_base_siteurl + "index.php?" + nv_name_variable + "={MODULE_NAME}&" + nv_fc_variable + "=reg&nocache=" + new Date().getTime(),
    			data : "reg=1&fullname=" + fullname + '&email=' + email + '&address=' + address + '&phone=' + phone,
    			success : function(response) {
    				if (response == "OK") {
                        $('input[type=text]').val('');
    					alert('Cảm ơn bạn, chúng tôi đã nhận được thông tin và sẽ liên hệ lại với bạn trong vòng 24h.');
    				} else{
    					alert(response);
    				}
                    $('input[name=reg_submit]').show();
                    $('#status_loading').hide();
    			},
    			error : function(x, e) {
    				if (x.status == 0) {
    					alert('You are offline!!\n Please Check Your Network.');
    				} else if (x.status == 404) {
    					alert('Requested URL not found.');
    				} else if (x.status == 500) {
    					alert('{LANG.read_error_memory_limit}');
    				} else if (e == 'timeout') {
    					alert('Request Time out.');
    				} else {
    					alert('Unknow Error.\n' + x.responseText);
    				}
                    $('input[name=reg_submit]').show();
                    $('#status_loading').hide();
    			}
    		});
        }
    });
</script>
<!-- END: main -->