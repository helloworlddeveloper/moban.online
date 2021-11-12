<!-- BEGIN: main -->
<a href="{link_module}">{LANG.danhsach}</a>
<br />
<script type="text/javascript">
	var de_del_cofirm = "{LANG.de_del_cofirm}";
	var de_duyet_cofirm = "{LANG.de_duyet_cofirm}";
	var nv_not_duyet = "{LANG.nv_not_duyet}";
	var doempty = "{LANG.doempty}"; 
</script>

<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->

<div class="text-center">
	<h3 class="text-danger">{LANG.detail_question}</h3>
	<h4><strong>{LANG.add_time}: {ORDER.addtime}</strong></h4>
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>
			{TABLE_CAPTION}
		</caption>
		<colgroup>
			<col class="w200" />
		</colgroup>
		<tbody>
			<tr>
				<td><strong>{LANG.full_name}</strong></td>
				<td>{ORDER.cus_name}</td>
			</tr>
			<tr>
				<td><strong>{LANG.email_cus}</strong></td>
				<td>{ORDER.cus_email}</td>
			</tr>
			<tr>
				<td><strong>{LANG.title}</strong></td>
				<td>{ORDER.title}</td>

			</tr>
			<tr>
				<td><strong>Chủ đề</strong></td>
				<td>{ORDER.cattitle}</td>

			</tr>
			<tr>
				<td><strong>{LANG.ndquestion}</strong></td>
				<td>{ORDER.question}</td>
			</tr>
		</tbody>
	</table>
</div>

<!-- BEGIN: an -->
<form action="{ACTION_FILE}" method="post" name="list_post" id="list_post">
	<div class="well">
		<label><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /><strong>{LANG.checkall}</strong></label>
		<span class="pull-right">{LANG.info_bill} ({num})</span>
	</div>

	<!-- BEGIN: phanhoi -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" id ="idcheck[]"> &nbsp;&nbsp;{ROW.cus_name} đã trả lời: <span class="pull-right"> (<!-- BEGIN: duyet --> <a href="{ROW.link_duyet}" onclick="nv_de_duyet({ROW.id},{ORDER.qid});">{LANG.duyet}</a> | <!-- END: duyet --> <a href="{ROW.link_edit}" class="changeContent">{LANG.edit}</a> | <span > <a href="{ROW.link_del}" onclick="nv_de_dels({ROW.id},{ORDER.qid});">{GLANG.delete}</a></span>)
				{ROW.addtime} </span>
		</div>
		<div class="panel-body">
			{ROW.answer}
		</div>
	</div>

	<div class="form-inline">
		<!-- END: phanhoi -->
		<select id="action" class="form-control w100">
			<!-- BEGIN: action -->
			<option value="{key_action}">{value_action}</option>
			<!-- END: action -->
		</select>
		<input type="button" onclick="faqstion('{OP}','{ORDER.qid}');" value="OK" class="btn btn-primary">
		<input type="submit" value="{LANG.info_bill}" class="btn btn-primary" name="save" id="save" />
	</div>
</form>
<br />
<!-- END: an -->

<form action="{ACTION_FILE}" method="post">
	<!-- BEGIN: nophanhoi -->
	<script type="text/javascript">
		$(function() {
			$('#tab').hide();
			$('#save').click(function() {
				$('#tab').show();
				$('#save').remove();
			});
		});
	</script>
	<div id="tab">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<td>{CONTENT}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<input type="submit" value="{LANG.send}" class="btn btn-primary" name="send"/>
	</div>
	<!-- END: nophanhoi -->
	<!-- BEGIN: edit -->
	<script type="text/javascript">
		$(function() {
			$('#save').remove();
			$('#tab').remove();
		});
	</script>

	<div id="tab2">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<caption>
					{TABLE_EDIT}
				</caption>
				<tbody>
					<tr>
						<td>{CONTENT}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<input type="submit" value="{LANG.send}" class="btn btn-primary" name="gui"/>
	</div>
	<!-- END: edit -->
</form>
<!-- END: main -->