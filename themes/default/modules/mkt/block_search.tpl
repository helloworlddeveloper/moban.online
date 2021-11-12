<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

    <ul class="nav nav-tabs">
        <li><a href="#tab1">{LANG_BLOCK.search_room_empty}</a></li>
        <li><a href="#tab2">{LANG_BLOCK.price_room}</a></li>
        <li><a href="#tab3">{LANG_BLOCK.contact_view_room}</a></li>
    </ul>
    <div class="tab_container">
        <div class="tab_content" id="tab1">
            <form class="form-inline" action="{NV_BASE_SITEURL}" method="get">
                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
            	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
            	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP_NAME}" />
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td>
                            <select class="w100 form-control" name="category">
                                <option value="0">--{LANG_BLOCK.category}--</option>
                                <!-- BEGIN: cat -->
                                <option{CAT.sl} value="{CAT.catid}">{CAT.title}</option>
                                <!-- END: cat -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control" name="start_hour" style="float:left">
                                <option value="">{LANG_BLOCK.start_hour}</option>
                                <!-- BEGIN: start_hour -->
                                <option{START_HOUR.sl} value="{START_HOUR.val}">{START_HOUR.val}</option>
                                <!-- END: start_hour -->
                            </select>
                            <input style="float:left;width:90px" placeholder="{LANG_BLOCK.date}" class="form-control w100px" type="text" name="timestart" value="{timestart}" id="timestart" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control" name="end_hour" style="float:left">
                                <option value="">{LANG_BLOCK.end_hour}</option>
                                <!-- BEGIN: end_hour -->
                                <option{END_HOUR.sl} value="{END_HOUR.val}">{END_HOUR.val}</option>
                                <!-- END: end_hour -->
                            </select>
                            <input style="float:left;width:90px" placeholder="{LANG_BLOCK.date}" class="form-control" type="text" name="timeend" value="{timeend}" id="timeend" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
                        </td>
                    </tr>
                    <tfoot>
                        <tr>
                            <td><input type="submit" class="btn btn-primary" name="submit" value="{LANG_BLOCK.search}" /></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
        <div class="tab_content" id="tab2">
            <form class="form-inline" action="" method="get">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td>
                            <input class="form-control w100" type="text" name="price_fullname" placeholder="{LANG_BLOCK.fullname} *" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control w100" type="text" name="price_email" placeholder="{LANG_BLOCK.email} *" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control w100" type="text" name="price_phone" placeholder="{LANG_BLOCK.phone} *" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control w100" type="text" name="price_company" placeholder="{LANG_BLOCK.company}" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select class="w100 form-control" name="price_category">
                                <option value="0">--{LANG_BLOCK.category}--</option>
                                <!-- BEGIN: price_cat -->
                                <option{CAT.sl} value="{CAT.catid}">{CAT.title}</option>
                                <!-- END: price_cat -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {LANG_BLOCK.timestart}:&nbsp;
                            <select class="form-control" name="price_start_hour">
                                <option value="">{LANG_BLOCK.start_hour}</option>
                                <!-- BEGIN: price_start_hour -->
                                <option{PRICE_START_HOUR.sl} value="{PRICE_START_HOUR.val}">{PRICE_START_HOUR.val}</option>
                                <!-- END: price_start_hour -->
                            </select>
                            <input style="width:90px" placeholder="{LANG_BLOCK.date}" class="form-control w100px" type="text" name="price_timestart" value="{timestart}" id="timestart" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {LANG_BLOCK.timeend}:&nbsp;
                            <select class="form-control" name="price_end_hour">
                                <option value="">{LANG_BLOCK.end_hour}</option>
                                <!-- BEGIN: price_end_hour -->
                                <option{PRICE_END_HOUR.sl} value="{PRICE_END_HOUR.val}">{PRICE_END_HOUR.val}</option>
                                <!-- END: price_end_hour -->
                            </select>
                            <input style="width:90px" placeholder="{LANG_BLOCK.date}" class="form-control" type="text" name="price_timeend" value="{timeend}" id="timeend" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea name="price_description" class="w100 form-control" placeholder="{LANG_BLOCK.price_room_des}"></textarea>
                        </td>
                    </tr>
                    <tfoot>
                        <tr>
                            <td><input class="btn btn-primary" type="button" name="submit" value="{LANG_BLOCK.price_room_send}" /></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
        <div class="tab_content" id="tab3">
            <form class="form-inline" action="{NV_BASE_SITEURL}" method="get">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td>
                            <input class="form-control w100" type="text" name="view_fullname" placeholder="{LANG_BLOCK.fullname} *" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control w100" type="text" name="view_email" placeholder="{LANG_BLOCK.email} *" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control w100" type="text" name="view_phone" placeholder="{LANG_BLOCK.phone} *" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control w100" type="text" name="view_company" placeholder="{LANG_BLOCK.company}" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {LANG_BLOCK.contact_view_room_time}:&nbsp;
                            <select class="form-control" name="hourview">
                                <option value="">{LANG_BLOCK.start_hour}</option>
                                <!-- BEGIN: timeview -->
                                <option{TIMEVIEW.sl} value="{TIMEVIEW.val}">{TIMEVIEW.val}</option>
                                <!-- END: timeview -->
                            </select>
                            <input style="width:90px" placeholder="{LANG_BLOCK.date}" class="form-control w100px" type="text" name="date_view" id="timestart" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea name="view_description" class="w100 form-control" placeholder="{LANG_BLOCK.price_room_des}"></textarea>
                        </td>
                    </tr>
                    <tfoot>
                        <tr>
                            <td><input class="btn btn-primary" type="button" name="submit" value="{LANG_BLOCK.price_room_send}" /></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<style type="text/css">
    .w100{width:100% !important}
