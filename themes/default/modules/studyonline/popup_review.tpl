
<script src="{NV_BASE_SITEURL}themes/default/js/bootstrap.min.js?t=28"></script>
<div class="modal fade" id="popupModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{LANG.review_of_student_title}</h4>
            </div>
            <div class="modal-body">
                <p>{LANG.review_of_student_note}</p>
                <div class="form-group">
                    <textarea class="form-control" style="width: 100%;" name="revirecontent"></textarea>
                    <input name="khoahocid" type="hidden" value="{DETAIL.id}">
                    <input name="checkress" type="hidden" value="{DETAIL.checkress}">
                </div>
                <div class="form-group"><input type="button" class="btn btn-primary" name="sendreview" value="{LANG.sendreview}"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function () {
            $('#popupModal').modal('show');
        },5000); });
</script>