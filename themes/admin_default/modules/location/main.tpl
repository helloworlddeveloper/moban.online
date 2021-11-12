<!-- BEGIN: main -->
<div id="ablist">
    <input name="addNew" type="button" value="{add}" />
</div>
<div class="myh3">{tieude}</div>
<div id="pageContent"></div>
<script type="text/javascript">
//<![CDATA[
$(function() {
  $("div#pageContent").load("{MODULE_URL}={op}{op1}&list{page}&random=" + nv_randomPassword(10))
});
$("input[name=addNew]").click(function() {
  window.location.href = "{MODULE_URL}={op}{op1}&add";
  return false
});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: action -->
<div id="pageContent">
    <form id="addCat" method="post" action="{ACTION_URL}">
        <h3 class="myh3">{PTITLE}</h3>
        <table class="table table-striped table-bordered table-hover">
            <col style="width:200px" />
            <tbody class="second">
                <tr>
                    <td>{LANG.title} <span style="color:red">*</span></td>
                    <td><input title="{LANG.title}" class="txt" type="text" name="title" value="{CAT.title}" maxlength="255" /></td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td>{LANG.alias}</td>
                    <td><input title="{LANG.alias}" class="txt" type="text" name="alias" value="{CAT.alias}" maxlength="255" /></td>
                </tr>
            </tbody>
            <!-- BEGIN: mien -->
            <tbody>
            	<tr>
                    <td>{LANG.code} <span style="color:red">*</span></td>
                    <td><input title="{LANG.code}" class="txt" type="text" name="code" value="{CAT.code}" maxlength="255" /></td>
                </tr>
            </tbody>
            <tbody>
            	<tr>
            		
                    <td>{LANG.mien} <span style="color:red">*</span>                    	
                    </td>                    
                    <td> 
                    	<!-- BEGIN: add_mien -->
	                    <select name="pro" class="newWeight">
	                    <option value="">{LANG.chomien}</option>
	                    <!-- BEGIN: option -->
	                    <option value="{NEWWEIGHT.id}"{NEWWEIGHT.selected}>{NEWWEIGHT.title}</option>
	                    <!-- END: option -->
	                	</select>
	                	<!-- END: add_mien -->
	                	<!-- BEGIN: edit_mien -->
	                		{mien}
	                		<input type="hidden" name="pro" value="{CAT.idmien}" maxlength="255" />
	                	<!-- END: edit_mien -->
                	</td>
                </tr>            
            </tbody>
            <!-- END: mien -->
            <!-- BEGIN: province -->
            <tbody>
                <tr>
                    <td>{LANG.pro} <span style="color:red">*</span></td>
                    <td> 
	                   <!-- BEGIN: add_province -->
	                    <select name="pro" class="newWeight">
	                    <option value="">{LANG.choprovince}</option>
	                    <!-- BEGIN: option -->
	                    <option value="{NEWWEIGHT.id}"{NEWWEIGHT.selected}>{NEWWEIGHT.title}</option>
	                    <!-- END: option -->
	                	</select>
	                	<!-- END: add_province -->
	                	<!-- BEGIN: edit_province -->
	                		{province}
	                		<input type="hidden" name="pro" value="{CAT.idprovince}" maxlength="255" />
	                	<!-- END: edit_province -->
                	</td>
                </tr>
            </tbody>
            <!-- END: province -->
            <!-- BEGIN: district -->
            <tbody>
                <tr>
                    <td>{LANG.dis} <span style="color:red">*</span></td>
                    <td> 
	                    <!-- BEGIN: add_district -->
	                   <strong> <input id = "check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />{LANG.dis_cho}</strong><br/>							
						<!-- BEGIN: option -->
						<span style="width: 25%;float: left; margin-top: 5px;">
						<input name="deid[{NEWWEIGHT.id}]" value="{NEWWEIGHT.id}" type="checkbox"{NEWWEIGHT.checked} id ="idcheck[]"/> {NEWWEIGHT.title}
						
						</span>							
						<!-- END: option -->
						 <input type="hidden" name="add_ward" value="1" />							
	                	<!-- END: add_district -->
	                	<!-- BEGIN: edit_district -->
	                		{district}
	                		<input type="hidden" name="dis" value="{CAT.iddistrict}" maxlength="255" />
	                	<!-- END: edit_district -->
                	</td>
                </tr>
            </tbody>
            <!-- END: district -->
           
        </table>
        <input type="hidden" name="save" value="1" />
        <input name="submit" type="submit" value="{LANG.save}" />
    </form>
