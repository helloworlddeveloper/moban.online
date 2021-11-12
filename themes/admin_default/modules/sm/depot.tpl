<!-- BEGIN: main -->
<!-- BEGIN: data -->
<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<td><strong>{LANG.depot_title}</strong></td>
			<td><strong>{LANG.address}</strong></td>
			<td><strong>{LANG.mobile}</strong></td>
			<td><strong>{LANG.admin_depot}</strong></td>
			<td width="120px" class="text-center"><strong>{LANG.function}</strong></td>
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN: row -->
		<tr>
			<td>{DATA.title}</td>
			<td>{DATA.address}</td>
			<td>{DATA.mobile}</td>
			<td>{DATA.fullname}</td>
			<td class="text-center"><i class="fa fa-edit">&nbsp;</i><a href="{link_edit}" title="">{LANG.edit}</a>&nbsp</td>
		</tr>
	<!-- END: row -->
	</tbody>
</table>
<script type='text/javascript'>
	$(function() {
		$('#checkall').click(function() {
			$('input:checkbox').each(function() {
				$(this).attr('checked', 'checked');
			});
		});
		$('#uncheckall').click(function() {
			$('input:checkbox').each(function() {
				$(this).removeAttr('checked');
			});
		});
		$('#delall').click(function() {
			if (confirm("{LANG.prounit_del_confirm}")) {
				var listall = [];
				$('input.ck:checked').each(function() {
					listall.push($(this).val());
				});
				if (listall.length < 1) {
					alert("{LANG.prounit_del_no_items}");
					return false;
				}
				$.ajax({
					type : 'POST',
					url : '{URL_DEL}',
					data : 'listall=' + listall,
					success : function(data) {
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
		$('a.delete').click(function(event) {
			event.preventDefault();
			if (confirm("{LANG.prounit_del_confirm}")) {
				var href = $(this).attr('href');
				$.ajax({
					type : 'POST',
					url : href,
					data : '',
					success : function(data) {
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
	});
</script>
<!-- END: data -->
<form class="form-inline" action="" method="post">
	<input name="savecat" type="hidden" value="1" />
	<table class="table table-striped table-bordered table-hover">
		<caption>{caption}</caption>
		<tr>
			<td align="right" width="250px"><strong>{LANG.depot_title}: </strong></td>
			<td><input class="form-control" style="width: 600px" name="title" type="text" value="{CONTENT.title}" maxlength="255" /></td>
		</tr>
		<tr>
			<td valign="top" align="right"><strong>{LANG.address}: </strong></td>
			<td><input class="form-control" style="width: 600px" name="address" type="text" value="{CONTENT.address}" maxlength="255" /></td>
		</tr>
		<tr>
			<td valign="top" align="right"><strong>{LANG.mobile}: </strong></td>
			<td><input class="form-control" style="width: 600px" name="mobile" type="text" value="{CONTENT.mobile}" maxlength="255" /></td>
		</tr>
		<tr>
			<td valign="top" align="right"><strong>{LANG.admin_depot}: </strong></td>
			<td>
				<div class="uiTokenizer uiInlineTokenizer"  style="float:left;">
					<span id="userid" class="tokenarea">
						<!-- BEGIN: admin_depot -->
						<span class="uiToken removable" title="{DATA_USER.fullname}">
							{DATA_USER.fullname}<input type="hidden" autocomplete="off" name="userid" value="{DATA_USER.userid}" />
							<a class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);" onclick="$(this).parent().remove();"></a>
						</span>
						<!-- END: admin_depot -->
					</span>
					<span class="uiTypeahead">
						<input type="hidden" class="hiddenInput" autocomplete="off" value="" />
						<div class="innerWrap" style="float:left;">
							<input id="group_cat-search" type="text" placeholder="{LANG.input_customer}" class="form-control textInput" style="width: 100%;" />
						</div>
					</span>
				</div>
			</td>
		</tr>
	</table>
	<br>
	<div class="text-center">
		<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</div>
</form>
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script>
    $("#group_cat-search").bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=userajax", {
                term : extractLast(request.term)
            }, response);
        },
        search : function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        select : function(event, data) {
            nv_add_element( data.item );
            $(this).val('');
            return false;
        }
    });
    function nv_add_element( data ){
        var html = "<span title=\"" + data.value + "\" class=\"uiToken removable\">" + data.fullname + "<input type=\"hidden\" value=\"" + data.key + "\" name=\"userid\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
        $("#userid").html( html );
        return false;
    }
    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }
</script>
<!-- END: main -->