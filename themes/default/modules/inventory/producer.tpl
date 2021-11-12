<!-- BEGIN: main -->
	<!-- BEGIN: data -->
	<div class="text-center"><a href="{add_more}" class="btn btn-success">{LANG.add_producer_name}</a><br><br></div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td><strong>{LANG.producer_name}</strong></td>
				<td><strong>{LANG.producer_name_note}</strong></td>
				<td width="120px" class="text-center"><strong>{LANG.function}</strong></td>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
			<tr>
				<td>{title}</td>
				<td>{note}</td>
				<td class="text-center"><i class="fa fa-edit">&nbsp;</i><a href="{link_edit}" title="">{LANG.edit}</a>&nbsp; <i class="fa fa-trash-o">&nbsp;</i><a href="{link_del}" class="delete" title="">{LANG.delete}</a></td>
			</tr>
		<!-- END: row -->
		</tbody>
	</table>
	<script type='text/javascript'>
		$(function() {
			$('a.delete').click(function(event) {
				event.preventDefault();
				if (confirm("{LANG.del_confirm}")) {
					var href = $(this).attr('href');
					$.ajax({
						type : 'POST',
						url : href,
						data : '',
						success : function(data) {
							window.location = '{URL_DEL_BACK}';
						}
					});
				}
			});
		});
	</script>
	<!-- END: data -->
	<!-- BEGIN: add -->
		<!-- BEGIN: error -->
		<div class="alert alert-danger">
			{ERROR}
		</div>
		<!-- END: error -->
		<form  action="" method="post">
			<input name="savecat" type="hidden" value="1" />
			<div class="form-group">
				<div class="input-group">
						<span class="input-group-addon">
							{LANG.producer_name}
						</span>
					<input type="text" maxlength="60" value="{DATA.title}" name="title" class="form-control required" placeholder="{LANG.producer_name}" />
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
						<span class="input-group-addon">
							{LANG.producer_name_note}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</span>
					<input type="text" maxlength="60" value="{DATA.note}" name="note" class="form-control" placeholder="{LANG.producer_name_note}" />
				</div>
			</div>
			<br>
			<div class="text-center">
				<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
			</div>
		</form>
	<!-- END: add -->
<!-- END: main -->