<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<div id="edit">
    <!-- BEGIN: return1 -->
    <div class="alert alert-success">
        <strong>{RETURN}</strong>
        <p>Mật khẩu chỉ xuất hiện 1 lần duy nhất. Vì vậy hay copy thông tin này và gửi cho ĐL, NPP ngay để tránh thất lạc!</p>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.fullname}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.fullname}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.mobile}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.mobile}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.chossen_agency}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.agency.title} - {DATA.agency_info}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.province_name}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.province_name}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.district_name}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.district_name}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.username}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.username}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.password}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.password}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="text-center">
            <a href="{back_edit}" class="btn btn-success">{LANG.back_edit}</a>
        </div>
    </div>
    <!-- END: return1 -->
    <!-- BEGIN: return2 -->
    <div class="alert alert-success">
        <strong>{RETURN}</strong>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.fullname}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.fullname}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.mobile}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.mobile}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.chossen_agency}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.agency.title} - {DATA.agency_info}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.province_name}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.province_name}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.district_name}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.district_name}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.address}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.address}</p>
        </div>
    </div>
    <!--
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.cmnd}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.cmnd}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.ngaycap}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.ngaycap}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.noicap}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.noicap}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.stknganhang}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.stknganhang}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.tennganhang}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.tennganhang}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-6 col-md-6 control-label">{LANG.chinhanh}</label>
        <div class="col-sm-18 col-md-18">
            <p class="form-control-static">{DATA.chinhanh}</p>
        </div>
    </div>
    -->
    <div class="form-group">
        <div class="text-center">
            <a href="{back_edit}" class="btn btn-success">{LANG.back_edit}</a>
        </div>
    </div>
    <!-- END: return2 -->
    <!-- BEGIN: error -->
    <div class="alert alert-danger">
        - {ERROR}
    </div>
    <!-- END: error -->
    <script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
    <link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

    <form enctype="multipart/form-data" onsubmit="return sendsunmit();" action="{NV_BASE_SITEURL}index.php" method="post">
        <input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
        <input type="hidden" name ="userid" value="{DATA.userid}" />
        <input name="savecat" type="hidden" value="1" />
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption>
                    <em class="fa fa-file-text-o">&nbsp;</em>{LANG.create_account_agency}
                </caption>
                <tr>
                    <td>{LANG.fullname} <span class="require">(*)</span></td>
                    <td>
                        <input type="text" placeholder="{LANG.fullname}" value="{DATA.fullname}" name="fullname" class="form-control" />
                    </td>
                </tr>
				<tr>
                    <td>{LANG.birthday} <span class="require">(*)</span></td>
                    <td>
                        <input class="form-control" name="birthday" id="birthday" value="{DATA.birthday}" style="width: 90px;" maxlength="10" type="text"/>
                    </td>
                </tr>
				<tr>
                    <td>{LANG.gender} <span class="require">(*)</span></td>
                    <td>
                        <select class="form-control chosen-select" name="gender">
                            <!-- BEGIN: gender -->
                            <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                            <!-- END: gender -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.job} <span class="require">(*)</span></td>
                    <td>
                        <select class="form-control chosen-select" name="jobid">
                            <option value="0">{LANG.job_select}</option>
                            <!-- BEGIN: job -->
                            <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                            <!-- END: job -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.mobile} <span class="require">(*)</span></td>
                    <td>
                        <input type="text" placeholder="{LANG.mobile}" value="{DATA.mobile}" name="mobile" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.email}</td>
                    <td>
                        <input type="text" placeholder="{LANG.email}" value="{DATA.email}" name="email" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.istype} <span class="require">(*)</span></td>
                    <td>
                        <select name="istype" class="form-control">
                            <option value="-1">--- {LANG.istype_select} ---</option>
                            <!-- BEGIN: istype -->
                            <option{OPTION.selected} value="{OPTION.key}">{OPTION.title}</option>
                            <!-- END: istype -->
                        </select>
                    </td>
                </tr>
                <!-- BEGIN: agency -->
                <tr>
                    <td>{LANG.chossen_agency} <span class="require">(*)</span></td>
                    <td>
                        <select name="agencyid" class="form-control">
                            <option value="0">--{LANG.select_agency}--</option>
                            <!-- BEGIN: loop -->
                            <option{AGENCY.sl} value="{AGENCY.id}">{AGENCY.title} - {AGENCY.agency_info}</option>
                            <!-- END: loop -->
                        </select>
                    </td>
                </tr>
                <!-- END: agency -->
                <tr>
                    <td>{LANG.province_select} <span class="require">(*)</span></td>
                    <td>
                        <select onchange="nv_load_district('districtid', 0)" class="form-control chosen-select" name="provinceid">
                            <option value=""> --- </option>
                            <!-- BEGIN: select_province -->
                            <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                            <!-- END: select_province -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.district_select} <span class="require">(*)</span></td>
                    <td>
                        <div id="load_district">
                            <select  class="form-control" name="districtid" id="district" style="width:100%;" tabindex="2">
                                <option value="0"> --- </option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.address} <span class="require">(*)</span></td>
                    <td>
                        <input type="text" placeholder="{LANG.address}" value="{DATA.address}" name="address" class="form-control" />
                    </td>
                </tr>
                <tr class="info_istype_0">
                    <td>{LANG.cmnd} <span class="require">(*)</span></td>
                    <td>
                        <input type="text" placeholder="{LANG.cmnd}" value="{DATA.peopleid}" name="peopleid" class="form-control" />
                    </td>
                </tr>
                <tr class="info_istype_0">
                    <td>
                        {LANG.cmnd_1} <span class="require">(*)</span>
                    </td>
                    <td>
                        <label class="btn btn-success fileinput-button" for="fileUpload_befor">
                            <i class="fa fa-file-image-o"></i>
                            <input id="fileUpload_befor" name="photo_befor" type="file" style="display:none;" />
                            {LANG.select_file}
                        </label>
                        <div id="image-befor">
                            <!-- BEGIN: image_befor -->
                            <img src="{NV_BASE_SITEURL}{NV_UPLOADS_DIR}/{MODULE_NAME}/{DATA.photo_befor}" class="photo-thumb" />
                            <!-- END: image_befor -->
                        </div>
                        <input type="hidden" name="photo_befor" value="{DATA.photo_befor}" />
                    </td>
                </tr>
                <tr class="info_istype_0">
                    <td>
                        {LANG.cmnd_2} <span class="require">(*)</span>
                    </td>
                    <td>
                        <label class="btn btn-success fileinput-button" for="fileUpload_after">
                            <i class="fa fa-file-image-o"></i>
                            <input id="fileUpload_after" name="photo_after" type="file" style="display:none;" />
                            {LANG.select_file}
                        </label>
                        <div id="image-after">
                            <!-- BEGIN: image_after -->
                            <img src="{NV_BASE_SITEURL}{NV_UPLOADS_DIR}/{MODULE_NAME}/{DATA.photo_after}" class="photo-thumb" />
                            <!-- END: image_after -->
                        </div>
                        <input type="hidden" name="photo_after" value="{DATA.photo_after}" />
                    </td>
                </tr>
                <tr class="info_istype_1">
                    <td>
                        {LANG.gpkd} <span class="require">(*)</span>
                    </td>
                    <td>
                        <label class="btn btn-success fileinput-button" for="fileUpload_gpkd">
                            <i class="fa fa-file-image-o"></i>
                            <input id="fileUpload_gpkd" name="gpkd" type="file" style="display:none;" />
                            {LANG.select_file}
                        </label>
                        <div id="image_gpkd">
                            <!-- BEGIN: image_gpkd -->
                            <img src="{NV_BASE_SITEURL}{NV_UPLOADS_DIR}/{MODULE_NAME}/{DATA.gpkd}" class="photo-thumb" />
                            <!-- END: image_gpkd -->
                        </div>
                        <input type="hidden" name="gpkd" value="{DATA.gpkd}" />
                    </td>
                </tr>
                <tr class="info_istype_1">
                    <td>
                        {LANG.photo_shops} <span class="require">(*)</span>
                    </td>
                    <td>
                        <label class="btn btn-success fileinput-button" for="fileUpload_photo_shops">
                            <i class="fa fa-file-image-o"></i>
                            <input id="fileUpload_photo_shops" name="photo_shops" type="file" style="display:none;" />
                            {LANG.select_file}
                        </label>
                        <div id="photo_shops">
                            <!-- BEGIN: photo_shops -->
                            <img src="{NV_BASE_SITEURL}{NV_UPLOADS_DIR}/{MODULE_NAME}/{DATA.photo_shops}" class="photo-thumb" />
                            <!-- END: photo_shops -->
                        </div>
                        <input type="hidden" name="photo_shops" value="{DATA.photo_shops}" />
                    </td>
                </tr>
                <tr class="info_istype_1">
                    <td>
                        {LANG.photo_product_in_shops} <span class="require">(*)</span>
                    </td>
                    <td>
                        <label class="btn btn-success fileinput-button" for="fileUpload_photo_product_in_shops">
                            <i class="fa fa-file-image-o"></i>
                            <input id="fileUpload_photo_product_in_shops" name="photo_product_in_shops" type="file" style="display:none;" />
                            {LANG.select_file}
                        </label>
                        <div id="photo_product_in_shops">
                            <!-- BEGIN: photo_product_in_shops -->
                            <img src="{NV_BASE_SITEURL}{NV_UPLOADS_DIR}/{MODULE_NAME}/{DATA.photo_product_in_shops}" class="photo-thumb" />
                            <!-- END: photo_product_in_shops -->
                        </div>
                        <input type="hidden" name="photo_product_in_shops" value="{DATA.photo_product_in_shops}" />
                    </td>
                </tr>
                <!--
                <tr>
                    <td>{LANG.ngaycap}</td>
                    <td>
                        <input type="text" placeholder="{LANG.ngaycap}" value="{DATA.ngaycap}" name="ngaycap" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.noicap}</td>
                    <td>
                        <input type="text" placeholder="{LANG.noicap}" value="{DATA.noicap}" name="noicap" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.stknganhang}</td>
                    <td>
                        <input type="text" placeholder="{LANG.stknganhang}" value="{DATA.stknganhang}" name="stknganhang" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.tennganhang}</td>
                    <td>
                        <input type="text" placeholder="{LANG.tennganhang}" value="{DATA.tennganhang}" name="tennganhang" class="form-control" />
                    </td>
                </tr>
                <tr>
                    <td>{LANG.chinhanh}</td>
                    <td>
                        <input type="text" placeholder="{LANG.chinhanh}" value="{DATA.chinhanh}" name="chinhanh" class="form-control" />
                    </td>
                </tr>
                -->
                </tbody>
            </table>
        </div>
        <br />
        <div class="text-center">
            <!-- BEGIN: data_send -->
            <input class="btn btn-primary" name="submit1" type="submit" value="{LANG.send_register}" />
            <!-- END: data_send -->
            <!-- BEGIN: data_update -->
            <input class="btn btn-primary" name="submit1" type="submit" value="{LANG.data_update}" />
            <!-- END: data_update -->
        </div>
    </form>
