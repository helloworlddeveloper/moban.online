<!-- BEGIN: main -->
<a href="{link_module}">{LANG.danhsach}</a>
<br />

<!-- BEGIN: error -->
<div class="alert alert-danger">
	{error}
</div>
<!-- END: error -->

<form action="{ACTION_FILE}" name="frm" method="post" >
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w150" />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.title}</td>
					<td>
						<input type="text" size="40" value="{CONTENT.title}" id="title" name="title" class="form-control" />
					</td>
				</tr>
				<tr>
					<td> {LANG.cat} </td>
					<td>
					<select name="catid" class="form-control w200">
						<!-- BEGIN: catid -->
						<option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.title}</option>
						<!-- END: catid -->
					</select></td>
				</tr>
				<tr>
					<td>{LANG.ndquestion}</td>
					<td>{HTMLQS}</td>
				</tr>
				<tr>
					<td>{LANG.full_name}</td>
					<td><input type="text" size="40" value="{CONTENT.cus_name}" id="full_name" name="full_name" class="form-control w200" /></td>
				</tr>
				<tr>
					<td>{LANG.email}</td>
					<td><input type="text" size="40" value="{CONTENT.cus_email}" id="email" name="email" class="form-control w200" /></td>
				</tr>
			</tbody>
			<tbody >
				<tr>
					<td><input type="submit" value="{LANG.send}" class="btn btn-primary" name="save"/></td>
					<td><input type="hidden" size="40" value="{CONTENT.qid}" id="qid" name="qid" /></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->