/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (quanglh268@gmail.com)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Fri, 12 Jan 2018 02:38:03 GMT
 */

$( "#search_data" ).change(function () {
    search_data_users();
})

function search_data_users() {

    $.ajax({
        type : "GET",
        url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main' + '&nocache=' + new Date().getTime(),
        data : 'get_user_json=1&id=' + $( "#search_data" ).val(),
        dataType: "json",
        success : function(data) {
            $.each(data, function (index, value) {
                if(index == 'image'){
                    if( value != ''){
                        $('#' + index).html('<img style="width: 150px" class="img-thumbnail bg-gainsboro" src="' + value + '" />');
                    }
                }else{
                    $('#' + index).html(value);
                }

            })
        }
    });

    return false;
}

function uploadAvatar(a) {
    nv_open_browse(a, "NVImg", 650, 430, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no");
    return !1;
}


var UAV = {};
// Default config, replace it with your own
UAV.config = {
    inputFile: 'image_file',
    uploadIcon: 'upload_icon',
    pattern: /^(image\/jpeg|image\/png)$/i,
    maxsize: 2097152,
    avatar_width: 80,
    avatar_height: 80,
    max_width: 1500,
    max_height: 1500,
    target: 'preview',
    uploadInfo: 'uploadInfo',
    uploadGuide: 'guide',
    x: 'crop_x',
    y: 'crop_y',
    w: 'crop_width',
    h: 'crop_height',
    originalDimension: 'original-dimension',
    displayDimension: 'display-dimension',
    imageType: 'image-type',
    imageSize: 'image-size',
    btnSubmit: 'btn-submit',
    btnReset: 'btn-reset',
    uploadForm: 'upload-form'
};
// Default language, replace it with your own
UAV.lang = {
    bigsize: 'File too large',
    smallsize: 'File too small',
    filetype: 'Only accept jmage file tyle',
    bigfile: 'File too big',
    upload: 'Please upload and drag to crop'
};
UAV.data = {
    error: false,
    busy: false,
    cropperApi: null
};
UAV.tool = {
    bytes2Size: function(bytes) {
        var sizes = ['Bytes', 'KB', 'MB'];
        if (bytes == 0) return 'n/a';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
    },
    update: function(e) {
        $('#' + UAV.config.x).val(e.x);
        $('#' + UAV.config.y).val(e.y);
        $('#' + UAV.config.w).val(e.width);
        $('#' + UAV.config.h).val(e.height);
    },
    clear: function(e) {
        $('#' + UAV.config.x).val(0);
        $('#' + UAV.config.y).val(0);
        $('#' + UAV.config.w).val(0);
        $('#' + UAV.config.h).val(0);
    }
};
// Please use this package with fengyuanchen/cropper https://fengyuanchen.github.io/cropper
UAV.common = {
    read: function(file) {
        $('#' + UAV.config.uploadIcon).hide();
        var fRead = new FileReader();
        fRead.onload = function(e) {
            $('#' + UAV.config.target).show();
            $('#' + UAV.config.target).attr('src', e.target.result);
            $('#' + UAV.config.target).on('load', function() {
                var img = document.getElementById(UAV.config.target);
                var boxWidth = $('#' + UAV.config.target).innerWidth();
                var boxHeight = Math.round(boxWidth * img.naturalHeight / img.naturalWidth);
                var minCropBoxWidth = UAV.config.avatar_width / (img.naturalWidth / boxWidth);
                var minCropBoxHeight = UAV.config.avatar_height / (img.naturalHeight / boxHeight);
                if (img.naturalWidth > UAV.config.max_width || img.naturalHeight > UAV.config.max_height) {
                    UAV.common.error(UAV.lang.bigsize);
                    UAV.data.error = true;
                    return false;
                }
                if (img.naturalWidth < UAV.config.avatar_width || img.naturalHeight < UAV.config.avatar_height) {
                    UAV.common.error(UAV.lang.smallsize);
                    UAV.data.error = true;
                    return false;
                }
                if (!UAV.data.error) {
                    // Hide and show data
                    $('#' + UAV.config.uploadGuide).hide();
                    $('#' + UAV.config.uploadInfo).show();
                    $('#' + UAV.config.imageType).html(file.type);
                    $('#' + UAV.config.imageSize).html(UAV.tool.bytes2Size(file.size));
                    $('#' + UAV.config.originalDimension).html(img.naturalWidth + ' x ' + img.naturalHeight);

                    UAV.data.cropperApi = $('#' + UAV.config.target).cropper({
                        viewMode: 3,
                        dragMode: 'crop',
                        aspectRatio: 1,
                        responsive: true,
                        modal: true,
                        guides: false,
                        highlight: true,
                        autoCrop: false,
                        autoCropArea: 0.1,
                        movable: false,
                        rotatable: false,
                        scalable: false,
                        zoomable: false,
                        zoomOnTouch: false,
                        zoomOnWheel: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        minCropBoxWidth: minCropBoxWidth,
                        minCropBoxHeight: minCropBoxHeight,
                        minContainerWidth: 10,
                        minContainerHeight: 10,
                        crop: function(e) {
                            UAV.tool.update(e);
                        },
                        built: function(e) {
                            var imageData = $(this).cropper('getImageData');
                            var cropBoxScale = imageData.naturalWidth / imageData.width;
                            imageData.width = parseInt(Math.floor(imageData.width));
                            imageData.height = parseInt(Math.floor(imageData.height));
                            var cropBoxSize = {
                                width: 80 / cropBoxScale,
                                height: 80 / cropBoxScale
                            };
                            cropBoxSize.left = (imageData.width - cropBoxSize.width) / 2;
                            cropBoxSize.top = (imageData.height - cropBoxSize.height) / 2;
                            $(this).cropper('crop');
                            $(this).cropper('setCropBoxData', {
                                left: cropBoxSize.left,
                                top: cropBoxSize.top,
                                width: cropBoxSize.width,
                                height: cropBoxSize.height
                            });
                            $('#' + UAV.config.w).val(imageData.width);
                            $('#' + UAV.config.h).val(imageData.height);
                            $('#' + UAV.config.displayDimension).html(imageData.width + ' x ' + imageData.height);
                        }
                    });
                } else {
                    $('#' + UAV.config.uploadIcon).show();
                }
            });
        };
        fRead.readAsDataURL(file);
    },
    init: function() {
        UAV.data.error = false;
        if ($('#' + UAV.config.inputFile).val() == '') {
            UAV.data.error = true;
        }
        var image = $('#' + UAV.config.inputFile)[0].files[0];
        // Check ext
        if (!UAV.config.pattern.test(image.type)) {
            UAV.common.error(UAV.lang.filetype);
            UAV.data.error = true;
        }
        // Check size
        if (image.size > UAV.config.maxsize) {
            UAV.common.error(UAV.lang.bigfile);
            UAV.data.error = true;
        }
        if (!UAV.data.error) {
            // Read image
            UAV.common.read(image);
        }
    },
    error: function(e) {
        UAV.common.reset();
        alert(e);
    },
    reset: function() {
        if (UAV.data.cropperApi != null) {
            UAV.data.cropperApi.cropper('destroy');
            UAV.data.cropperApi = null;
        }
        UAV.data.error = false;
        UAV.data.busy = false;
        UAV.tool.clear();
        $('#' + UAV.config.target).removeAttr('src').removeAttr('style').hide();
        $('#' + UAV.config.uploadIcon).show();
        $('#' + UAV.config.uploadInfo).hide();
        $('#' + UAV.config.uploadGuide).show();
        $('#' + UAV.config.imageType).html('');
        $('#' + UAV.config.imageSize).html('');
        $('#' + UAV.config.originalDimension).html('');
        $('#' + UAV.config.w).val('');
        $('#' + UAV.config.h).val('');
        $('#' + UAV.config.displayDimension).html('');
    },
    submit: function() {
        if (!UAV.data.busy) {
            if ($('#' + UAV.config.w).val() == '' || $('#' + UAV.config.w).val() == '0') {
                alert(UAV.lang.upload);
                return false;
            }
            UAV.data.busy = true;
            return true;
        }
        return false;
    }
};
UAV.init = function() {
    $('#' + UAV.config.uploadIcon).click(function() {
        $('#' + UAV.config.inputFile).trigger('click');
    });
    $('#' + UAV.config.inputFile).change(function() {
        UAV.common.init();
    });
    $('#' + UAV.config.btnReset).click(function() {
        if (!UAV.data.busy) {
            UAV.common.reset();
            $('#' + UAV.config.uploadIcon).trigger('click');
        }
    });
    $('#' + UAV.config.uploadForm).submit(function() {
        return UAV.common.submit();
    });
};



function FormatNumber(str) {

    var strTemp = GetNumber(str);
    if (strTemp.length <= 3)
        return strTemp;
    strResult = "";
    for (var i = 0; i < strTemp.length; i++)
        strTemp = strTemp.replace(".", "");
    var m = strTemp.lastIndexOf(",");
    if (m == -1) {
        for (var i = strTemp.length; i >= 0; i--) {
            if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
                strResult = "." + strResult;
            strResult = strTemp.substring(i, i + 1) + strResult;
        }
    } else {
        var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf(","));
        var strphanthapphan = strTemp.substring(strTemp.lastIndexOf(","), strTemp.length);
        var tam = 0;
        for (var i = strphannguyen.length; i >= 0; i--) {

            if (strResult.length > 0 && tam == 4) {
                strResult = "." + strResult;
                tam = 1;
            }

            strResult = strphannguyen.substring(i, i + 1) + strResult;
            tam = tam + 1;
        }
        strResult = strResult + strphanthapphan;
    }
    return strResult;
}

