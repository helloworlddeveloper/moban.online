<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/jquery.ui.core.min.js"></script>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Chia sẻ file</h4>
      </div>
      <div class="modal-body">
        <form>
              <label for="socre-name" class="control-label">Người nhận:</label>
              <div class="form-group" style="max-height:300px;overflow: scroll;">
                <!-- BEGIN: users -->
                <label><input type="checkbox" name="recive_user" value="{USERS.userid}" id="recive_user_{USERS.userid}" />{USERS.full_name}</label><br />
                <!-- END: users -->
              </div>
            </form>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="id" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        <button type="button" onclick="nv_fileshare();" class="btn btn-primary">Chia sẻ</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function nv_fileshare(){
    var recive_user = Array();
    $("input:checkbox[name=recive_user]:checked").each(function(){
        recive_user.push($(this).val());
    });
    if( recive_user == '' ){
        alert('Bạn chưa chọn người nhận');
        return;
    }
    var id =  $("input[name=id]").val();
    $.ajax({
      url:nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}',
      type: "POST",
      data:'sharing=1&id=' + id + '&recive_user='+ recive_user + '&num=' + nv_randomPassword(8),
      success: function( data ){
        if (data == 'OK') {
           alert('Chia sẻ file thành công!');
           $('.modal-backdrop').hide();
           $('#exampleModal').hide();
        }else{
            alert(data);
        }
      }
    })  
}

$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('.modal-body input[name=recive_user]').prop('checked', false);
  $.ajax({
      url:nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}',
      type: "POST",
      data:'checkshare=1&id=' + button.data('id') + '&num=' + nv_randomPassword(8),
      dataType:"json",
      success: function( data ){
        if (data != '') {
          $.each( data, function( key, val ) {
            modal.find('#recive_user_' + val).prop('checked', true);
          });
        }
      }
  });
  modal.find('.modal-title').text('Chia sẻ file' + recipient)
  modal.find('.modal-footer input[name=id]').val(button.data('id'))
})
</script>
<h1>File bạn đã tải lên!</h1>
<!-- BEGIN: items -->
<div class="panel panel-default">
	<div class="panel-body">
		<!-- BEGIN: loop -->
			<div>
				<h3><a href="{ITEM.download_link}">{ITEM.title}</a></h3>
				<ul class="list-inline">
					<li><em class="fa fa-download">&nbsp;</em> {LANG.download_hits}: {ITEM.download_hits}</li>	
                    <li><em class="fa fa-file-archive-o">&nbsp;</em> {LANG.filesize}: {ITEM.filesize}</li>
				</ul>
			</div>
			<p class="text-right">
				<a data-toggle="modal" data-target="#exampleModal" data-whatever="{ITEM.title}" data-id="{ITEM.id}" href="javascript:void(0);">{LANG.share}</a> &divide; <a href="{ITEM.edit_link}">{GLANG.edit}</a> &divide; <a href="" onclick="nv_del_row(this,{ITEM.id});return false;">{GLANG.delete}</a>
			</p>
		<!-- END: loop -->
	</div>
</div>
<!-- END: items -->
<!-- END: main -->