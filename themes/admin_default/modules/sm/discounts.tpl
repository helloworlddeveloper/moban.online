<!-- BEGIN: main -->
<!-- BEGIN: view -->
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />

		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<select class="form-control" name="productid" id="productid">
						<option value="0"> -- {LANG.discount_product} -- </option>
						<!-- BEGIN: sl_productid -->
						<option value="{PRODUCT.id}" {PRODUCT.sl} >{PRODUCT.title}</option>
						<!-- END: sl_productid -->
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="{LANG.search}" />&nbsp;
				</div>
			</div>
		</div>
		<input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
		<label><em>{LANG.search_note}</em></label>
	</form>
</div>
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.discount_product}</th>
					<th>{LANG.discount_from}</th>
					<th>{LANG.discount_to}</th>
					<th>{LANG.discount_number}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td colspan="6" class="text-center">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td> {VIEW.product} </td>
					<td> {VIEW.begin_quantity} </td>
					<td> {VIEW.end_quantity} </td>
					<td> {VIEW.percent} </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>{CAPTION}</caption>
			<thead>
				<tr>
					<th> {LANG.discount_product} </th>
					<th> {LANG.discount_from} </th>
					<th> {LANG.discount_to} </th>
					<th> {LANG.discount_number} </th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<select class="form-control" name="productid">
							<option value="0">--{LANG.select_product}--</option>
							<!-- BEGIN: productid -->
							<option value="{PRODUCT.id}"{PRODUCT.sl}>{PRODUCT.title}</option>
							<!-- END: productid -->
						</select>
					</td>
					<td><input class="form-control" type="text" name="begin_quantity" value="{ROW.begin_quantity}" /></td>
					<td><input class="form-control" type="text" name="end_quantity" value="{ROW.end_quantity}"/></td>
					<td>
						<input class="form-control" type="text" name="percent" value="{ROW.percent}" />&nbsp;%
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</div>
</form>
<!-- END: main -->