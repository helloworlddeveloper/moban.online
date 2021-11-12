<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form id="edit_topic" class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="id" value="{ROW.id}" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
            <tr>
                <td> {LANG.subjectid} </td>
                <td>
                    <select class="form-control" name="subjectid">
                        <option value="0"> --- </option>
                        <!-- BEGIN: subject -->
                        <option value="{SUBJECT.id}" {SUBJECT.selected}>{SUBJECT.title}</option>
                        <!-- END: subject -->
                    </select>
                </td>
            </tr>
            <tr>
                <td> {LANG.classid} </td>
                <td>
                    <select class="form-control" name="classid">
                        <option value="0"> --- </option>
                        <!-- BEGIN: class -->
                        <option value="{CLASS.id}" {CLASS.selected}>{CLASS.title}</option>
                        <!-- END: class -->
                    </select>
                </td>
            </tr>
            <tr>
                <td> {LANG.topic_name} </td>
                <td><input class="form-control" type="text" name="topic_name" value="{ROW.topic_name}" /></td>
            </tr>
            <tr>
                <td> {LANG.topic_alias} </td>
                <td><input class="form-control" type="text" name="topic_alias" value="{ROW.topic_alias}" id="id_topic_alias" />&nbsp;<i class="fa fa-refresh fa-lg icon-pointer" onclick="nv_get_alias('id_topic_alias');">&nbsp;</i></td>
            </tr>
            <tr>
                <td> {LANG.topic_des} </td>
                <td><textarea class="form-control" style="width: 98%; height:100px;" cols="75" rows="5" name="topic_des">{ROW.topic_des}</textarea></td>
            </tr>
            <tr>
                <td> {LANG.topic_img} </td>
                <td><input class="form-control" type="text" name="topic_img" value="{ROW.topic_img}" id="id_topic_img" />&nbsp;<button type="button" class="btn btn-info" id="img_topic_img"><i class="fa fa-folder-open-o">&nbsp;</i> Browse server </button></td>
            </tr>
            <tr>
                <td> {LANG.startweek} </td>
                <td><input class="form-control" type="text" name="startweek" value="{ROW.startweek}" pattern="^[0-9]*$"  oninvalid="setCustomValidity( nv_digits )" oninput="setCustomValidity('')" /></td>
            </tr>
            <tr>
                <td> {LANG.circle} </td>
                <td>
                    <!-- BEGIN: checkbox_circle -->
                    <input class="form-control" type="checkbox" name="circle" value="{OPTION.key}" {OPTION.checked}>{OPTION.title} &nbsp;
                    <!-- END: checkbox_circle -->
                </td>
            </tr>
            <tr>
                <td> {LANG.status} </td>
                <td>
                    <select class="form-control" name="status">
                        <!-- BEGIN: select_status -->
                        <option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
                        <!-- END: select_status -->
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>

<script type="text/javascript">
    //<![CDATA[
    function nv_get_alias(id) {
        var title = strip_tags( $("[name='topic_name']").val() );
        if (title != '') {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
                $("#"+id).val( strip_tags( res ) );
            });
        }
        return false;
    }
    $("#img_topic_img").click(function() {
        var area = "id_topic_img";
        var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
        var currentpath = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
        var type = "image";
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

    //]]>
</script>

<!-- BEGIN: auto_get_alias -->
<script type="text/javascript">
    //<![CDATA[
    $("[name='topic_name']").change(function() {
        nv_get_alias('id_topic_alias');
    });
    //]]>
</script>
<!-- END: auto_get_alias -->
<!-- END: main -->