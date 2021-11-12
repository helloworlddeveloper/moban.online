<!-- BEGIN: main -->
<style type="text/css">
	.form-group{
		margin-bottom: 4px
	}
</style>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form action="" id="form_tranfer" onsubmit=" return send_data_tranfer()" method="post">
	<input type="hidden" name="savetranfer" value="1">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th rowspan="2" class="text-center">{LANG.product_title}</th>
					<th class="text-center">{LANG.tranfer_from}</th>
					<th class="text-center">{LANG.tranfer_to}</th>
				</tr>
				<tr>
					<th>
						<!-- BEGIN: tranfer_from -->
						<select name="tranfer_from" onchange="nv_get_info_numproduct(this.value)" class="form-control">
							<option value="0">-- {LANG.selecttranfer_from} --</option>
							<!-- BEGIN: loop -->
							<option value="{DEPOT.id}">{DEPOT.title}</option>
							<!-- END: loop -->
						</select>
						<!-- END: tranfer_from -->
					</th>
					<th>
						<!-- BEGIN: tranfer_to -->
						<select name="tranfer_to" class="form-control">
							<option value="0">-- {LANG.selecttranfer_to} --</option>
							<!-- BEGIN: loop -->
							<option value="{DEPOT.id}">{DEPOT.title}</option>
							<!-- END: loop -->
						</select>
						<!-- END: tranfer_to -->
					</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><a href="{DATA.link}" title="{DATA.title}"><strong>{DATA.title}</strong></a></td>
					<td>
						<div class="form-group">
							<div class="text-right">
								<span class="label label-success info_product_number" id="productid_{DATA.id}" title="{LANG.content_product_number}">0</span> {DATA.product_unit}</label>
							</div>
						</div>
					</td>
					<td>
						<input type="number" autocomplete="off" data_productid="{DATA.id}" name="productid[{DATA.id}]" value="{DATA.quantity}" class="form-control productid_number" placeholder="{LANG.tranfer_quantity}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
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
	function nv_get_info_numproduct( depotid ) {
	    if( depotid > 0 ){
            $.ajax({
                type : 'POST',
                dataType: 'json',
                url : script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tranfer',
                data : 'loaddepot=1&listid={LISTIDPRODUCT}&depotid=' + depotid,
                success : function(data) {
                    if( data != ''){
                        $.each(data, function(i, item) {
                            $('#productid_' + i).html( item );
                        });
                    }else{
                        $('.info_product_number').html(0);
                    }
                }
            });
        }else{
	        $('.info_product_number').html(0);
        }
    }
    function send_data_tranfer() {
	    var submit = 0;
        var objdata = [];
		$('.productid_number').each(function () {
		    if( $(this).val() == '' ){
                $(this).focus();
                submit = 0;
		        return false;
            }else if( $(this).val() > $('#productid_' + $(this).attr('data_productid')).html() ){
                $(this).focus();
                $(this).addClass('error');
                submit = 0
                return false;
            }else{
                $(this).removeClass('error');
                submit=1;
            }
        })
		if( submit == 1 ){
            $.ajax({
                type : 'POST',
                url : script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tranfer',
                data: $('#form_tranfer').serialize(),
                success : function(data) {
                    data = data.split("_");
                    if( data[0] == 'ERROR'){
                        alert(data[1]);
                    }else{
                        window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=warehouse_logs';
                    }
                }
            });
        }
		return false;
    }
</script>
<!-- END: main -->