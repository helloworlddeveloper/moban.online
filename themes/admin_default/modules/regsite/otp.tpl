<!-- BEGIN: main -->

<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
				<th class="text-center">stt</th>
				<th class="text-center">SĐT</th>
				<th class="text-center">Ngày</th>
				<th class="text-center">Mã OPT</th>
				<th>Trạng thái</th>
			</tr>
			</thead>
			<tbody>
			<!-- BEGIN: loop -->
			<tr>
    			<td>{ROW.stt}</td>
    			<td class="text-left">{ROW.mobile}</td>
    			<td>{ROW.addtime}</td>
    			<td>{ROW.code}</td>
    			<td class="text-center">
    				{ROW.status}
    			</td>
			</tr>
			<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->