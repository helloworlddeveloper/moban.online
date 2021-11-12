<!-- BEGIN: tree -->
{
text: '<i style="font-size: 11px;color:#ff3300">({TREE.postion})</i>&nbsp;{TREE.code} - {TREE.username} - {TREE.fullname}&nbsp;<i style="font-size: 12px;color:#ff3300">[{total_sub}]</i> - <strong style="color: #0FA015">{TREE.province_name}</strong>',
href: '{TREE.link_warehouse}',
tags: ['<a style="font-size: 12px;" href="{TREE.link_edit}">{TREE.lang_edit}</a>'],
<!-- BEGIN: loop -->
nodes: [
    <!-- BEGIN: tree -->
    {TREE_LOOP},
    <!-- END: tree -->
],
<!-- END: loop -->
},
<!-- END: tree -->
<!-- BEGIN: search -->
<div class="well">
    <form action="{NV_BASE_SITEURL}index.php" class="form-inline" method="get" id="search_form">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="maps-search" />
        <input type="hidden" name="userid" value="{userid}" />
        <input type="hidden" name="checkss" value="{checkss}" />
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label class="sr-only">{LANG.user_code}</label>
                    <input type="text" name="user_code" value="{SEARCH.user_code}" class="form-control" placeholder="{LANG.user_code}">
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <div class="input-group">
                        <select class="form-control" name="province">
                            <option value="0">--{LANG.province_select}--</option>
                            <!-- BEGIN:province -->
                            <option value="{PROVINCE.id}"{PROVINCE.sl}>{PROVINCE.title}</option>
                            <!-- END:province -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="{LANG.search}" />
            </div>
        </div>
    </form>
</div>
<!-- END: search -->
<!-- BEGIN: main -->
<div id="treeviewmap" class=""></div>
<script src="{NV_BASE_SITEURL}modules/{module_file}/libs/bootstrap-treeview.js"></script>
<script type="text/javascript">

    var defaultData = [
        {DATATREE}
    ];
    $('#treeviewmap').treeview({
        color: "#428bca",
        enableLinks: true,
        expandIcon: "fa fa-angle-double-right",
        collapseIcon: "fa fa-angle-double-down",
        nodeIcon: "fa fa-user",
        showTags: true,
        data: defaultData

    });
</script>

<!-- END: main -->