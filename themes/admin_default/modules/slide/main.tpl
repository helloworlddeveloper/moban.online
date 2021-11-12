<!-- BEGIN: main -->
<div id="list_mods">
	<form class="form-inline" name="listlink" method="post" action="{FORM_ACTION}">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<caption>{LANG.album_link_recent}</caption>
				<thead>
					<tr>
						<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
						<th class="text-center">{LANG.album_add_title}</th>
						<th class="text-center">{LANG.image}</th>
						<th class="text-center">{LANG.album_inhome}</th>
						<th class="text-center">{LANG.album_method}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="6">
							<input type="button" class="btn btn-primary" onclick="nv_del_select_rows(this.form, '{LANG.msgnocheck}')" value="{LANG.album_method_del}" /> 
						</td>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN: loop -->
					<tr>
						<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"></td>
						<td>{ROW.title}</td>
						<td><a onclick="show('{ROW.urlimg}');" href="#" data-src="{ROW.urlimg}" class="open_modal_image">
						<img src="{ROW.urlimg}" alt="{ROW.title}" style="height:50px;" > </a>
						</td>
						<td class="text-center">{ROW.status}</td>
						<td class="text-center">
							<em class="fa fa-edit fa-lg">&nbsp;</em>
							<a class="edit_icon" href="{ROW.url_edit}">{LANG.album_method_edit}</a>&nbsp;-&nbsp;
							<em class="fa fa-trash-o fa-lg">&nbsp;</em>
							<a href="javascript:void(0);" onclick="nv_del_rows('{ROW.id}')" >{LANG.album_method_del}</a>
						</td>
					</tr>
					<!-- END: loop -->
				</tbody>
			</table>
		</div>
	</form>
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{LANG.file_name}</h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
function show(url_image="") {
    $('#imagemodal .modal-body').html('<div class="text-center"><img class="img-preview" src="' + url_image + '"></div>');
    $('#imagemodal').modal('show');
};
</script>
<!-- END: main -->
