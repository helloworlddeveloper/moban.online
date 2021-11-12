<!-- BEGIN: main -->

<div id="pageContent"></div>

<input name="addNew" type="button" value="{LANG.addCat}" class="btn btn-default" />

<script type="text/javascript">
    //<![CDATA[
    $(function() {
        $("div#pageContent").load("{MODULE_URL}=cat&list&random=" + nv_randomPassword(10));
    });
    $("input[name=addNew]").click(function() {
        window.location.href = "{MODULE_URL}=cat&add";
        return false;
    });
    //]]>
</script>
<!-- END: main -->

<!-- BEGIN: action -->
<div id="pageContent">
    <form id="addCat" method="post" action="{ACTION_URL}">
        <h3 class="myh3">{PTITLE}</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <colgroup>
                    <col class="w200" />
                </colgroup>
                <tbody>
                <tr>
                    <td>{LANG.title} <span class="red">*</span></td>
                    <td><input title="{LANG.title}" class="form-control w300" type="text" name="title" value="{CAT.title}" maxlength="255" /></td>
                </tr>
                </tbody>
            </table>
        </div>
        <input type="hidden" name="save" value="1" />
        <input name="submit" type="submit" value="{LANG.save}" class="btn btn-primary" />
    </form>
</div>

<script type="text/javascript">
    //<![CDATA[
    $("form#addCat").submit(function() {
        var a = $("input[name=title]").val();
        a = trim(a);
        $("input[name=title]").val(a);
        if (a == "") {
            alert("{LANG.errorIsEmpty}: " + $("input[name=title]").attr("title"));
            $("input[name=title]").select();
            return false;
        }
        a = $(this).serialize();
        var c = $(this).attr("action");
        $("input[name=submit]").attr("disabled", "disabled");
        $.ajax({
            type : "POST",
            url : c,
            data : a,
            success : function(b) {
                if (b == "OK") {
                    window.location.href = "{MODULE_URL}=cat";
                } else {
                    alert(b);
                    $("input[name=submit]").removeAttr("disabled");
                }
            }
        });
        return false;
    });
    //]]>
</script>
<!-- END: action -->

<!-- BEGIN: list -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <colgroup>
            <col class="w50" />
            <col />
            <col width="150" />
        </colgroup>
        <thead>
        <tr>
            <th>{LANG.pos}</th>
            <th>{LANG.title}</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td>
                <select name="p_{LOOP.id}" class="form-control w100 newWeight">
                    <!-- BEGIN: option -->
                    <option value="{NEWWEIGHT.value}"{NEWWEIGHT.selected}>{NEWWEIGHT.value}</option>
                    <!-- END: option -->
                </select></td>
            <td> {LOOP.title} </td>
            <td class="text-center">
                <em class="fa fa-edit fa-lg">&nbsp;</em><a href="{MODULE_URL}=cat&edit&id={LOOP.id}">{GLANG.edit}</a> -
                <em class="fa fa-trash-o fa-lg">&nbsp;</em><a class="del" href="{LOOP.id}">{GLANG.delete}</a>
            </td>
        </tr>
        <!-- END: loop -->
        <tbody>
    </table>
</div>

<script type="text/javascript">
    //<![CDATA[
    $("a.del").click(function() {
        confirm("{LANG.delConfirm} ?") && $.ajax({
            type : "POST",
            url : "{MODULE_URL}=cat",
            data : "del=" + $(this).attr("href"),
            success : function(a) {
                if (a == "OK") {
                    window.location.href = window.location.href;
                } else {
                    alert(a);
                }
            }
        });
        return false;
    });
    $("select.newWeight").change(function() {
        var a = $(this).attr("name").split("_"), c = $(this).val(), d = this;
        a = a[1];
        $(this).attr("disabled", "disabled");
        $.ajax({
            type : "POST",
            url : "{MODULE_URL}=cat",
            data : "cWeight=" + c + "&id=" + a,
            success : function(b) {
                if (b == "OK") {
                    $("div#pageContent").load("{MODULE_URL}=cat&list&random=" + nv_randomPassword(10));
                } else {
                    alert("{LANG.errorChangeWeight}");
                }
                $(d).removeAttr("disabled");
            }
        });
        return false;
    });
    //]]>
</script>
<!-- END: list -->