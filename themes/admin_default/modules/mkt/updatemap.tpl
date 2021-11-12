<!-- BEGIN: main -->
<div class="table-responsive form-inline">
	<table id="table_field_read" class="table table-striped table-bordered table-hover">
        <tr class="footer">
            <td colspan="8">
                <input type="hidden" value="{studentid}" name="studentid" />
                <input type="hidden" value="{address}" name="address" />
                Số mili giây nghỉ giữa các lần: <input class="form-control" type="text" name="timeout" value="2000" placeholder="Số giây nghỉ giữa các lần" />
                <span id="status_check"></span>
                <input class="btn btn-primary" name="action_send" value="Chạy tiến trình check vị trí" />
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    $('input[name=action_send]').click(function(){
        var timeout = $('input[name=timeout]').val();
        var studentid = $('input[name=studentid]').val();
        var address = $('input[name=address]').val();
        if( timeout == 0 || ! is_numeric( timeout ) ){
            alert('Bạn cần nhập thời gian nghỉ lớn hơn 0');
        }else{
            check_location_address( timeout, studentid, address );
        }
    })
    
    function check_location_address( timeout, studentid, address ){
        var gmap_lat = 0;
        var gmap_lng = 0;
        $('#status_check').html('studentid: '+ studentid +' Add: ' + address + ' &nbsp;<i style="color:#f00" class="fa fa-refresh fa-spin"></i>');
        $.ajax({
          url: "https://maps.googleapis.com/maps/api/geocode/json?address="+address+'&sensor=false&key=AIzaSyDrAxUjw5tgYSn55I946qyqH1NkFi_EuUw',
          type: "POST",
          success: function(res){
            if( res.status != 'ZERO_RESULTS'){
                var gmap_lat = res.results[0].geometry.location.lat;
                var gmap_lng = res.results[0].geometry.location.lng;
            }
            $.post(script_name + "?" + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}&nocache=' + new Date().getTime(), 'save_maps=1&gmap_lat=' + gmap_lat + '&gmap_lng=' + gmap_lng + '&studentid=' + studentid, function(res) {
                res= res.split('[NV4]');
                setTimeout(function(){
                    check_location_address( timeout, res[1], res[2] )
                }, timeout); 
				
			});
                   
          }
        }); 
        return true;
    }
</script>
<div style="margin-top:8px;">
    <a class="button1" href="{SEND_NEW_MESS}"><span><span>{LANG.new_mess}</span></span></a>
</div>
<!-- END: main -->
