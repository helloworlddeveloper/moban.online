<!-- BEGIN: main -->
<!-- BEGIN: data -->
<div class="table-responsive">
    <div class="text-center clearfix">
        <a class="btn btn-primary" href="{add_possiton}">{LANG.add_possiton}</a>
        <div class="clearfix">&nbsp;</div>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <colgroup>
            <col class="w100">
            <col span="1">
            <col span="2" class="w150">
        </colgroup>
        <thead>
        <tr class="text-center">
            <th>{LANG.order}</th>
            <th>{LANG.title}</th>
            <th>{LANG.salary}</th>
            <th>{LANG.salary_kpi}&nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.salary_kpi_note}">&nbsp;</em></th>
            <th>{LANG.percent_responsibility}&nbsp;<em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.percent_responsibility_note}">&nbsp;</em></th>
            <th>{LANG.istype}</th>
            <th>{LANG.status}</th>
            <th>{LANG.feature}</th>
        </tr>
        </thead>
        <tbody>
        <!-- BEGIN: row -->
        <tr>
            <td class="text-center">
                <select id="change_weight_{ROW.id}" onchange="nv_chang_weight('{ROW.id}', '{op}');" class="form-control">
                    <!-- BEGIN: weight -->
                    <option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
                    <!-- END: weight -->
                </select></td>
            <td>{ROW.title}</td>
            <td>{ROW.salary}</td>
            <td>{ROW.kpi_require}</td>
            <td>{ROW.percent_responsibility}%</td>
            <td>{ROW.istype}</td>
            <td class="text-center">
                <select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}', '{op}');" class="form-control">
                    <!-- BEGIN: status -->
                    <option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
                    <!-- END: status -->
                </select></td>
            <td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em><a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp; <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_module_del({ROW.id}, '{op}', '{ROW.checkss}');">{GLANG.delete}</a></td>
        </tr>
        <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: data -->
<!-- BEGIN: add -->
    <!-- BEGIN: error -->
    <div class="alert alert-danger">
        {ERROR}
    </div>
    <!-- END: error -->
    <form action="{FORM_ACTION}" method="post" class="confirm-reload">
        <input name="submit" type="hidden" value="1" />
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <colgroup>
                        <col class="w200" />
                        <col />
                    </colgroup>
                    <tbody>
                    <tr>
                        <td class="text-right"> {LANG.title} <sup class="required">(*)</sup></td>
                        <td><input class="w300 form-control pull-left" type="text" value="{DATA.title}" name="title" id="idtitle" maxlength="250" />&nbsp;<span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
                    </tr>
                    <tr>
                        <td class="text-right">{LANG.alias}</td>
                        <td><input class="w300 form-control pull-left" type="text" value="{DATA.alias}" name="alias" id="idalias" maxlength="250" />&nbsp;<em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias('{ID}');">&nbsp;</em></td>
                    </tr>
                    <tr>
                        <td class="text-right">{LANG.image}</td>
                        <td><input class="w300 form-control pull-left" type="text" name="image" id="image" value="{DATA.image}" style="margin-right: 5px"/><input type="button" value="Browse server" name="selectimg" class="btn btn-info"/></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            {LANG.salary_kpi}
                            <em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.salary_kpi_note}">&nbsp;</em>
                        </td>
                        <td><input onkeyup="this.value=FormatNumber(this.value);" class="w300 form-control" type="text" value="{DATA.kpi_require}" name="kpi_require" /></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            {LANG.salary}
                            <em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.salary_note}">&nbsp;</em>
                        </td>
                        <td><input onkeyup="this.value=FormatNumber(this.value);" class="w300 form-control" type="text" value="{DATA.salary}" name="salary" /></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            {LANG.percent_responsibility}
                            <em class="fa fa-question-circle fa-pointer text-info" data-toggle="tooltip" data-original-title="{LANG.percent_responsibility_note}">&nbsp;</em>
                        </td>
                        <td><input class="w300 form-control" type="text" value="{DATA.percent_responsibility}" name="percent_responsibility" /></td>
                    </tr>
                    <tr>
                        <td class="text-right">{LANG.istype}</td>
                        <td>
                            <select name="istype" class="w300 form-control">
                                <!-- BEGIN: istype -->
                                <option value="{ISTYPE.value}"{ISTYPE.sl}>{ISTYPE.title}</option>
                                <!-- END: istype -->
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row text-center">
            <input type="submit" value="{LANG.save}" class="btn btn-primary"/>
        </div>
    </form>
    <script type="text/javascript">
        var uploads_dir_user = '{UPLOADS_DIR_USER}';
        $("#titlelength").html($("#idtitle").val().length);
        $("#idtitle").bind('keyup paste', function() {
            $("#titlelength").html($(this).val().length);
        });

        $("#descriptionlength").html($("#description").val().length);
        $("#description").bind('keyup paste', function() {
            $("#descriptionlength").html($(this).val().length);
        });
    </script>
    <!-- BEGIN: get_alias -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#idtitle').change(function() {
                get_alias('{ID}');
            });
        });
    </script>
    <!-- END: get_alias -->
<!-- END: add -->
<!-- END: main -->