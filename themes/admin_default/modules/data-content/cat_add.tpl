<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div class="alert alert-dagner">{ERROR}</div>
<!-- END: error -->

<form action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
	        <tbody>
	            <tr>
	                <td>
	                    {LANG.cat_name} <span class="red">*</span>
	                </td>
	                <td>
	                    <input class="form-control w400" value="{DATA.title}" name="title" id="title" maxlength="100" />
	                </td>
	            </tr>
	            <tr>
	                <td>
	                    {LANG.user_admin} <span class="red">*</span>
	                </td>
	                <td>
	                <select name="admin" class="form-control w300">
	                	<option value="">{LANG.admin0}</option>
	                	<!-- BEGIN: quantri -->
	                		<option value="{AD.userid}" {AD.selected}>{AD.username}</option>
	                	<!-- END: quantri -->
	                </select>
	                                                  
	                </td>
	            </tr>
	        </tbody>
	        <tbody>
	            <tr>
	                <td>
	                    {LANG.hometext}
	                </td>
	                <td>
	                <textarea rows="2" cols="50" name="hometext" id="hometext">{DATA.hometext}</textarea>                   
	                </td>
	            </tr>
	            <tr>
	                <td>
	                    {LANG.is_email_admin}
	                </td>
	                <td>
	                	<input type="checkbox" name="is_email"  value="1"  {DATA.is_email} />
	                </td>
	            </tr>
	            <tr>
	                <td colspan="2">
	                    <input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary" />
	                </td>
	            </tr>
	        </tbody>
	    </table>
	</div>
</form>
<!-- END: main -->