<!-- BEGIN: main -->
<script src="/themes/default/js/bootstrap.min.js"></script>
  <div class="modal fade" id="notificationModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
      </div>
    </div>
  </div>
<style type="text/css">
button.close {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: 0 none;
    cursor: pointer;
    padding: 0;
}
.close {
    color: #000;
    float: right;
    font-size: 21px;
    font-weight: 700;
    line-height: 1;
    opacity: 0.2;
}
#notificationModal .modal-header{ color:#000}
#notificationModal .modal-body{ color:#000;font-size:16px}
#notificationModal .modal-body a{color:#1a3f5e}
</style>
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/bootstrap-notify.js"></script>	
<script type="text/javascript">

function titleScroller(text) {
    document.title = text;
    setTimeout(function () {
        titleScroller(text.substr(1) + text.substr(0, 1));
    }, 500);
}
$(document).ready(function(){
  setInterval(function(){
    load_notification()
  },{CONFIG.timeout}); });
function load_notification(){
    if (!localStorage.notification)
    {
        localStorage.notification = 0;    
    }
	var notification = localStorage.notification;	
    $.ajax({
      url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "={module}&" + nv_fc_variable + '=main',
      type: 'GET',
      contentType: "application/json",
      dataType: 'json',
      data: {getnotification: 1, notification: notification, checkallow: '{checkallow}'},
      success: function(data) {
    	if(data.statusok == 1 ){
    	   if(data.popup == 1 ){
                $("#notificationModal").find(".modal-title").html(data.author);
                $("#notificationModal").find(".modal-body").html('<a target="_blank" href="' + data.url +'">' + data.message + '</a>');
                var scrl = data.message + '-     ';
                titleScroller(scrl)
                $('#notificationModal').modal('show');
    	   }
    	   if( data.iconimage==2){
    	       $.notify({
            		icon: data.icon,
            		message: data.author + ' ' + data.message,
            		url: data.url
            	});
    	   }else if( data.iconimage==1){
    	       $.notify({
            		icon: data.icon,
            		message: data.author + ' ' + data.message,
            		url: data.url
            	},{
            	   icon_type: 'image'
            	});
    	   }else{
    	       $.notify({
            		message: data.author + ' ' + data.message,
            		url: data.url
            	});
    	   }
           if( data.id > 0 ){
                localStorage.notification = data.id
           }
    	}
      },
      error: function(e) {
    	console.log(e.message);
      }
    });
}
</script>
<!-- END: main -->