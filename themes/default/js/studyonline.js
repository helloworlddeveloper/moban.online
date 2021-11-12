/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 06:56:00 GMT
 */
 

function sendrating(id, point, newscheckss) {
    if (point == 1 || point == 2 || point == 3 || point == 4 || point == 5) {
        $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + newscheckss + '&point=' + point, function(res) {
            res = res.split('|');
            $('#stringrating').html(res[0]);
            if (typeof res[1] != 'undefined' && res[1] != '0') {
                $('#numberrating').html(res[1]);
            }
            if (typeof res[2] != 'undefined' && res[2] != '0') {
                $('#click_rating').html(res[2]);
            }
        });
    }
}


function fix_news_image(){
    var news = $('#studyonline-bodyhtml'), newsW, w, h;
    if( news.length ){
        var newsW = news.innerWidth();
        $.each($('img', news), function(){
            if( typeof $(this).data('width') == "undefined" ){
                w = $(this).innerWidth();
                h = $(this).innerHeight();
                $(this).data('width', w);
                $(this).data('height', h);
            }else{
                w = $(this).data('width');
                h = $(this).data('height');
            }
            
            if( w > newsW ){
                $(this).prop('width', newsW);
                $(this).prop('height', h * newsW / w);
            }
        });
    }
}

$(window).on('load', function() {
    fix_news_image();
});

$(window).on("resize", function() {
    fix_news_image();
});

function nvLoadVideo(lesson_id, video_id) {
    $('#embed_video_youtube, #embed_video_wowza').hide();
    var playerblob = videojs('embed_video_wowza');
    playerblob.pause();
    stop();//youtube stop
    $.ajax({
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=check',
            type: "POST",
            dataType: 'json',
            data: ({
                video_id: video_id,
                lesson_id: lesson_id
            }),
            success: function (resp) {
                if(resp.status == 1){
                    if(resp.type == 1){
                        $('#embed_video_youtube').html('');
                        $('#embed_video_container').html('<video poster="'+ resp.image +'" width="100%" src="' + resp.value + '" controls loop preload="auto" poster="" >HTML5 Video is required for this example</video>');
                    }else if(resp.type == 2){
                        $('#embed_video_wowza').show();
                        $('#embed_video_youtube').html('');
                        $('#embed_video_wowza_html5_api').show();
                        playerblob.src({
                            src: resp.value,
                            type: 'application/x-mpegURL'
                          });  
                    }else if(resp.type == 3){
                        //function youtube
                        $('#embed_video_youtube').show();
                        $('#embed_video_container').html('');
                        loadVideo(resp.value, '0', 'default');
                    } 
                }
            }
        });
        
    //$('#embed_video_container video').attr('src', $('#lesson_' + video_id).attr('item-path'));
    var user_id =0;
    if (user_id > 0) {
        $.ajax({
            url: '/eLessonOnline/updateLastVideoId',
            type: "POST",
            data: ({
                lesson_id: 9500,
                video_id: video_id
            }),
            success: function (resp) {
            }
        });
    }
}

function changeVideo(lesson_id, video_id) {
    if ($('#lesson_' + video_id).attr('class') == 'active') {
        $('#lesson_' + video_id).removeClass('active');
    } else {
        $('.lesson').removeClass('active');
        $('#lesson_' + video_id).addClass('active');
    }
    nvLoadVideo(lesson_id, video_id);
}

$(".buy_baihoc").click(function(){
    if (nv_is_user == 0){
        loginForm('');
    }else{
        if(confirm( buy_baihoc_confrim + ' ' + $(this).attr('data_baihoc_title') + '?')){
            $.ajax({
              type: "POST",
              url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=buy&nocache=' + new Date().getTime(),
              data: 'lesson_id=' + $(this).attr('data_baihoc_id') + '&khoahocid=' + $(this).attr('data_khoahoc_id'),
              success: function(result){
                   if( result.status == 200 || result.status == 201 ){
                        alert( result.message );
                        window.location.href=window.location.href;
                   }else if( result.status == 0 ){
                        loginForm("");
                   }else if( result.status == 2 ){
                        alert(result.message);
                        if( confirm(result.message) ){
                            window.location.href= nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=taikhoan';   
                        }
                   }else{
                	   alert(result.message);
                   }
              },
              dataType: "json",
            });  
        }
    }
 });
 $(".buy_khoahoc").click(function(){
    if (nv_is_user == 0){
        loginForm('');
    }else{
        if(confirm( buy_khoahoc_confrim + ' ' + $(this).attr('data_khoahoc_title') + '?')){
            $.ajax({
              type: "POST",
              url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=buy&nocache=' + new Date().getTime(),
              data: 'khoahocid=' + $(this).attr('data_khoahoc_id'),
              success: function(result){
                   if( result.status == 200 || result.status == 201 ){
                        alert( result.message );
                        window.location.href=window.location.href;
                   }else if( result.status == 0 ){
                        loginForm("");
                   }else if( result.status == 2 ){
                        if( confirm(result.message) ){
                            window.location.href= nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=taikhoan';   
                        }
                   }else{
                	   alert(result.message);
                   }
              },
              dataType: "json",
            });  
        }
    }
 });

$('input[name=sendreview]').click(function(){
    var revirecontent = $('textarea[name=revirecontent]').val();
    if( revirecontent.trim() == ''){
        $('textarea[name=revirecontent]').focus();
    }else{
        var khoahocid = $('input[name=khoahocid]').val();
        var checkress = $('input[name=checkress]').val();
        $.ajax({
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=actionajax',
            type: "POST",
            dataType: 'json',
            data: ({
                action: 'review',
                khoahocid: khoahocid,
                checkress: checkress,
                reviewcontent: revirecontent
            }),
            success: function (resp) {
                $('#popupModal').modal('hide');
                alert(resp.message);
            }
        });
    }
});

function send_taikhoan_data( module_send, idproduct, product_title, data_money, money_unit, tokenkey, actiontype ){
    $.ajax({
        type: "POST",
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=taikhoan&' + nv_fc_variable + '=ws&nocache=' + new Date().getTime(),
        data: 'product_id=' + idproduct + '&product_title=' + product_title + '&module_send=' + module_send + '&money=' + data_money + '&money_unit=' + money_unit + '&tokenkey=' + tokenkey + '&actiontype=' + actiontype,
        success: function(result){
            if( result.status != 200 ){
                alert( result.message )
            }else{
                //kiem tra cac dieu kien thanh cong thuc hien lenh
            }
        },
        dataType: "json",
    });
}