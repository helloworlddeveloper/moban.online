<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<div class="well">Bạn đang cập thông tin website của bạn.</div>
<form enctype="multipart/form-data" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="id" value="{DATA.id}">
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
                    <label><a href="http://{DATA.domain}">{DATA.domain}</a></label>
                </td>
            </tr>
            <tr>
                <td><strong>{LANG.site_mobile}</strong> <sup class="required">(∗)</sup></td>
                <td>
                    <label>{DATA.mobile}</label>
                </td>
            </tr>
            <tr>
                <td><strong>{LANG.site_title}</strong> <sup class="required">(∗)</sup></td>
                <td><input type="text" style="width: 100%" class="form-control" name="site_title" placeholder="VD: NPP CASH 13 Nguyễn Hồng Thảo" value="{DATA.title}" /></td>
            </tr>
            <tr>
                <td><strong>{LANG.site_email}</strong></td>
                <td><input type="text" style="width: 100%" placeholder="VD: nguyenha@gmail.com" class="form-control" name="site_email" value="{DATA.email}" /></td>
            </tr>
            <tr>
                <td><strong>{LANG.facebook_link}</strong></td>
                <td><input type="text" style="width: 100%" class="form-control" placeholder="VD: https://www.facebook.com/cash13group/" name="facebook_link" value="{DATA.facebook}" /></td>
            </tr>
            <tr>
                <td><strong>Kênh Youtube</strong></td>
                <td><input type="text" style="width: 100%" class="form-control" placeholder="Nhập url kênh youtube của bạn" name="youtube" value="{DATA.youtube}" /></td>
            </tr>
            <tr>
                <td><strong>Instagram</strong></td>
                <td><input type="text" style="width: 100%" class="form-control" placeholder="Nhập url tài khoản Instagram" name="instagram" value="{DATA.instagram}" /></td>
            </tr>
            <tr>
                <td><strong>Zalo</strong></td>
                <td><input type="text" style="width: 100%" class="form-control" placeholder="Nhập sđt sử dụng Zalo" name="zalo" value="{DATA.zalo}" /></td>
            </tr>
            <tr>
                <td><strong>{LANG.site_banner}</strong></td>
                <td>
                    <div class="margin-bottom">
                        <img id="site_banner" class="img-thumbnail bg-gainsboro" src="{DATA.image_site}" width="80" height="80" data-default="{AVATAR_DEFAULT}" />
                    </div>
                    <div>
                        <button type="button" class="btn btn-success btn-xs margin-right-sm" onclick="changeAvatar('{URL_BANNER}');">
                            {LANG.change_avatar}
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>{LANG.site_image}</strong></td>
                <td>
                    <div class="margin-bottom">
                        <img id="myavatar" class="img-thumbnail bg-gainsboro" src="{DATA.photo}" width="80" height="80" data-default="{AVATAR_DEFAULT}" />
                    </div>
                    <div>
                        <button type="button" class="btn btn-success btn-xs margin-right-sm" onclick="changeAvatar('{URL_AVATAR}');">
                            {LANG.change_avatar}
                        </button>
                    </div>
                </td>
            </tr>
            </tr>
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->