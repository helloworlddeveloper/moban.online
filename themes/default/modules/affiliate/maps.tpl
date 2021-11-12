<!-- BEGIN: tree -->
<ul>
    <!-- BEGIN: subitem -->
    <li{SUBITEM.hasnumsubcat} data-userid="{SUBITEM.userid}" data-checkss="{SUBITEM.checkss}">
        {SUBITEM.postion} <a href="{SUBITEM.link_warehouse}">{SUBITEM.code} - {SUBITEM.fullname}</a>&nbsp;<i style="font-size: 12px;color:#ff3300">[{SUBITEM.numsubcat}]</i> - <strong style="color: #0FA015">{SUBITEM.province_name}</strong>
        <span class="right">
            <i id="loading_{SUBITEM.userid}"></i>
            <button class="loading_{SUBITEM.userid} btn btn-success btn-xs" onclick="nv_data_export_by_users( '{SUBITEM.userid}', '{SUBITEM.checkss}', '' );">{LANG.export}</button>
            <!-- BEGIN: permission -->
                <!-- BEGIN: active -->
                <button class="btn btn-primary btn-xs" onclick="nv_active_user({SUBITEM.userid}, '{SUBITEM.checkss}');">{LANG.active}</button>
                <!-- END: active -->
                <!-- BEGIN: pendingdelete -->
                Xóa lúc {SUBITEM.pendingdelete_text}
                <button class="btn btn-primary btn-xs" onclick="nv_indelete_user({SUBITEM.userid}, '{SUBITEM.checkss}');">Hủy lệnh xóa</button>
                <!-- END: pendingdelete -->
                <!-- BEGIN: nopendingdelete -->
                <button class="btn btn-danger btn-xs" onclick="nv_delete_user({SUBITEM.userid}, '{SUBITEM.checkss}');">{LANG.delete}</button>
                <!-- END: nopendingdelete -->
            <!-- END: permission -->
        </span>
    </li>
    <!-- END: subitem -->