</style>
<script type="text/javascript">
    //Default Action
    $(".tab_content").hide(); //Hide all content
    $("ul.nav-tabs li:first").addClass("active").show(); //Activate first tab
    $(".tab_content:first").show(); //Show first tab content
    //On Click Event
    $("ul.nav-tabs li").click(function() {
    $("ul.nav-tabs li").removeClass("active"); //Remove any "active" class
    $(this).addClass("active"); //Add "active" class to selected tab
    $(".tab_content").hide(); //Hide all tab content
    var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
    $(activeTab).fadeIn(); //Fade in the active content
    return false;
    }); 
//<![CDATA[
	$("#timestart,#timeend").datepicker({
		showOn : "focus",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});
    $('#tab2 input[name=submit]').click(function(){
       var fullname = $('input[name=price_fullname]').val();
       var email = $('input[name=price_email]').val();
       var phone = $('input[name=price_phone]').val();
       var company = $('input[name=price_company]').val();
       var category = $('select[name=price_category]').val();
       var start_hour = $('select[name=price_start_hour]').val();
       var timestart = $('input[name=price_timestart]').val();
       var end_hour = $('select[name=price_end_hour]').val();
       var timeend = $('input[name=price_timeend]').val();
       var description = $('textarea[name=price_description]').val();
       if( fullname == '' ){
            $('input[name=price_fullname]').focus();
       }else if( email == '' ){
            $('input[name=price_email]').focus();
       }else if( phone == '' ){
            $('input[name=price_phone]').focus();
       }else{
              alert('Feature is Building...!')      
       }
    }); 
    $('#tab3 input[name=submit]').click(function(){
       var fullname = $('input[name=view_fullname]').val();
       var email = $('input[name=view_email]').val();
       var phone = $('input[name=view_phone]').val();
       var company = $('input[name=view_company]').val();
       var hourview = $('select[name=hourview]').val();
       var date_view = $('input[name=date_view]').val();
       var description = $('textarea[name=view_description]').val();
       if( fullname == '' ){
            $('input[name=view_fullname]').focus();
       }else if( email == '' ){
            $('input[name=view_email]').focus();
       }else if( phone == '' ){
            $('input[name=view_phone]').focus();
       }else{
              alert('Feature is Building...!')      
       }
    }); 
//]]>
</script>
<!-- END: main -->