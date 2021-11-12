<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{error}</div>
<!-- END: error -->
<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.setting_view}</caption>
			<tbody>
				<tr>
					<th>{LANG.setting_per_page}</th>
					<td>
					<select class="form-control" name="per_page">
						<!-- BEGIN: per_page -->
						<option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
						<!-- END: per_page -->
					</select></td>
				</tr>
                <tfoot>
    				<tr>
    					<td class="text-center" colspan="2">
    						<input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" />
    						<input type="hidden" value="1" name="savesetting" />
    					</td>
    				</tr>
    			</tfoot>
			</tbody>
		</table>
	</div>
   </div>
</form>

<!-- END: main -->