</ul>
<!-- END: tree -->
<!-- BEGIN: main -->
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">SƠ ĐỒ HỆ THỐNG</div>
        <div class="panel-body">
            <ul class="treeview">
                <li{DATA_ROOT.hasnumsubcat}>
                    {DATA_ROOT.postion}
                    <a href="{DATA_ROOT.link_warehouse}">{DATA_ROOT.code} - {DATA_ROOT.fullname} &nbsp;<i style="font-size: 12px;color:#ff3300">[{DATA_ROOT.numsubcat}]</i> - <strong style="color: #0FA015">{DATA_ROOT.province_name}</strong></a>
                    <span class="right"><input type="button" class="btn btn-success btn-xs" name="data_export" value="{LANG.export_maps}" /></span>
                    <ul>
                        <!-- BEGIN: subitem -->
                        <li{SUBITEM.hasnumsubcat}{SUBITEM.permission} data-userid="{SUBITEM.userid}" data-checkss="{SUBITEM.checkss}">
                            {SUBITEM.postion} <a href="{SUBITEM.link_warehouse}">{SUBITEM.code} - {SUBITEM.fullname}</a>&nbsp;<i style="font-size: 12px;color:#ff3300">[{SUBITEM.numsubcat}]</i> - <strong style="color: #0FA015">{SUBITEM.province_name}</strong>
                            <span class="right">
                                <i id="loading_{SUBITEM.userid}"></i>
                                <button class="loading_{SUBITEM.userid} btn btn-success btn-xs" onclick="nv_data_export_by_users( '{SUBITEM.userid}', '{SUBITEM.checkss}', '' );">{LANG.export}</button>
                                <button class="btn btn-success btn-xs" onclick="window.location.href='{SUBITEM.link_edit}'">{LANG.edit}</button>
                                <!-- BEGIN: permission -->
                                    <!-- BEGIN: active -->
                                    <button class="btn btn-primary btn-xs" onclick="nv_active_user({SUBITEM.userid}, '{SUBITEM.checkss}');">{LANG.active}</button>
                                    <!-- END: active -->
                                    <!-- BEGIN: pendingdelete -->
                                    Xóa lúc {SUBITEM.pendingdelete_text}
                                    <button class="btn btn-primary btn-xs" onclick="nv_indelete_user({SUBITEM.userid}, '{SUBITEM.checkss}');">Hủy lệnh xóa</button>
                                    <!-- END: pendingdelete -->
                                    <!-- BEGIN: nopendingdelete -->
                                    <button class="btn btn-danger btn-xs" onclick="nv_delete_user({SUBITEM.userid}, '{SUBITEM.checkss}');">{LANG.delete}</button>
                                    <!-- END: nopendingdelete -->
                                <!-- END: permission -->
                            </span>
                        </li>
                        <!-- END: subitem -->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
    $.fn.extend({
        treeview:	function() {
            return this.each(function() {
                // Initialize the top levels;
                var tree = $(this);

                tree.addClass('treeview-tree');
                tree.find('li').each(function() {
                    var stick = $(this);
                    if( $(this).hasClass("hasnumsubcat") ){
                        reload_javasecrip($(this));
                    }
                });
            });
        }
    });

    $(window).on('load', function () {
        $('.treeview').each(function () {
            var tree = $(this);
            tree.treeview();
        })
    })
    var check_reload =0;
    function reload_javasecrip(obj){
        var branch = $(obj); //li with children ul
        branch.prepend("<i class='tree-indicator fa fa-chevron-right'></i>");
        branch.addClass('tree-branch');
        branch.on('click', function (e) {
            if (this == e.target) {
                var icon = $(this).children('i:first');

                icon.toggleClass("fa-chevron-down fa-chevron-right");
                $(this).children().children().toggle();
                var job_html = $(this);
                if( !$(this).find('ul').length ){
                    $.get('{LOADSUB}&userid=' + $(this).attr('data-userid') + '&checkss=' + $(this).attr('data-checkss'), function(data){
                        $(job_html).append(data);
                        var obj_reload =$(job_html).find('ul');
                        if( $(obj_reload).find(".hasnumsubcat") ){
                            reload_javasecrip($(obj_reload).find(".hasnumsubcat"));
                        }
                    });
                }
            }
        })
        if( check_reload == 0 ){
            branch.children().children().toggle();
        }
        check_reload = 1;
        branch.children('.tree-indicator, button, a').click(function(e) {
            branch.click();
        });
    }

    function nv_data_export_by_users( userid, checkss, data ) {

        $('#loading_' + userid ).html('{LANG.export_note}&nbsp;<img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="" />');
        $('.loading_' + userid ).hide();
        $.ajax({
            type : "POST",
            url : nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=export&nocache=" + new Date().getTime(),
            data : "step=1&userid=" + userid + '&checkss=' + checkss + '&datanext=' + data,
            dataType: "json",
            success : function(response) {
                if (response.status == "OK") {
                    $('.loading_' + userid ).show();
                    $('#loading_' + userid ).html('');
                    alert(response.mess);
                    window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&step=2';
                } else if( response.status == "NEXT" ){
                    nv_data_export_by_users( userid, checkss, response.mess );
                }  else {
                    $('.loading_' + userid ).show();
                    $('#loading_' + userid ).html('');
                    alert(response.mess);
                    window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}';
                }
            }
        });

    }
    function nv_data_export( data ) {
        $.ajax({
            type : "POST",
            url : nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=export&nocache=" + new Date().getTime(),
            data : "step=1&datanext=" + data,
            dataType: "json",
            success : function(response) {
                if (response.status == "OK") {
                    $("#loading_bar").hide();
                    alert(response.mess);
                    window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&step=2';
                } else if( response.status == "NEXT" ){
                    nv_data_export( response.mess );
                } else {
                    $("#loading_bar").hide();
                    alert(response.mess);
                    window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}';
                }
            }
        });
    }
    function nv_active_user(userid, checkss){
        if(confirm('Bạn có muốn kích hoạt tài khoản?')){
            $.ajax({
                type : "POST",
                url : nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=maps&nocache=" + new Date().getTime(),
                data : "setactive=1&userid=" + userid + '&checkss=' + checkss,
                dataType: "json",
                success : function(response) {
                    if (response.status == 1) {
                        alert(response.message);
                        window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}';
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }
    function nv_delete_user(userid, checkss){
        if(confirm('Bạn có muốn xóa tài khoản?')){
            $.ajax({
                type : "POST",
                url : nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=maps&nocache=" + new Date().getTime(),
                data : "del=1&userid=" + userid + '&checkss=' + checkss,
                dataType: "json",
                success : function(response) {
                    if (response.status == 1) {
                        alert(response.message);
                        window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}';
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }
    function nv_indelete_user(userid, checkss){
        if(confirm('Bạn có muốn hủy lệnh xóa tài khoản?')){
            $.ajax({
                type : "POST",
                url : nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=maps&nocache=" + new Date().getTime(),
                data : "indel=1&userid=" + userid + '&checkss=' + checkss,
                dataType: "json",
                success : function(response) {
                    if (response.status == 1) {
                        alert(response.message);
                        window.location.href = nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '={OP}';
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }
    $("input[name=data_export]").click(function() {
        $("input[name=data_export]").attr("disabled", "disabled");
        $(this).parent().html('{LANG.export_note}&nbsp;<img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="" />');
        nv_data_export('');
    });
</script>
<!-- END: main -->