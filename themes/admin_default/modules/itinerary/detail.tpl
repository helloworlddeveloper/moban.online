<!-- BEGIN: main -->
<div class="panel panel-default">
	<div class="panel-body">
		<input type="hidden" name="id" value="{ROW.id}" />
		<div class="form-group">
			<div class="col-sm-24 col-md-12">
				<label class="control-label">{LANG.title_itinerary}: </label> <strong>{ITI.title_itinerary}</strong>
			</div>
			<div class="col-sm-24 col-md-12">
				<label class="control-label"><strong>{LANG.vehicle}: </strong> </label> {VHC.car_number_plate}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-19 col-md-12">
				<div class="row">
					<label class="col-sm-5 col-md-24 control-label text_left"><strong>{LANG.time_start}: </strong> </label>
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="input-group">{ITI.time_start}</div>
					</div>
				</div>
			</div>
			<div class="col-sm-19 col-md-12">
				<div class="row">
					<label class="col-sm-5 col-md-24 control-label text_left"><strong>{LANG.time_end}: </strong> </label>
					<div class="col-sm-12 col-md-12">
						<div class="input-group">{ITI.time_end}</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-24 col-md-12">
				<label class="control-label"><strong>{LANG.localtion_start}: </strong> </label> {LCLST.localtion_start}
			</div>
			<div class="col-sm-24 col-md-12">
				<label class="control-label"><strong>{LANG.localtion_end}: </strong> </label> {LCLEND.localtion_end}
			</div>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-body class_info_price">
		<div class="form-group">
			<div class="col-sm-12 col-md-12 text-right">
				<label class="control-label"><strong>{LANG.customer_price}: </strong> </label>
			</div>
			<div class="col-sm-12 col-md-12">
				<label class="control-label price_itinerary">{customer_price}</label>
			</div>
			<div class="col-sm-12 col-md-12 text-right">
				<label class="control-label"><strong>{LANG.ship_price}: </strong> </label>
			</div>
			<div class="col-sm-12 col-md-12">
				<label class="control-label price_itinerary">{ship_price}</label>
			</div>
			<div class="col-sm-12 col-md-12 text-right">
				<label class="control-label"><strong>{LANG.cost_price}: </strong> </label>
			</div>
			<div class="col-sm-12 col-md-12">
				<label class="control-label price_itinerary">{cost_price}</label>
			</div>
			<div class="col-sm-12 col-md-12 text-right">
				<label class="control-label"><strong>{LANG.loinhuan}: </strong> </label>
			</div>
			<div class="col-sm-12 col-md-12">
				<label class="control-label price_itinerary">{loinhuan}</label>
			</div>
		</div>
	</div>
