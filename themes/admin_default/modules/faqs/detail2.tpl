<!-- BEGIN: main -->
<a href="{link_module}">{LANG.danhsach}</a>
<br />
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->

<div class="text-center">
	<h3 class="red">{LANG.detail_question}</h3>
	<h4><strong>{LANG.add_time}: {ORDER.addtime}</strong></h4>
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>
			{TABLE_CAPTION}
		</caption>

		<tbody>
			<tr>
				<td><strong>{LANG.cat}</strong> </td>
				<td>{cat}</td>
				<td><strong>{LANG.full_name}</strong></td>
				<td>{ORDER.cus_name}</td>
			</tr>
			<tr>
				<td width="150"><strong>{LANG.title}</strong> </td>
				<td>{ORDER.title}</td>
				<td width="150"><strong>{LANG.email_cus}</strong></td>
				<td>{ORDER.cus_email}</td>
			</tr>
			<tr>
				<td width="150"><strong>{LANG.ndquestion}</strong> </td>
				<td colspan="3">{ORDER.question}</td>

			</tr>
		</tbody>
		<!-- BEGIN: files -->
		<tr>
			<td><strong>{LANG.file}</strong></td>
			<td>
				<a id="myfile{ORDER.qid}" href="{ORDER.links}" onclick="nv_download_files('{ORDER.links}');return false;">{ORDER.titles}</a>
			</td>
		</tr>
		<!-- END: files -->
	</table>
</div>

<input type="submit" value="{LANG.duyetque}" class="btn btn-success" name="accept" />
<script type="text/javascript">
	$('input[name="accept"]').click(function(){
		if( confirm( nv_is_change_act_confirm[0] ) )
		{
			var qid = '{ORDER.qid}';
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=de&nocache=' + new Date().getTime(), 'accept=1&qid=' + qid, function(res) {
				if( res == 'OK' )
				{
					alert( nv_is_change_act_confirm[1] );
					window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=duyetque';
				}
				else
				{
					alert( nv_is_change_act_confirm[2] );
				}
			});
		}
	});
</script>
<!-- END: main -->