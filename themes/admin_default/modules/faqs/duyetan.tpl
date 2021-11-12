<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<div class="clear">
</div>
<!-- END: error -->

<!-- BEGIN: content -->
<script type="text/javascript">
	var de_del_cofirm = "{LANG.de_del_cofirm}";
</script>
<form action="{FORM_ACTION}" method="get">
<input type="hidden" name ='nv' value={MODULE_NAME}>
<input type="hidden" name ='op' value={OP}>

<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/easyTooltip.js" type="text/javascript"></script>
<script src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-vi.js" type="text/javascript"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />

{LANG.dateselect}

</form>
<div class="table-responsive" style="margin-top: 10px">
	<table class="table table-striped table-bordered table-hover">
        <caption>{TABLE_CAPTION}</caption>
        <colgroup>
        	<col class="w50" />
        	<col />
        	<col span="2" class="w200" />
        	<col span="2" class="w150" />
        </colgroup>
        <thead>
            <tr>
                <th class="text-center">{LANG.sort}</th>
                <th>{LANG.ndan}</th>
                <th>{LANG.name_cus}</th>
                <th>{LANG.email_cus}</th>
                <th>{LANG.time_order}</th>
                <th class="text-center">{LANG.feature}</th>
            </tr>
        </thead>
        <tbody>
        	<!-- BEGIN: loop -->
        		<tr>
        			<td class="text-center">{ROW.sort}</td>
	                <td>{ROW.answer}</td>
	                <td>{ROW.cus_name}</td>
	                <td>{ROW.cus_email}</td>
	                <td>{ROW.addtime}</td>
	                <td class="text-center">
	                	<em class="fa fa-search fa-lg">&nbsp;</em><a href="{ROW.detail_url}">{LANG.detail}</a>
	                </td>
        		</tr>
        	<!-- END: loop -->
        <tbody>
        <!-- BEGIN: generate_page -->
	    <tr class="footer">
	        <td colspan="8">
	            {GENERATE_PAGE}
	        </td>
	    </tr>
	    <!-- END: generate_page -->
	</table>
</div>

<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$("#to,#from").datepicker({
			showOn: "both",
			dateFormat: "dd.mm.yy",
			changeMonth: true,
			changeYear: true,
			showOtherMonths: true,
			buttonImage: nv_siteroot+"images/calendar.gif",
			buttonImageOnly: true
		});
	});

//]]>
</script>
<!-- END: content -->

<!-- END: main -->