function GetNumber(str) {
    var count = 0;
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == " ")
            return str.substring(0, i);
        if (temp == ",") {
            if (count > 0)
                return str.substring(0, ipubl_date);
            count++;
        }
    }
    return str;
}

function IsNumberInt(str) {
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == ",") {
            return str.substring(0, i);
        }
    }
    return str;
}
function split(val) {
    return val.split(/,\s*/);
}

function extractLast(term) {
    return split(term).pop();
}
function nv_add_element( idElment, key, value ){
    var html = "<div class='clearfix'>" +
        "<div class='col-md-6' ondblclick=\"$(this).parent().remove();\" ><span title=\"" + value + "\" class=\"uiToken removable\"><i class=\"fa fa-remove\"></i>&nbsp;&nbsp;" + value + "<input type=\"hidden\" value=\"" + key + "\" name=\"" + idElment + "[]\" autocomplete=\"off\"></span></div>";
    html += '<div class="col-md-18"><span class="fl">&nbsp;&nbsp;' + chucvu + ':&nbsp;</span>\n<input class="fl dotted-input" name="chucvu[]"></div>';
    $("#" + idElment).append( html );
    return false;
}

function check_data_inventory(a) {
    var currentTime = new Date();
    if( isNaN(parseInt( $('input[name=hour]').val()))){
        $('input[name=hour]').focus();
        return false;
    }
    else if( isNaN(parseInt( $('input[name=minute]').val()))){
        $('input[name=minute]').focus();
        return false;
    }
    else if( isNaN(parseInt( $('input[name=day]').val())) || parseInt( $('input[name=day]').val()) > 31){
        $('input[name=day]').focus();
        return false;
    }
    else if( isNaN(parseInt( $('input[name=month]').val())) || parseInt( $('input[name=month]').val()) > 12){
        $('input[name=month]').focus();
        return false;
    }
    else if( isNaN(parseInt( $('input[name=year]').val())) || parseInt( $('input[name=year]').val()) > currentTime.getFullYear()){
        $('input[name=year]').focus();
        return false;
    }
    else{
        $.ajax({
            type: $(a).prop("method"),
            cache: !1,
            url: $(a).prop("action"),
            data: $(a).serialize(),
            dataType: "json",
            success: function(b) {
                $('[type="submit"]', $(a)).prop('disabled', false);
                if( b.status == "error" ) {
                    alert(b.mess);
                } else {
                    window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=inventory-list'
                }
            }
        });
    }

    return false;
}

function load_departmentid(departmentid) {
 window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=inventory&departmentid=' + departmentid + '&nocache=' + new Date().getTime();
}