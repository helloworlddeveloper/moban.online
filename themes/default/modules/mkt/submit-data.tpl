<!-- BEGIN: main -->
<!-- BEGIN: nodata -->
<div class="panel panel-default">
    <div class="panel-body text-center"><strong>Hiện tại chưa có sự kiện nào diễn ra. Hãy quay lại sau!</strong></div>
</div>
<!-- END: nodata -->
<!-- BEGIN: data -->
<style>
    .funkyradio label{
        font-size: 16px;
    }
    .error-input{
        border: 1px solid #d00078;
    }
</style>
<div  class="ss1-hotro myform">
    <div class="myform2">
        <h3 class="text-center" style="font-size:16px">FORM NHẬP THÔNG TIN KHÁCH MỜI THAM GIA HỘI THẢO CỦA NPP</h3>
        <p class="text-center">Hãy điền thông tin đăng ký của khách mời vào bên dưới.</p>
        <form id="fmrmodal" action="" method="post">
            <div class="row">
                <div class="col-md-24">
                    <div class="form-group">
                        <label for="dienthoai">Điện thoại</label>
                        <input type="text" class="form-control" name="phone" onblur="check_phone(this.value);" id="phone" placeholder="Điện thoại">
                    </div>
                </div>
                <!-- BEGIN: user_refer -->
                <div class="col-md-24">
                    <div class="form-group">
                        <label for="hoten">Người giới thiệu</label>
                        <div class="uiTokenizer uiInlineTokenizer">
	                            <span id="userid" class="tokenarea"></span>
                                <span class="uiTypeahead">
                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                    <div class="innerWrap" style="float:left;">
                                        <input id="user_search" type="text" placeholder="Nhập tên người giới thiệu" class="form-control textInput" style="width: 100%;" />
                                    </div>
	                            </span>
                        </div>
                    </div>
                </div>
                <!-- END: user_refer -->
                <div class="col-md-24">
                    <div class="form-group">
                        <label for="hoten">Họ tên</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Họ tên">
                    </div>
                </div>
                <div class="col-md-24">
                    <div class="form-group">
                        <label for="dienthoai">Email (nếu có)</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email của bạn">
                    </div>
                </div>
                <div class="col-md-24">
                    <div class="form-group">
                        <label for="hoten">Địa chỉ( nơi bạn đang sinh sống )</label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="Địa chỉ">
                    </div>
                </div>
                <div class="col-md-24">
                    <div class="form-group">
                        <label for="hoten">Tỉnh/thành phố</label>
                        <select name="provinceid" class="form-control">
                            <option value="0">--Chọn tỉnh/thành phố--</option>
                            <!-- BEGIN: province -->
                            <option value="{PROVINCE.id}">{PROVINCE.title}</option>
                            <!-- END: province -->
                        </select>
                    </div>
                </div>
                <div class="col-md-24">
                    <div class="form-group funkyradio">
                        <label for="hoten">Chọn sự kiện tham gia</label>
                        <!-- BEGIN: events -->
                        <div class="funkyradio-primary">
                            <input type="radio" name="event"{EVENT.ck} value="{EVENT.id}" id="radio{EVENT.id}"/>
                            <label for="radio{EVENT.id}">{EVENT.title} - {EVENT.timeevent} </label>
                            <p style="padding-left: 20px;font-size: 13px;">Địa điểm: {EVENT.addressevent}</p>
                        </div>
                        <!-- END: events -->
                    </div>
                </div>
                <div class="col-md-24 text-center clear">
                    <input type="hidden" name="vitri" value="top">
                    <span id="status_loading" style="display:none;><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" /><span style="color:#fff">&nbsp;Đang gửi dữ liệu...</span></span>
                    <input class="btn btn-danger btn-lg btn-gonow" name="reg_submit" type="button" value="Gửi phiếu" /><br />
                </div>
            </div>
        </form>
    </div>
</div>
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">
    var intRegexPhone = /^[0-9]+$/;
    function check_phone(mobile){
        if(( mobile.length < 10 || mobile.length > 11) || (!intRegexPhone.test(mobile)) ){
            $('#phone').addClass('error-input');
        }else{
            $('#phone').removeClass('error-input');
            $.ajax({
                type : "POST",
                url : nv_base_siteurl + "index.php?" + nv_name_variable + "={MODULE_NAME}&" + nv_fc_variable + "=submit-data&nocache=" + new Date().getTime(),
                data : 'check=1&mobile=' + mobile,
                dataType: "json",
                success : function(data) {
                    $('#fullname').val(data.full_name);
                    $('#email').val(data.email);
                    $('#address').val(data.address);
                    $('#address').val(data.address);
                    $("select[name=provinceid]").val(data.provinceid).change();
                }
            });
        }
    }
    $('input[name=reg_submit]').click(function() {
        var fullname = trim( $('input[name=fullname]').val());
        var email = trim( $('input[name=email]').val());
        var address = trim( $('input[name=address]').val());
        var phone = trim( $('input[name=phone]').val());
        var event = trim( $('input[name=event]:checked').val());
        var provinceid = trim( $('select[name=provinceid]').val());
        var userid = trim( $('input[name=userid]').val());
        if( userid == 'undefined'){
            userid = 0;
        }
        if( fullname == '' ){
            $('input[name=fullname]').focus();
            alert('Vui lòng nhập họ tên của khách mời');
        }else if((phone.length < 10 || phone.length > 11) || (!intRegexPhone.test(phone))){
            alert('Vui lòng nhập số điện thoại liên hệ của là số có từ 10-11 chữ số');
            $('input[name=phone]').focus();
        }else if(  event == 'undefined' ){
            alert('Vui lòng chọn sự kiện tham gia');
        }
        else{
            $('input[name=reg_submit]').hide();
            $('#status_loading').show();
            $.ajax({
                type : "POST",
                url : nv_base_siteurl + "index.php?" + nv_name_variable + "={MODULE_NAME}&" + nv_fc_variable + "=submit-data&nocache=" + new Date().getTime(),
                data : "reg=1&fullname=" + fullname + '&email=' + email + '&address=' + address + '&phone=' + phone +'&event=' + event +'&provinceid=' + provinceid + '&userid=' + userid,
                success : function(response) {
                    if (response == "OK") {
                        alert('Cảm ơn bạn, chúng tôi đã nhận được thông tin!');
                        window.location.href=window.location.href;
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
    $("#user_search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(nv_base_siteurl + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=userajax", {
                term : extractLast(request.term)
            }, response);
        },
        search : function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        select : function(event, data) {
            nv_add_element( data.item );
            $(this).val('');
            return false;
        }
    });
    function nv_add_element( data ){
        var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.fullname + "<input type=\"hidden\" value=\"" + data.key + "\" name=\"userid\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $('#userid').html( html );
        return false;
    }
    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }
</script>
<!-- END: data -->
<!-- END: main -->