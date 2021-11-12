<!-- BEGIN: main -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel"></h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="note-name" class="control-label">{LANG.ghichu}:</label>
            <textarea class="form-control" id="note-name" name="note_name"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="userid" />
        <input type="hidden" name="id" />
          <input type="hidden" name="datetime-key" />
        <button type="button" class="btn btn-default" data-dismiss="modal">{LANG.close}</button>
        <button type="button" onclick="save_nhanxet();" class="btn btn-primary">{LANG.save}</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var modal = $(this);
  modal.find('.modal-body textarea').val(button.data('note'))
  
  modal.find('.modal-title').text('{LANG.ghichu} {LANG.for} ' + recipient)
  modal.find('.modal-body textarea').attr('placeholder', '{LANG.ghichu} {LANG.for} ' + recipient + ' {LANG.datetime_check} ' + button.data('date'))
  modal.find('.modal-footer input[name=userid]').val(button.data('userid'))
  modal.find('.modal-footer input[name=id]').val(button.data('id'))
    modal.find('.modal-footer input[name=datetime-key]').val(button.data('datetime-key'))

})
</script>
<!-- BEGIN: view -->
<div style="display: none;" id="ajax_load"><div class="loading" id="set_status"></div></div>
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/bootstrap-select.min.css" rel="stylesheet" />
<form action="{BASE_URL_SITE}" name="fsea" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    <div class="row">
        <div class="col-sm-8 col-md-8">
            <strong>{LANG.chossen_teacher}</strong>
            <select name="teacher[]" class="selectpicker" multiple>
              <!-- BEGIN: teacher -->
              <option{TEACHER.sl} value="{TEACHER.userid}">{TEACHER.full_name}</option>
              <!-- END: teacher -->
            </select>
        </div>
        <div class="col-sm-8 col-md-8">
            <strong>{LANG.chossen_type}</strong>
            <select name="type[]" class="selectpicker" multiple >
                <!-- BEGIN: type -->
                <option{TYPE.sl} value="{TYPE.key}">{TYPE.title}</option>
                <!-- END: type -->
            </select>
        </div>
        <div class="col-sm-8 col-md-8">
            <strong>{LANG.chossen_status}</strong>
            <select name="status[]" class="selectpicker" multiple >
                <!-- BEGIN: status -->
                <option{STATUS.sl} value="{STATUS.key}">{STATUS.title}</option>
                <!-- END: status -->
            </select>
        </div>
        <div class="col-sm-24 col-md-24">
            <div class="form-inline">
                {LANG.from}&nbsp;<input class="form-control" name="starttime" id="starttime" value="{DATA_SEARCH.starttime}" placeholder="dd/mm/yyyy" style="width: 100px;" type="text" />&nbsp;
                {LANG.to}&nbsp;<input class="form-control" name="endtime" id="endtime" value="{DATA_SEARCH.endtime}" placeholder="dd/mm/yyyy" style="width: 100px;" type="text" />
                &nbsp;<input type="submit" name="submit" class="btn btn-primary" value="{LANG.search_data}" />
                <!-- BEGIN: import_ngaycong -->
                &nbsp;<a class="btn btn-primary" href="{import_ngaycong}">{LANG.import_ngaycong}</a>
                <!-- END: import_ngaycong -->
                &nbsp;<a class="btn btn-primary" href="{tonghop_ngaycong}">{LANG.tonghop_ngaycong}</a>
            </div>
        </div>
    </div>
    <script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(function($){
           $("#starttime,#endtime").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
        });
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</form>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-bordered table-hover grid-edit">
            <thead>
            <tr>
                <th>{LANG.teacher_name}</th>
                <th>{LANG.teacher_code}</th>
                <th>{LANG.checkin}</th>
                <th>{LANG.checkout}</th>
                <th>{LANG.tinhcagio}</th>
                <th>{LANG.datetime_check}</th>
                <th>{LANG.chamcong_status_1}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr valign="middle" class="bg_dimuon_{VIEW.dimuon} bg_vesom_{VIEW.vesom}">
                <td rowspan="{VIEW.total_row}">
                    &nbsp;<a data-toggle="tooltip" data-original-title="{LANG.checkin}: {VIEW.infocheck.giovao_1} - {LANG.checkout}: {VIEW.infocheck.giora_1}">{VIEW.teacher.last_name} {VIEW.teacher.first_name}</a>
                    <!-- BEGIN: note --> 
                    &nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{VIEW.note}">&nbsp;</em>
                    <!-- END: note -->
                </td>
                <td rowspan="{VIEW.total_row}">{VIEW.teacher.code}</td>
                <!-- BEGIN: check_info1 -->
                <td>
                    <!-- BEGIN: dimuon -->
                    <label title="{LANG.cophep}"><input type="checkbox" value="1" name="cophepdimuon"{VIEW.dimuoncophep} data_id="{VIEW.id}" data_userid="{VIEW.teacherid}" /></label>
                    <!-- END: dimuon -->
                    {CHECK_INFO_IN}
                </td>
                <td>
                    <!-- BEGIN: vesom -->
                    <label title="{LANG.cophep}"><input type="checkbox" value="1" name="cophepvesom"{VIEW.vesomcophep} data_id="{VIEW.id}" data_userid="{VIEW.teacherid}" /></label>
                    <!-- END: vesom -->
                    {CHECK_INFO_OUT}
                </td>
                <!-- END: check_info1 -->
                <td rowspan="{VIEW.total_row}">
                    <!-- BEGIN: notallow -->{VIEW.ngaycong}<!-- END: notallow -->
                    <!-- BEGIN: allow -->
                    <input type="text" maxlength="3" value="{VIEW.ngaycong}" data_id="{VIEW.id}" data-datetime-key="{VIEW.datetime_key}" data_userid="{VIEW.teacherid}" name="ngaycong" data_old="{VIEW.ngaycong}" data_userid="{VIEW.teacherid}" />
                    <!-- END: allow --> 
                </td>
                <td rowspan="{VIEW.total_row}"> {VIEW.datetime} </td>
                <td rowspan="{VIEW.total_row}" class="bg_status_{VIEW.status}">
                    <!-- BEGIN: noallow_status -->
                    {VIEW.status_text}
                    <!-- END: noallow_status -->
                    <!-- BEGIN: allow_status -->
                    <label><input type="checkbox" value="1" name="changstatus"{VIEW.status_checked} data_id="{VIEW.id}" data_userid="{VIEW.teacherid}" /></label>
                    <!-- END: allow_status --> 
                </td>
                <td rowspan="{VIEW.total_row}" class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a data-toggle="modal" data-target="#exampleModal" data-whatever="{VIEW.teacher.last_name} {VIEW.teacher.first_name}" data-userid="{VIEW.teacherid}" data-datetime-key="{VIEW.datetime_key}" data-id="{VIEW.id}" data-date="{VIEW.datetime}" data-note="{VIEW.note}" href="javascript:void(0);">{LANG.ghichu}</a></td>
            </tr>
            <!-- BEGIN: check_info2 -->
            <tr valign="middle" class="bg_dimuon_{VIEW.dimuon} bg_vesom_{VIEW.vesom}">
                <td>
                    {CHECK_INFO_IN}
                    <!-- BEGIN: dimuon -->
                    <label><input type="checkbox" value="1" name="cophepdimuon"{VIEW.dimuoncophep} data_id="{VIEW.id}" data_userid="{VIEW.teacherid}" />{LANG.cophep}</label>
                    <!-- END: dimuon -->
                </td>
                <td>
                    {CHECK_INFO_OUT}
                    <!-- BEGIN: vesom -->
                    <label><input type="checkbox" value="1" name="cophepvesom"{VIEW.vesomcophep} data_id="{VIEW.id}" data_userid="{VIEW.teacherid}" />{LANG.cophep}</label>
                    <!-- END: vesom -->
                </td>
            </tr>
            <!-- END: check_info2 -->
            <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: view -->
<!-- BEGIN: addnew -->
<div class="panel-heading">
    <!-- BEGIN: importexcel -->
    <div class="alert alert-warning">{LANG.importexcel}</div>
    <!-- END: importexcel -->
    <!-- BEGIN: error -->
    <div class="alert alert-warning">{ERROR}</div>
    <!-- END: error -->
    <div class="">
        <form enctype="multipart/form-data" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&import=1" method="post">
            <div class="col-sm-18 col-md-18">
                <div class="input-group">
                    <input type="text" class="form-control" id="file_name" disabled>
                    <span class="input-group-btn">
                        <button class="btn btn-default" onclick="$('#upload_fileupload').click();" type="button">
                            <em class="fa fa-folder-open-o fa-fix">&nbsp;</em> {LANG.file_selectfile}
                        </button>
                    </span>
                </div>
                <em class="help-block">{LANG.upload_valid_ext_info}: {EXT_ALLOWED}</em>
                <input type="file" name="upload_fileupload" id="upload_fileupload" style="display: none" />
            </div>
            <div class="col-sm-6 col-md-6"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.read_file_excel}" /></div>
        </form>
    </div>
    <div class="clear">&nbsp;</div>
</div>

<!-- END: addnew -->
<!-- END: main -->