</div>
<script type="text/javascript">
    $("#birthday").datepicker({
        showOn : "focus",
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
        buttonImageOnly : true
    });
    $('.info_istype_0').hide();
    $('.info_istype_1').hide();
    $('select[name=istype]').change(function () {
        change_istype( $(this).val() );
    })
    change_istype( '{DATA.istype}' );
    function change_istype( value ){
        if( value == 0){
            $('.info_istype_0').show();
            $('.info_istype_1').hide();
        }else{
            $('.info_istype_1').show();
            $('.info_istype_0').hide();
        }
    }
    $(document).ready(function() {
        $("#fileUpload_befor").on('change', function() {
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#image-befor");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "photo-thumb"
                        }).appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);

                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Pls select only images");
            }
        });

        $("#fileUpload_after").on('change', function() {
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#image-after");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "photo-thumb"
                        }).appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);

                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Pls select only images");
            }
        });
        $("#fileUpload_gpkd").on('change', function() {
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#image_gpkd");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "photo-thumb"
                        }).appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);

                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Pls select only images");
            }
        });
        $("#fileUpload_photo_shops").on('change', function() {
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#photo_shops");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "photo-thumb"
                        }).appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);

                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Pls select only images");
            }
        });
        $("#fileUpload_photo_product_in_shops").on('change', function() {
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#photo_product_in_shops");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "photo-thumb"
                        }).appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);

                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Pls select only images");
            }
        });
    });

    function nv_load_district( select_name, selected_id ){
        var province = $('select[name=provinceid]').val();
        if( province == 0 )$('#load_district').html('');
        else{
            $.post(nv_base_siteurl + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'act=district&province=' + province + '&select_name=' + select_name + '&selected_id=' + selected_id, function(res) {
                $('#load_district').html(res)
            });
        }
    }
    function sendsunmit(){
        $('input[type=submit]').attr('disabled', true)
        return true;
    }
    <!-- BEGIN: load_district -->
    nv_load_district( 'districtid', '{DATA.districtid}' );
    <!-- END: load_district -->
</script>
<!-- END: main -->