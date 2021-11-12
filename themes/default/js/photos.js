/**
 * @Project PHOTOS 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 * @Copyright (C) 2015 tradacongnghe.com
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21 Sep 2015 14 : 12 GMT +7
 */

function sendrating_album(album_id, point, checkss) {
	if (point == 1 || point == 2 || point == 3 || point == 4 || point == 5) {
		$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&nocache=' + new Date().getTime(), 'album_id=' + album_id + '&checkss=' + checkss + '&point=' + point, function(res) {
			$('#stringrating').html(res);
		});
	}
}

function detai_view_next(next_id, view_next){
	$('#detail_viewer').load( nv_base_siteurl + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=detail_viewer&nocache=' + new Date().getTime(),'&ajax=1&row_id=' + next_id, function() {
		FB.XFBML.parse();
	});
}
function detai_view_pre(pre_id, view_previous){
	$('#detail_viewer').load( nv_base_siteurl + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=detail_viewer&nocache=' + new Date().getTime(),'&ajax=1&row_id=' + pre_id, function() {
		FB.XFBML.parse();
	});
}


$('body').on('click', '.deleterows', function(e) {

    var row_id = $(this).attr('data-row');
    var token = $(this).attr('data-token');
    var token_image = $(this).attr('data-token-image');
    var token_thumb = $(this).attr('data-token-thumb');
    var key = $(this).attr('data-key');
    var thumb = $('input[name="albums['+ key +'][thumb]"]').val();
    var image_url = $('input[name="albums['+ key +'][image_url]"]').val();
    if(confirm(lang_confirm) ) {
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&action=deleterows&nocache=' + new Date().getTime(),
            type: 'post',
            dataType: 'json',
            data: 'album_id=' + album_id + '&row_id=' + row_id + '&token=' + token + '&token_image=' + token_image + '&token_thumb=' + token_thumb + '&thumb=' + thumb + '&image_url=' + image_url,
            beforeSend: function() {
                $('#images-' + key + ' .deleterows .fa-spinner').css('display', 'block');
            },
            complete: function() {
                $('#images-' + key + ' .fa-spinner').css('display', 'none');
            },
            success: function(json) {
                $('.alert').remove();
                $("html, body").animate({ scrollTop: 0 }, "slow");

                if (json['error']) {
                    $('#content').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {

                    $('#images-' + key).remove();

                    $('#content').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    if( $('input[name="albums['+key+'][defaults]"]' ).is(":checked") )
                    {
                        $('body .fixradio').get(0).checked = true;
                    }else
                    {
                        var check = 0;
                        $('body .fixradio').each(function()
                        {
                            if( $(this).is(":checked") )
                            {
                                ++check;
                            }
                        });
                        if( check == 0 )
                        {
                            $('body .fixradio').get(0).checked = true;
                        }
                    }

                }

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});


$('#button-delete').on('click', function() {
    if(confirm(lang_del_confirm))
    {
        var listid = [];
        $("input[name=\"selected[]\"]:checked").each(function() {
            listid.push($(this).val());
        });
        if (listid.length < 1) {
            alert(lang_please_select_one);
            return false;
        }

        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&action=delete&nocache=' + new Date().getTime(),
            type: 'post',
            dataType: 'json',
            data: 'listid=' + listid + '&token='+del_token,
            beforeSend: function() {
                $('#button-delete i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('#button-delete').prop('disabled', true);
            },
            complete: function() {
                $('#button-delete i').replaceWith('<i class="fa fa-trash-o"></i>');
                $('#button-delete').prop('disabled', false);
            },
            success: function(json) {
                $('.alert').remove();

                if (json['error']) {
                    $('#content').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#content').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                    $.each(json['id'], function(i, id) {
                        $('#group_' + id ).remove();
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});
