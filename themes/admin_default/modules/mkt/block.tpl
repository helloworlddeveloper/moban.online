<!-- BEGIN: main -->
<div id="module_show_list">
	{BLOCK_LIST}
</div>
<br />
<div id="add">
	<!-- BEGIN: news -->
    <script type="text/javascript" charset="utf8" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery.dataTables.js"></script>
    
	<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post">
		<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="table-responsive">
			<table id="table_search" class="table table-striped table-bordered table-hover">
				<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.addtoblock}</caption>
				<thead>
					<tr>
						<th class="w50 text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
						<th>{LANG.name}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
						<td>
						<select class="form-control" name="bid">
							<!-- BEGIN: bid -->
							<option value="{BID.key}"{BID.selected}>{BID.title}</option>
							<!-- END: bid -->
						</select>
						<input type="hidden" name ="checkss" value="{CHECKSESS}" /><input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" /></td>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN: loop -->
					<tr>
						<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"{ROW.checked}/></td>
						<td>{ROW.title}</td>
					</tr>
					<!-- END: loop -->
				</tbody>
			</table>
		</div>
	</form>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#table_search').DataTable(
                {paging: true}
            );
        } );
    </script>
	<!-- END: news -->
</div>
<!-- END: main -->