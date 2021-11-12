<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" />
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.code_voucher}</th>
                    <th>{LANG.timeuse}</th>
					<th>{LANG.userid}</th>
					<th>{LANG.status}</th>
					<th>{LANG.buyhistoryid}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>{VIEW.code}</td>
                    <td> {VIEW.timeuse} </td>
					<td> {VIEW.userid} </td>
					<td> {VIEW.status} </td>
					<td>{VIEW.buyhistoryid}</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
        <div class="text-center"><a class="btn btn-primary" href="{addnew_voucher}">{LANG.addnew_voucher}</a></div>
	</div>
</form>
<!-- END: main -->