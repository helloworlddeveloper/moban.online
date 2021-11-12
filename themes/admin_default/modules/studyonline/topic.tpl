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
					<input class="btn btn-primary" type="button" name="add_question" value="{LANG.add_topic}" onclick="window.location='{URL_ADD}';"/>
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
			<thead>
			<tr>
				<th>{LANG.classid}</th>
				<th>{LANG.subjectid}</th>
				<th>{LANG.topic_name}</th>
				<th>{LANG.startweek}</th>
				<th>{LANG.circle_title}</th>
				<th>{LANG.status}</th>
				<th>&nbsp;</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="7">{NV_GENERATE_PAGE}</td>
			</tr>
			</tfoot>
			<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td> {VIEW.classid} </td>
				<td> {VIEW.subjectid} </td>
				<td> {VIEW.topic_name} </td>
				<td> {VIEW.startweek} </td>
				<td> {VIEW.circle} </td>
				<td> {VIEW.status} </td>
				<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
			</tr>
			<!-- END: loop -->
			<!-- BEGIN: generate_page -->
			<tr>
				<td colspan="7">
                    {GENERATE_PAGE}
				</td>
			</tr>
			</tbody>
			<!-- END: generate_page -->
		</table>
	</div>
</form>
<!-- END: main -->