</div>
<script type="text/javascript">
//<![CDATA[
$("form#addCat").submit(function() {
  var a = $("input[name=title]").val();
  a = trim(a);
  $("input[name=title]").val(a);
  if(a == "") {
    alert("{LANG.errorIsEmpty}: " + $("input[name=title]").attr("title"));
    $("input[name=title]").select();
    return false
  }
  a = $(this).serialize();
  var c = $(this).attr("action");
  $("input[name=submit]").attr("disabled", "disabled");
  $.ajax({type:"POST", url:c, data:a, success:function(b) {
	var r_split = b.split("_");	
	if (r_split[0]== 'OK') {
		if (r_split.length != 1)
		{		
		  window.location.href = "{MODULE_URL}={op}"+r_split[1];
		}
		else
		{
			window.location.href = "{MODULE_URL}={op}";
		}	  
    }else {        
      alert(b);
      $("input[name=submit]").removeAttr("disabled")
    }
  }});
  return false
});
//]]>
</script>
<!-- END: action -->
<!-- BEGIN: list -->
<table class="table table-striped table-bordered table-hover">
    <col width="50" />
    <col width="250" />
    <thead>
        <tr>
            <td>
                {LANG.pos}
            </td>
            <td>
               {LANG.title}
            </td>
            <td>
            </td>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
        <tr>
            <td>
                <select name="p_{LOOP.id}" class="newWeight">
                    <!-- BEGIN: option -->
                    <option value="{NEWWEIGHT.value}"{NEWWEIGHT.selected}>{NEWWEIGHT.value}</option>
                    <!-- END: option -->
                </select>
            </td>
            <td>
                {LOOP.alink1} {LOOP.title}{LOOP.alink2}
            </td>
            <td>
           	{LOOP.alink3}{LOOP.alink4}<a href="{MODULE_URL}={op}{op1}&edit&id={LOOP.id}">{GLANG.edit}</a> | <a class="del" href="{LOOP.id}">{GLANG.delete}</a>
            </td>
        </tr>
    <!-- END: loop -->
    
    <br/>
    <!-- BEGIN: generate_page -->
    <tr class="footer">
        <td colspan="8">
            {GENERATE_PAGE}
        </td>
    </tr>
    <!-- END: generate_page -->
</table>
<script type="text/javascript">
//<![CDATA[
$("a.del").click(function() {
  confirm("{LANG.delConfirm} ?") && $.ajax({type:"POST", url:"{MODULE_URL}={op}{op1}", data:"del=" + $(this).attr("href"), success:function(a) {
    if(a == "OK") {
      window.location.href = window.location.href;
    }else {
      alert(a)
    }
  }});
  return false
});
$("select.newWeight").change(function() {
  var a = $(this).attr("name").split("_"), c = $(this).val(), d = this;
  a = a[1];
  $(this).attr("disabled", "disabled");
  $.ajax({type:"POST", url:"{MODULE_URL}={op}{op1}", data:"cWeight=" + c + "&id=" + a, success:function(b) {
    if(b == "OK") {
      $("div#pageContent").load("{MODULE_URL}={op}{op1}&list&random=" + nv_randomPassword(10))
    }else {
      alert("{LANG.errorChangeWeight}")
    }
    $(d).removeAttr("disabled")
  }});
  return false
});
//]]>
</script>

<!-- END: list -->