</div>
<div class="col-md-24">
	<div class="panel with-nav-tabs panel-default">
		<div class="panel-heading">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_customer" data-toggle="tab">Hành khách</a></li>
				<li><a href="#tab_commodity" data-toggle="tab">Hàng hóa</a></li>
				<li><a href="#tab_cost" data-toggle="tab">Hành trình</a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="tab-content">
				<div class="tab-pane fade in active" id="tab_customer">
					<!-- BEGIN: customer -->
					<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th class="w50 text-center">{LANG.number}</th>
									<th>{LANG.fullname}</th>
									<th>{LANG.mobile}</th>
									<th>{LANG.localtion_customer_start}</th>
									<th>{LANG.localtion_customer_end}</th>
									<th>{LANG.qty_customer}</th>
									<th>{LANG.price_ticket}</th>
									<th></th>
								</tr>
								</thead>
								<tbody>
								<!-- BEGIN: loop -->
								<tr>
									<td class="text-center">{ITIVIEWID.stt}</td>
									<td>{ITIVIEWID.fullname}</td>
									<td>{ITIVIEWID.mobile}</td>
									<td>{ITIVIEWID.localtion_customer_start}</td>
									<td>{ITIVIEWID.localtion_customer_end}</td>
									<td>{ITIVIEWID.qty_customer}</td>
									<td>{ITIVIEWID.price_ticket}</td>
									<td class="text-center"><a href="{ITIVIEWID.link_edit}#tab_customer" data-toggle="tooltip" data-original-title="{LANG.edit}"><i class="fa fa-edit fa-lg">&nbsp;</i></a> - <a href="{ITIVIEWID.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);" data-toggle="tooltip" data-original-title="{LANG.delete}"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a></td>
								</tr>
								<!-- END: loop -->
								</tbody>
							</table>
						</div>
					</form>
					<!-- END: customer -->
					<!-- BEGIN: error -->
					<div class="alert alert-warning">{ERROR}</div>
					<!-- END: error -->
					<form class="form-horizontal" action="#" method="post">
						<div class="panel panel-default">
							<div class="panel-body">
								<input type="hidden" name="id" value="{CUSTOMER.id}" />
								<div class="form-group">
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.fullname}</strong></label> <input class="form-control" type="text" name="fullname" value="{CUSTOMER.fullname}"/>
									</div>
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.mobile}</strong></label> <input class="form-control" type="text" name="mobile" value="{CUSTOMER.mobile}"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.localtion_customer_start}</strong></label>
										<input class="form-control" type="text" name="localtion_customer_start" value="{CUSTOMER.localtion_customer_start}"/>
									</div>
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.localtion_customer_end}</strong></label>
										<input class="form-control" type="text" name="localtion_customer_end" value="{CUSTOMER.localtion_customer_end}"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.qty_customer}</strong></label> <input class="form-control" type="text" name="qty_customer" value="{CUSTOMER.qty_customer}" pattern="^[0-9]*$" oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" />
									</div>
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.price_ticket}</strong></label> <input class="form-control" type="text" name="price_ticket" value="{CUSTOMER.price_ticket}" />
									</div>
								</div>
								<div class="form-group text-center">
									<input class="btn btn-primary" name="submit_customer" type="submit" value="{LANG.save}" />
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="tab-pane fade" id="tab_commodity">
					<form class="form-horizontal" action="#" method="post">
						<input type="hidden" name="id" value="{COMMODITY_EDIT.id}" />
						<div class="panel panel-default">
							<!-- BEGIN: commodity -->
							<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<tr>
											<th class="w50 text-center">{LANG.number}</th>
											<th>{LANG.commodity_name}</th>
											<th>{LANG.sender_name}</th>
											<th>{LANG.sender_mobile}</th>
											<th>{LANG.receiver_name}</th>
											<th>{LANG.receiver_mobile}</th>
											<th>{LANG.localtion_cargo_start}</th>
											<th>{LANG.localtion_cargo_end}</th>
											<th>{LANG.qty_cargo}</th>
											<th>{LANG.price_ship}</th>
										</tr>
										</thead>
										<tbody>
										<!-- BEGIN: loop -->
										<tr>
											<td class="text-center">{COMMODITY.stt}</td>
											<td>{COMMODITY.commodity_name}</td>
											<td>{COMMODITY.sender_name}</td>
											<td>{COMMODITY.sender_mobile}</td>
											<td>{COMMODITY.receiver_name}</td>
											<td>{COMMODITY.receiver_mobile}</td>
											<td>{COMMODITY.localtion_start}</td>
											<td>{COMMODITY.localtion_end}</td>
											<td>{COMMODITY.qty}</td>
											<td>{COMMODITY.price_ship}</td>
											<td class="text-center"><a href="{COMMODITY.link_edit}#tab_commodity" data-toggle="tooltip" data-original-title="{LANG.edit}"><i class="fa fa-edit fa-lg">&nbsp;</i></a> - <a href="{COMMODITY.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);" data-toggle="tooltip" data-original-title="{LANG.delete}"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a></td>
										</tr>
										<!-- END: loop -->
										</tbody>
									</table>
								</div>
							</form>
							<!-- END: commodity -->
							<div class="panel-body">
								<div class="form-group">
									<div class="form-group">
										<div class="col-sm-24 col-md-24">
											<label class="control-label"><strong>{LANG.commodity_name}</strong></label> <input class="form-control" type="text" name="commodity_name" value="{COMMODITY_EDIT.commodity_name}"/>
										</div>
										<div class="col-sm-24 col-md-12">
											<label class="control-label"><strong>{LANG.sender_name}</strong></label> <input class="form-control" type="text" name="sender_name" value="{COMMODITY_EDIT.sender_name}"/>
										</div>
										<div class="col-sm-24 col-md-12">
											<label class="control-label"><strong>{LANG.sender_mobile}</strong></label> <input class="form-control" type="text" name="sender_mobile" value="{COMMODITY_EDIT.sender_mobile}"/>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-24 col-md-12">
											<label class="control-label"><strong>{LANG.receiver_name}</strong></label> <input class="form-control" type="text" name="receiver_name" value="{COMMODITY_EDIT.receiver_name}"/>
										</div>
										<div class="col-sm-24 col-md-12">
											<label class="control-label"><strong>{LANG.receiver_mobile}</strong></label> <input class="form-control" type="text" name="receiver_mobile" value="{COMMODITY_EDIT.receiver_mobile}"/>
										</div>
									</div>
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.localtion_cargo_start}</strong></label>
										<input class="form-control" type="text" name="localtion_start" value="{COMMODITY_EDIT.localtion_start}"/>
									</div>
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.localtion_cargo_end}</strong></label>
										<input class="form-control" type="text" name="localtion_end" value="{COMMODITY_EDIT.localtion_end}"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.qty_cargo}</strong></label> <input class="form-control" type="text" name="qty" value="{COMMODITY_EDIT.qty}" pattern="^[0-9]*$" oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" />
									</div>
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.price_ship}</strong></label> <input class="form-control" type="text" name="price_ship" value="{COMMODITY_EDIT.price_ship}" />
									</div>
								</div>
								<div class="form-group text-center">
									<input class="btn btn-primary" name="submit_commodity" type="submit" value="{LANG.save}" />
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="tab-pane fade" id="tab_cost">
					<form class="form-horizontal" action="#" method="post">
						<input type="hidden" name="id" value="{COST_EDIT.id}" />
						<div class="panel panel-default">
							<!-- BEGIN: cost -->
							<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<thead>
										<tr>
											<th class="w50 text-center">{LANG.number}</th>
											<th>{LANG.costs_title}</th>
											<th>{LANG.localtion_cost}</th>
											<th>{LANG.note}</th>
											<th>{LANG.costs_itinerary}</th>
											<th></th>
										</tr>
										</thead>
										<tbody>
										<!-- BEGIN: loop -->
										<tr>
											<td class="text-center">{COST.stt}</td>
											<td>{COST.cost_name}</td>
											<td>{COST.localtion_cost}</td>
											<td>{COST.note}</td>
											<td>{COST.price}</td>
											<td class="text-center"><a href="{COST.link_edit}#tab_cost" data-toggle="tooltip" data-original-title="{LANG.edit}"><i class="fa fa-edit fa-lg">&nbsp;</i></a> - <a href="{COST.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);" data-toggle="tooltip" data-original-title="{LANG.delete}"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a></td>
										</tr>
										<!-- END: loop -->
										</tbody>
									</table>
								</div>
							</form>
							<!-- END: cost -->
							<div class="panel-body">
								<div class="form-group">
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.costs_title}</strong></label> <input class="form-control" type="text" name="costs_title" value="{COST_EDIT.cost_name}" />
									</div>
									<div class="col-sm-24 col-md-12">
										<label class="control-label"><strong>{LANG.costs_itinerary}</strong></label> <input class="form-control" type="text" name="price" value="{COST_EDIT.price}" />
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-24 col-md-24">
										<label class="control-label"><strong>{LANG.localtion_cost}</strong></label>
										<input class="form-control" type="text" name="localtion_cost" value="{COST_EDIT.localtion_end}"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-24 col-md-24">
										<label class="control-label"><strong>{LANG.note}</strong></label>
										<textarea class="form-control" style="height: 100px;" cols="75" rows="5" name="note">{COST_EDIT.note}</textarea>
									</div>
								</div>
								<div class="form-group text-center">
									<input class="btn btn-primary" name="submit_cost" type="submit" value="{LANG.save}" />
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	//<![CDATA[
    $(function(){
        var hash = window.location.hash;
        hash && $('ul.nav a[href="' + hash + '"]').tab('show');

        $('.nav-tabs a').click(function (e) {
            $(this).tab('show');
            var scrollmem = $('body').scrollTop();
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
        });
    });
	function nv_add_ctm(id) {
		var new_status = $('#change_status_' + id).is(':checked') ? true
				: false;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name
					+ '&' + nv_fc_variable + '=manage_vehicle&nocache='
					+ new Date().getTime(), 'change_status=1&id=' + id,
					function(res) {
						var r_split = res.split('_');
						if (r_split[0] != 'OK') {
							alert(nv_is_change_act_confirm[2]);
						}
					});
		} else {
			$('#change_status_' + id)
					.prop('checked', new_status ? false : true);
		}
		return;
	}
	//]]>
</script>
<!-- END: main -->