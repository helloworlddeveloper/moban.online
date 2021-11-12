<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-inline" action="{DATA_CONTENTS.link_submit}" method="post">
	<input type="hidden" name="rows_id" value="{DATA_CONTENTS.id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
            <caption><h1>{LANG.reg} {LANG.topic} {DATA_CONTENTS.topic.name} - {DATA_CONTENTS.teacher.name}</h1></caption>
			<tbody>
				<tr>
					<td> {LANG.reg_full_name} <span style="color:#f00;">(*)</span></td>
					<td><input class="form-control" type="text" name="reg_full_name" value="{ROW.reg_full_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td> {LANG.reg_email} </td>
					<td><input class="form-control" type="email" name="reg_email" value="{ROW.reg_email}" oninvalid="setCustomValidity( nv_email )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td> {LANG.reg_phone} <span style="color:#f00;">(*)</span></td>
					<td><input class="form-control" type="text" name="reg_phone" value="{ROW.reg_phone}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td> {LANG.reg_address} </td>
					<td><input class="form-control" type="text" name="reg_address" value="{ROW.reg_address}" /></td>
				</tr>
				<tr>
					<td> {LANG.reg_school} </td>
					<td><input class="form-control" type="text" name="reg_school" value="{ROW.reg_school}" /></td>
				</tr>
				<tr>
					<td> {LANG.reg_sex} </td>
					<td>
                        <input class="form-control" name="reg_sex" type="radio" value="M"{ROW.ck_m} id="sex_m" /><label for="sex_m">{LANG.reg_sex_M}</label>
                        <input class="form-control" name="reg_sex" type="radio" value="F"{ROW.ck_f} id="sex_f" /><label for="sex_f">{LANG.reg_sex_F}</label>
                    </td>
				</tr>
				<tr>
					<td> {LANG.reg_facebook} </td>
					<td><input class="form-control" type="text" name="reg_facebook" value="{ROW.reg_facebook}" /></td>
				</tr>
				<tr>
					<td> {LANG.note} </td>
					<td><textarea class="form-control" style="width: 98%; height:100px;" cols="75" rows="5" name="note">{ROW.note}</textarea></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.reg_action}" /></div>
</form>
<!-- BEGIN: comment -->
{CONTENT_COMMENT}
<!-- END: comment -->
<!-- END: main -->