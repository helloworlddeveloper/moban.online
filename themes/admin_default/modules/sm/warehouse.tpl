<!-- BEGIN: main -->
<style type="text/css">
	.form-group{
		margin-bottom: 4px
	}
</style>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form action="" method="post">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="control-label"><strong>{LANG.title}</strong></label>
				<input type="text" name="title" value="{WAREHOUSE_TITLE}" class="form-control" />
			</div>
			<div class="form-group">
				<label class="control-label"><strong>{LANG.select_depot}</strong></label>
				<select class="form-control" name="depotid">
					<option value="0"> --- </option>
					<!-- BEGIN: depotid -->
					<option value="{DEPOT.id}"{DEPOT.sl}>{DEPOT.title} - {DEPOT.address}</option>
					<!-- END: depotid -->
				</select>
			</div>
			<div class="form-group">
				<label class="control-label"><strong>{LANG.note}</strong></label>
				<textarea name="note" class="form-control" rows="4">{WAREHOUSE.note}</textarea>
			</div>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="text-center">&nbsp;</th>
					<th class="text-center">{LANG.warehouse_quantity}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td colspan="2"><a href="{DATA.link}" title="{DATA.title}"><strong>{DATA.title}</strong></a></td>
				</tr>
				<tr>
					<td class="text-center">{DATA.no}</td>
					<td>
						<div class="form-group">
							<div class="text-right">
								<label class="col-sm-6 control-label"><span class="label label-success">{LANG.content_product_number}</span>&nbsp;<span class="label label-danger" title="{LANG.content_product_number}">{DATA.quantity} {DATA.product_unit}</span></label>
							</div>
							<div class="col-sm-6">
								<input type="number" autocomplete="off" name="data[{DATA.id}][quantity]" value="{DATA.quantity}" class="form-control" placeholder="{LANG.warehouse_quantity}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
							</div>
							<div class="col-sm-6">
								<input type="text" name="data[{DATA.id}][price]" class="form-control" value="{DATA.price_in}" placeholder="{LANG.warehouse_price}" onkeyup="this.value=FormatNumber(this.value);" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
							</div>
						</div>
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-primary" value="{LANG.save}" name="submit" />
	</div>
</form>
<script type="text/javascript">
	var inputnumber = '{LANG.error_inputnumber}';
</script>
<!-- END: main -->