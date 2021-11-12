<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<!-- BEGIN: step1 -->
<div class="well">
    <div class="form-group clearfix">
        <div class="col-md-14">
            <h1>Đăng ký web site bán hàng cùng CASH 13</h1>
            <ul class="item-regsite">
                <li>Chỉ mất 30s để khởi tạo 1 website bán hàng chuyên nghiệp.</li>
                <li>Quản lý chủ động bằng tài khoản và số điện thoại.</li>
                <li>Nhận thông báo đơn hàng qua SMS chủ động.</li>
                <li>Nhận hệ thống quản lý kho hàng và doanh thu</li>
                <li>Được truy cập kho tài nguyên, tài liệu về sản phẩm - bán hàng...</li>
            </ul>
        </div>
        <div class="col-md-10">
            <span class="error" id="error"></span><span class="ok" id="ok"></span>
            <div id="input_mobile">
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon">
                        <em class="fa fa-phone fa-lg fa-horizon"></em>
                    </span>
                        <input type="text" maxlength="60" value="" name="fphone" id="fphone" class="form-control" placeholder="Nhập số điện thoại của bạn để nhận mã kích hoạt">
                    </div>
                </div>
                <!-- BEGIN: captcha -->
                <img width="{GFX_WIDTH}" height="{GFX_HEIGHT}" title="{LANG.captcha}" alt="{LANG.captcha}" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" class="captchaImg display-inline-block">
                <em onclick="change_captcha('.fcode');" title="{GLANG.captcharefresh}" class="fa fa-pointer fa-refresh margin-left margin-right"></em>
                <input type="text" placeholder="{LANG.captcha}" maxlength="{NV_GFX_NUM}" value="" name="fcode" class="fcode required form-control display-inline-block" style="width:100px;" data-pattern="/^(.){{NV_GFX_NUM},{NV_GFX_NUM}}$/" data-mess="{LANG.error_captcha}"/>
                <!-- END: captcha -->
                <!-- BEGIN: recaptcha -->
                <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}"></div></div>
                <script type="text/javascript">
                    nv_recaptcha_elements.push({
                        id: "{RECAPTCHA_ELEMENT}",
                        btn: $('[type="button"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent().parent())
                    })
                </script>
                <!-- END: recaptcha -->
                <div class="clearfix">&nbsp;</div>
                <div class="text-center"><input type="button" name="check_phone" value="Lấy mã xác nhận" class="btn btn-primary"/><br><br><a class="regsite_have_code" href="javascript:void(0);">Có mã kích hoạt?&nbsp;</a></div>
            </div>
            <div id="input_code">
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon">
                        <em class="fa fa-lock fa-lg fa-horizon"></em>
                    </span>
                        <input type="text" maxlength="60" value="" name="code" id="code" class="form-control" placeholder="Nhập mã kích hoạt gửi đến điện thoại">
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center"><input type="button" name="check_code" value="Đăng ký site" class="btn btn-primary"/><br><br><a class="regsite_get_code" href="javascript:void(0);">Lấy mã kích hoạt mới!&nbsp;</a></div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
<script type="text/javascript">
    $('#input_mobile').show();
    $('#input_code').hide();
</script>
<!-- END: step1 -->
<!-- BEGIN: step2 -->
<div class="well">Bạn đang đăng ký website với số điện thoại <strong>{DATA_CODE.mobile}</strong>.</div>
<form enctype="multipart/form-data" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tfoot>
            <tr>
                <td colspan="2" class="text-center"><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary"/></td>
            </tr>
            </tfoot>
            <tbody>
            <tr>
                <td><strong>{LANG.domain_name}</strong> <sup class="required">(∗)</sup></td>
                <td>
                    <input type="text" placeholder="Viết thường không dấu, và chỉ có các ký tự từ a-z. VD: nguyenha" class="form-control" style="width:300px;float:left" name="domain_name" value="{DATA.domain}" /><label>&nbsp;.cash13.vn</label>
                    <label id="result_domain"></label>
                </td>
            </tr>
            <!--
            <tr>
                <td><strong>{LANG.mobile_refer}</strong> <sup class="required">(∗)</sup></td>
                <td><input type="text" style="width: 100%" class="form-control" name="mobile_refer" placeholder="Nhập số điện thoại người giới thiệu bạn. Nếu không có nhập: 0868236236" value="{DATA.mobile_refer}" /></td>
            </tr>
            -->
            <tr>
                <td><strong>{LANG.site_title}</strong> <sup class="required">(∗)</sup></td>
                <td><input type="text" style="width: 100%" class="form-control" name="site_title" placeholder="VD: NPP CASH 13 Nguyễn Hồng Thảo" value="{DATA.site_title}" /></td>
            </tr>
            <tr>
                <td><strong>{LANG.site_email}</strong></td>
                <td><input type="text" style="width: 100%" placeholder="VD: nguyenha@gmail.com" class="form-control" name="site_email" value="{DATA.site_email}" /></td>
            </tr>
            <tr>
                <td><strong>{LANG.facebook_link}</strong></td>
                <td><input type="text" style="width: 100%" class="form-control" placeholder="VD: https://www.facebook.com/cash13group/" name="facebook_link" value="{DATA.facebook_link}" /></td>
            </tr>
            <tr>
                <td><strong>{LANG.site_banner}</strong></td>
                <td>
                    <div class="page panel panel-default">
                        <div class="panel-body bg-lavender">
                            <div class="margin-bottom">
                                <img id="site_banner" class="img-thumbnail bg-gainsboro" src="{DATA.image_site}" width="{DATA.photoWidth}" height="{DATA.photoHeight}" data-default="{AVATAR_DEFAULT}" />
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary btn-xs margin-right-sm" onclick="changeAvatar('{URL_BANNER}');">
                                    {LANG.change_avatar}
                                </button>
                                <button type="button" class="btn btn-danger btn-xs" id="delavatar" onclick="deleteAvatar('#myavatar','{DATA.checkss}',this)"{DATA.imgDisabled}>
                                    {GLANG.delete}
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>{LANG.site_image}</strong></td>
                <td>
                    <div class="page panel panel-default">
                        <div class="panel-body bg-lavender">
                            <div class="margin-bottom">
                                <img id="myavatar" class="img-thumbnail bg-gainsboro" src="{DATA.photo}" width="{DATA.photoWidth}" height="{DATA.photoHeight}" data-default="{AVATAR_DEFAULT}" />
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary btn-xs margin-right-sm" onclick="changeAvatar('{URL_AVATAR}');">
                                    {LANG.change_avatar}
                                </button>
                                <button type="button" class="btn btn-danger btn-xs" id="delavatar" onclick="deleteAvatar('#myavatar','{DATA.checkss}',this)"{DATA.imgDisabled}>
                                    {GLANG.delete}
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</form>
<!-- END: step2 -->
<!-- END: main -->