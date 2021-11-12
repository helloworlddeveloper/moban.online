<!-- BEGIN: main -->
<div id="users">
	<form action="{FORM_ACTION}" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col style="width: 260px" />
					<col/>
				</colgroup>
				<tfoot>
					<tr>
						<td colspan="2"><input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td>{LANG.allow_accept}</td>
						<td><input type="checkbox" value="1" name="allow_accept"{allow_accept}/></td>
					</tr>
					<tr>
						<td>{LANG.domain_accept}</td>
						<td><input class="form-control w200" name="domain_accept" value="{DATA.domain_accept}" /></td>
					</tr>
                    <tr>
						<td>{LANG.apikey}</td>
						<td>
                            <input class="form-control w200" name="apikey" value="{DATA.apikey}" />
                            <a href="javascript:void(0);" onclick="return nv_genpass();" class="btn btn-primary btn-xs">{LANG.genpass}</a>
                        </td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>
<!-- END: main -->