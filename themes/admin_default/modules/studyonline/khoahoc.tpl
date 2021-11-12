<!-- BEGIN: main -->
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td>
				<form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="form-control" style="width:130px;" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.filter_enterkey}"/>
                    <select class="form-control"  class="text" name="classid">
                        <option value="0">--{LANG.filter_all_class}--</option>
						<!-- BEGIN: class -->
						<option value="{CLASS.id}"{CLASS.selected}>{CLASS.title}</option>
						<!-- END: class -->
					</select>
                    <select class="form-control"  class="text" name="subjectid">
                        <option value="0">--{LANG.filter_all_subject}--</option>
						<!-- BEGIN: subject -->
						<option value="{SUBJECT.id}"{SUBJECT.selected}>{SUBJECT.title}</option>
						<!-- END: subject -->
					</select>
                    <input class="btn btn-primary" type="button" name="do" value="{LANG.filter_action}"/>
    					<input class="btn btn-default" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
    					<input class="btn btn-default" type="button" name="clear" value="{LANG.filter_clear}"/>
                        <input class="btn btn-primary" type="button" name="add_question" value="{LANG.add_khoahoc}" onclick="window.location='{URL_ADD}';"/>
                    <div class="clearfix">&nbsp;</div>
				</form>
			</td>
		</tr>
	</tbody>
</table>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('input[name=clear]').click(function(){
		$('#filter-form .text').val('');
		$('input[name=q]').val('');
	});
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();
		var f_class = $('select[name=classid]').val();
		var f_subject = $('select[name=subjectid]').val();
		if (  f_q != '' || f_class != 0 || f_subject != 0 )
		{
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&subjectid=' + f_subject + '&classid=' + f_class;	
		}
		else
		{
			alert ('{LANG.filter_err_submit}');
		}
	});
});
</script>

<form action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td style="width:30px">ID</td>
                <td>{LANG.title_khoahoc}</td>
				<td style="width:70px">{LANG.class_name}</td>
                <td style="width:80px">{LANG.subject_name}</td>
				<td>{LANG.teacher_of_khoahoc}</td>
                <td style="width:100px">{LANG.price}</td>
                
                <td style="width:40px;" class="text-center"><i title="{LANG.numview}" class="fa fa-eye">&nbsp;</i></td>
                <td style="width:40px;" class="text-center"><i title="{LANG.numlike}" class="fa fa-hand-peace-o">&nbsp;</i></td>
                <td style="width:40px;" class="text-center"><i title="{LANG.numbuy}" class="fa fa-usd">&nbsp;</i></td>
				<td style="width:90px">{LANG.addtime}</td>
				<td class="center">{LANG.feature}</td>
			</tr>
		<!-- BEGIN: row -->
			<tr class="topalign">
				<td>{ROW.id}</td>
				<td>{ROW.title}</td>
                <td>{ROW.class_name}</td>
                <td>{ROW.subject_name}</td>
                <td>{ROW.teacher}</td>
                <td>{ROW.price}</td>
                <td class="text-center">{ROW.numview}</td>
                <td class="text-center">{ROW.numlike}</td>
                <td class="text-center">{ROW.numbuy}</td>
				<td><strong>{ROW.addtime}</strong></td>
				<td class="center">
                    <!-- BEGIN: emailmarketing -->
                    <span id="status_{ROW.id}"><em class="fa fa-handshake-o">&nbsp;</em><a href="javascript:void(0);" class="emailmarketing" data_khoahoc="{ROW.id}">{LANG.emailmarketing}</a></span>
                    <!-- END: emailmarketing -->
                    <em class="fa fa-file-video-o">&nbsp;</em><a href="{ROW.qlbaihoc}">{LANG.qlbaihoc}</a>
                    <em class="fa fa-edit fa-lg">&nbsp;</em><a href="{ROW.url_edit}">{LANG.edit}</a>
					<em class="fa fa-trash-o fa-lg"></em>&nbsp;<a href="javascript:void(0);" onclick="nv_delete_khoahoc({ROW.id});">{GLANG.delete}</a>
				</td>
			</tr>
		<!-- END: row -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="9">
					{GENERATE_PAGE}
				</td>
			</tr>
		</tbody>
		<!-- END: generate_page -->
	</table>
</div>
</form>
<script type="text/javascript">
    $('.emailmarketing').click(function(){
        var id_khoa_hoc = $(this).attr('data_khoahoc');
        $('#status_' + id_khoa_hoc).html('<img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" />');
        $.ajax({
			type : 'POST',
			url : '{emailmarketing_link}',
			data : '&id=' + id_khoa_hoc,
			success : function(data) {
				var r_split = data.split('_');
				if( r_split[0] == 'OK' ){
				    $('#status_' + id_khoa_hoc).html('[OK]');
				}
				else{
				    alert(r_split[1]);
					window.location.href = window.location.href;
				}
			}
		});
        
    })
</script>
<!-- END: main -->
