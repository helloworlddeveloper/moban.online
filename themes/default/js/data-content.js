
function validHTML(html) {
    var openingTags, closingTags, html_replace;
    html_end = '';
    html        = html.replace(/<[^>]*\/\s?>/g, '');      // Remove all self closing tags
    html        = html.replace(/<(br|hr|img).*?>/g, '');  // Remove all <br>, <hr>, and <img> tags
    openingTags = html.match(/<[^\/].*?>/g) || [];        // Get remaining opening tags
    closingTags = html.match(/<\/.+?>/g) || [];           // Get remaining closing tags

    if( openingTags.length != closingTags.length ) {
        html_end = openingTags[openingTags.length-1]
        html_end = html_end.replace('<', '</');
    }
    return html_end;
}

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

/*
$(document).ready(function() {
    // Configure/customize these variables.
    var showChar = 505;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "[XEM THÊM]";
    var lesstext = "[THU GỌN]";

    $('.more').each(function(index) {
        var content = $(this).html();
        if(content.length > showChar) {
            var c = content.substr(0, showChar);
            var c = c + ellipsestext + '<a href="" data-item="'+index+'" class="morelink">' + moretext + '</a>' + validHTML(c);
            var h = content.substr(showChar, content.length - showChar);

            var html = '<div class="moretext'+index+'" style="display: inline-block">' + c + '</div>';
            html += '<div class="lesstext'+index+'" style="display: none">' + content + '<a href="" data-item="'+index+'" class="morelink">' + lesstext + '</a></div>';
            $(this).html(html);
        }

    });

    $(".morelink").click(function(){
        var indexitem = $(this).attr('data-item');
        if($('.moretext' + indexitem).is(':visible')) {
            $('.lesstext' + indexitem).css('display', 'inline-block');
            $('.moretext' + indexitem).css('display', 'none');

        } else {
            $('.lesstext' + indexitem).css('display', 'none');
            $('.moretext' + indexitem).css('display', 'inline-block');

        }
        return false;
    });
});

*/
function copyToClipboard_block(obj) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('#' + obj).html()).select();
    document.execCommand("copy");
    $temp.remove();
    $('#' + obj + '_tooltip').tooltip('show');
    setTimeout(function(){ $('#' + obj + '_tooltip').tooltip('hide'); }, 2000);
}
