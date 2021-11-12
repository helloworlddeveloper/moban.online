<!-- BEGIN: main -->
<script type="text/javascript">
	var cat_del_cofirm = "{LANG.cat_del}";
</script>

<a class="button1" href="{ADD_NEW_CAT}"><span><span>{LANG.cat_add}</span></span></a>

<div id="users">
    <table class="tab1">
        <caption>{TABLE_CAPTION}</caption>
        <colgroup>
        	<col class="w100" />
        	<col />
        	<col class="w150" />
        </colgroup>
        <thead>
            <tr>
                <th>
                    {LANG.cat_sort}
                </th>
                <th>
                    {LANG.cat_name}
                </th>
                <th>
                    {LANG.cat_parent}
                </th>
                <th class="text-center">
                    {LANG.cat_active}
                </th>
                <th class="text-center">
                    {LANG.feature}
                </th>
            </tr>
        </thead>
        <tbody>
        <!-- BEGIN: row -->
            <tr>
                <td>
                    <select name="weight" id="weight{ROW.id}" onchange="nv_chang_cat_weight({ROW.id});">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td>
                    <strong><a href="{ROW.titlelink}">{ROW.title}</a></strong>{ROW.numsub}
                </td>
                <td>
                    {ROW.parentid}
                </td>
                <td class="text-center">
                    <input type="checkbox" name="active" id="change_status{ROW.id}" value="1"{ROW.status} onclick="nv_chang_cat_status({ROW.id});" />
                </td>
                <td class="text-center">
                    <span class="edit_icon"><a href="{EDIT_URL}">{GLANG.edit}</a></span>
                    &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_cat_del({ROW.id});">{GLANG.delete}</a></span>
                </td>
            </tr>
        <!-- END: row -->
        <tbody>
    </table>
</div>
<!-- END: main -->