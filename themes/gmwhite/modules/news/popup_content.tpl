<link type="text/css" href="{NV_BASE_SITEURL}themes/default/css/popup.style.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />

<div id="box-promotion" style="display:none;" class="box-promotion box-promotion-active">
    <div class="box-promotion-item">
        <div class="box-banner">
            <form class="form" id="form_popup" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DETAIL}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            	<input type="hidden" name="byid" value="0" />
                <input type="hidden" name="show" value="0" />
                <input type="hidden" name="idpost" value="{DETAIL.id}" />
                <input type="hidden" name="modulename" value="{MODULE_NAME}" />
            	<div class="table-responsive">
            		<table class="table" id="form-popup">
                        <caption class="viewonstep">
                           {LANG.input_info_to_download_popup}
                        </caption>
            			<tbody>
                            <tr class="step0">
            					<td class="text-center">
                                    <input type="button" name="show0" class="btn-popup btn btn-warning" value="{LANG.isstudent}" />
                                    <input type="button" name="show1" class="btn-popup btn btn-success" value="{LANG.isparent}" />
                                </td>
            				</tr>
            				<tr class="step1">
            					<td><input class="form-control" placeholder="{LANG.fullname}(*)" type="text" name="fullname" value="{ROW.fullname}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
            				</tr>
            				<tr class="step1">
                                <td>
                                    <div class="uiTokenizer uiInlineTokenizer" style="float:left; width: 100%;">
                                        <span id="school_id" class="tokenarea">
                                            <!-- BEGIN: school_id -->
                                            <span class="uiToken removable" title="{school_name}">
                                                {school_name}<input type="hidden" autocomplete="off" name="school_id" value="{schoolid}" />
                                                <a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
                                            </span>
                                            <!-- END: school_id -->
                                        </span>
                                        <span class="uiTypeahead">
                                            <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
                                            <div class="innerWrap" style="float: left;">
                                                <input id="school_id_search" type="text" name="schooltext" placeholder="{LANG.school_id}" class="form-control textInput" />
                                            </div>
                                        </span>
                                    </div>
                                 </td>
            				</tr>
                            <!--
                            <tr class="step1">
            					<td>
                                    <select class="form-control" onchange="nv_get_district(this.value, 0)" name="provinceid">
                                        <option value="0">{LANG.provinceid}</option>
                                        <!-- BEGIN: provinceid  -->
                                        <option value="{PROVINCE.id}">{PROVINCE.title}</option>
                                        <!-- END: provinceid  -->
                                    </select>
                                    <div id="district_DETAIL" style="padding-top:10px">
                                        <select style="width: 100%;" class="form-control" name="districtid">
                                            <option value="0">{LANG.districtid}</option>
                    				    </select>
                                    </div>
                                </td>
            				</tr>
                            -->
                            <tr class="step1">
            					<td>
                                    <input type="button" name="next2" value="{LANG.next}" class="btn btn-primary" />
                                </td>
            				</tr>
                            <tr class="step2">
            					<td>
                                    <select class="form-control" name="class_study">
                                        <!-- BEGIN: class_study  -->
                                        <option value="{class_study}">{LANG.classes} {class_study}</option>
                                        <!-- END: class_study  -->
                                    </select>
                                </td>
            				</tr>
            				<tr class="step2">
            					 <td><input class="form-control" type="text" placeholder="{LANG.phone}(*)" name="phone" value="{ROW.phone}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
            				</tr>
                            <tr class="forparent">
            					<td><input class="form-control" placeholder="{LANG.email}" type="text" name="email" value="{ROW.email}" /></td>
            				</tr>
                            <tr class="step2">
            					<td><input class="form-control" type="text" placeholder="{LANG.address}" name="address" /></td>
            				</tr>
                            <tr class="step2">
            					<td>
                                    <input type="button" name="prev1" value="{LANG.prev}" class="btn btn-primary" />
                                    <input type="button" name="next3" value="{LANG.get_file}" class="btn btn-primary" />
                                </td>
            				</tr>
                            <tr class="step3">
            					<td>
                                    <div style="font-size:17px;color:#fff"><span id="url_link">{LANG.link_download}&nbsp;<a target="_blank" onclick="nv_download_file('{DETAIL.id}', '{DETAIL.newscheckss}');return false;" target="_blank" href="javascript:void(0);"><strong style="color: #f00;">{LANG.vaoday}</strong></a>&nbsp;{LANG.todownload}</span></div>
                                </td>
            				</tr>
            			</tbody>
            		</table>
            	</div>
            </form>
        </div>
        <a class="box-promotion-close" href="javascript:;" title="Ðóng lại"></a>
    </div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.autocomplete.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#box-promotion").hasClass("box-promotion")
        {
            fBoxPromotion();
        }
    });

    function fBoxPromotion() {
        var _boxPromotion = $(".box-promotion");
        var _boxPromotionItem = $(".box-promotion .box-promotion-item");
        var _widthSite = $(window).width();
        var _heightSite = $(window).height();

        $(_boxPromotionItem).css({
            "width": "" + _widthSite + "px"
          , "height": "" + _heightSite + "px"
          , "left": "" + (_widthSite -500) / 2 + "px"
          , "top": "" + (_heightSite - 500) / 2 + "px"
        });
    };
    jQuery(document).ready(function ($) {
    	$('#button-download-popup').click(function () {
			$('#box-promotion').fadeIn('medium');
    		$('#TheBlogWidgets, .box-promotion-close').click(function () {
    			$('#box-promotion').stop().fadeOut('medium');
    		});
		});
    });

    function nv_get_district(provinceid, districtid) {
        if( provinceid == 0 ){
            provinceid = $('select[name=provinceid]').val();
        }
    	$.post(nv_siteroot + 'index.php?' + nv_name_variable + '=popup&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'loaddistrict=1&provinceid=' + provinceid + '&districtid=' + districtid, function(res) {
    		$("#district_DETAIL").html( res );
    	});
    }
    
    $("#school_id_search").bind("keydown", function(event) {
    	if (event.keyCode === $.ui.keyCode.TAB && $(this).DETAIL("ui-autocomplete").menu.active) {
    		event.preventDefault();
    	}
    	}).autocomplete({
    	source : function(request, response) {
    		$.getJSON(nv_siteroot + 'index.php?' + nv_name_variable + "=popup&" + nv_fc_variable + "=main&search_school=1", {
    			term : extractLast(request.term),
                provinceid: $('select[name=provinceid]').val(),
                districtid: $('select[name=districtid]').val()
    		}, response);
    	},
    	search : function() {
    		// custom minLength
    		var term = extractLast(this.value);
    		if (term.length < 2) {
    			return false;
    		}
    	},
    	select : function(event, DETAIL) {
            nv_add_element( 'school_id', DETAIL.item );
            $(this).val('');
            return false;
    	}
    });
    function nv_add_element( id_add, DETAIL ){
       var html = "<span title=\"" + DETAIL.value + "\" class=\"uiToken removable\">" + DETAIL.value + "<input type=\"hidden\" value=\"" + DETAIL.key + "\" name=\""+id_add+"\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#" + id_add).html( html );
    	return false;
    }
    function split(val) {
    	return val.split(/,\s*/);
    }
    
    function extractLast(term) {
    	return split(term).pop();
    }

    $('.step1,.step2,.step3,.forparent').hide();
    $('input[name=show0],input[name=show1]').click(function(){
        if( $(this).attr('name') == 'show1' ){
            $('select[name=class_study] > option').each(function() {
              $('select[name=class_study] option[value="' + $(this).val() + '"]').text("{LANG.classes_1} " + $(this).val())
            });
            $('input[name="show"]').val('1');
            $('#school_id_search').attr('placeholder', '{LANG.school_id_1}')
        }
        $('.step1').show();
        $('.step0').hide();
    })
    $('input[name=next2]').click(function(){
        
        $('input,select').parent().removeClass('has-error has-feedback');
        var fullname = $('input[name="fullname"]').val();
        if( fullname == '')
        {
            $('input[name="fullname"]').focus();
            $('input[name="fullname"]').attr('title', '{LANG.error_fullname}');
            $('input[name="fullname"]').tooltip('show');
            $('input[name="fullname"]').parent().addClass('has-error has-feedback');
             return false;
        }
        if( $('input[name="show"]').val() == 1 ){
            $('.forparent').show();   
        }
        $('.step2').show();
        $('.step1').hide();
    })
    $('input[name=prev1]').click(function(){
        $('.step1').show();
        $('.step2').hide();
    })
    $('input[name=prev2]').click(function(){
        $('.step2').show();
        $('.step3').hide();
    })
    
    $('input[name=next3]').click(function(){
        var phone = $('input[name="phone"]').val();
        var intRegexPhone = /^[0-9]+$/
        if((phone.length < 10 || phone.length > 11) || (!intRegexPhone.test(phone)))
        {
            $('input[name="phone"]').focus();
            $('input[name="phone"]').attr('title', '{LANG.error_phone}');
            $('input[name="phone"]').tooltip('show');
            $('input[name="phone"]').parent().addClass('has-error has-feedback');
             return false;
        }
        $('.step3').show();
        $('.step2').hide();
        var address = $('input[name=address]').val();
        if( address != ''){
            $.ajax({
              url: "https://maps.googleapis.com/maps/api/geocode/json?address="+address+'&sensor=false&key=AIzaSyDrAxUjw5tgYSn55I946qyqH1NkFi_EuUw',
              type: "POST",
              success: function(res){
                if( res.status != 'ZERO_RESULTS'){
                    var gmap_lat = res.results[0].geometry.location.lat;
                    var gmap_lng = res.results[0].geometry.location.lng;
                    save_data( gmap_lat, gmap_lng);
                }else{
                    save_data( 0, 0);
                }
              }
            });
        }else{
            save_data( 0, 0);
        }
    })
    function save_data(gmap_lat, gmap_lng){
        $.post(nv_siteroot + 'index.php?' + nv_name_variable + '=popup&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'submit=1&gmap_lat=' + gmap_lat + '&gmap_lng=' + gmap_lng + '&' + $('#form_popup').serialize(), function(res) {
            res = res.split('_');
    		if( res[0] == 'OK'){
                $('.step2').hide();
                $('.step3').show();
                $.cookie('popup_site_{module_data}', '1', {
            		path: '/',
            		expires: 60
            	});
    		}
    	});
    }
    //<![CDATA[
    jQuery.cookie = function (key, value, options) {
    
    	if (arguments.length > 1 && String(value) !== "[object Object]") {
    		options = jQuery.extend({}, options);
    		if (value === null || value === undefined) {
    			options.expires = -1;
    		}
    		if (typeof options.expires === 'number') {
    			var days = options.expires,
    				t = options.expires = new Date();
    			t.setDate(t.getDate() + days);
    		}
    		value = String(value);
    		return (document.cookie = [
    			encodeURIComponent(key), '=',
    			options.raw ? value : encodeURIComponent(value),
    			options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
    			options.path ? '; path=' + options.path : '',
    			options.domain ? '; domain=' + options.domain : '',
    			options.secure ? '; secure' : ''
    		].join(''));
    	}
    	// key and possibly options given, get cookie...
    	options = value || {};
    	var result, decode = options.raw ? function (s) {
    		return s;
    	} : decodeURIComponent;
    	return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
    };
</script>
