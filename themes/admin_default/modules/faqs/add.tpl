<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div class="alert alert-danger">{error}</div>
<!-- END: error -->

<form action="{ACTION_FILE}" name="frm" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w150" />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.title} <span class="red">*</span></td>
					<td>
						<input type="text" size="100" value="{CONTENT.title}" id="title" name="title" class="form-control" />
					</td>
				</tr>
	            <tr>
	                <td>
	                    {LANG.cat} <span class="red">*</span>
	                </td>
	                <td>
	                    <select name="catid" class="form-control w300">
	                        <!-- BEGIN: catid -->
	                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.title}</option>
	                        <!-- END: catid -->
	                    </select>
	                </td>
	            </tr>
				<tr>
					<td>{LANG.ndquestion} <span class="red">*</span></td>
					<td>
					{HTMLQS}
					</td>
				</tr>
			</tbody>		
		</table>
	</div>
	<input type="submit" name="submit" value="Save" class="btn btn-primary" />
	
</form>
<!-- END: main -->