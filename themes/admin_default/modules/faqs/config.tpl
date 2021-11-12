<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<div id="users">
    <form action="{FORM_ACTION}" method="post">
		<div class="table-responsive" style="margin-top: 10px">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col class="w300" />
				</colgroup>
	            <tbody>
	                <tr>
	                    <td>{LANG.config_email_cus}</td>
	                    <td>
	                        <input name="is_cus" value="1" type="checkbox"{DATA.is_cus} />
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_email_admin}</td>
	                    <td>
	                        <input name="is_admin" value="1" type="checkbox"{DATA.is_admin} />
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_email}</td>
	                    <td>
	                        <input name="is_email" type="email" class="form-control w300" value="{DATA.is_email}" width="200px"/>
	                    </td>
	                </tr>
	                <!--
	                <tr>
	                    <td>{LANG.is_mark}</td>
	                    <td>
	                         <input name="is_mark" value="1" type="checkbox"{DATA.is_mark} />
	                    </td>
	                </tr>
	                -->
	                <tr>
	                    <td>{LANG.checkque}</td>
	                    <td>
	                         <input name="duyetqu" value="1" type="checkbox"{DATA.duyetqu} />
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.checkan}</td>
	                    <td>
	                         <input name="duyetan" value="1" type="checkbox"{DATA.duyetan} />
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.captcha_display}</td>
	                    <td>
	                    	<select name="is_captcha" class="form-control w200">
	                    		<!-- BEGIN: is_captcha -->
	                    		<option value="{IS_CAPTCHA.id}" {IS_CAPTCHA.selected}>{IS_CAPTCHA.title}</option>
	                    		<!-- END: is_captcha -->
	                    	</select>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.num_row_list}</td>
	                    <td>
	                         <input type="number" name="num_row_list" value="{DATA.num_row_list}" class="form-control w200" />
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.length_home}</td>
	                    <td>
	                         <input type="number" name="length_home" value="{DATA.length_home}" class="form-control w200" /><span class="help-block">{LANG.length_home_note}</span>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.config_home_cat}</td>
	                    <td>
	                         <input type="checkbox" name="home_cat" value="1" {DATA.home_cat} />
	                    </td>
	                </tr>
	                <!--
	                <tr>
	                    <td>{LANG.mark_start}</td>
	                    <td>
	                        <input name="mark_start" type="text" value="{DATA.mark_start}"  width="20px"/>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.mark_question}</td>
	                    <td>
	                        <input name="mark_question" type="text" value="{DATA.mark_question}"  width="20px"/>
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.mark_an_add}</td>
	                    <td>
	                        <input name="mark_an_add" type="text" value="{DATA.mark_an_add}"  width="20px"/>
	                    </td>
	                </tr>

	                <tr>
	                    <td>{LANG.mark_an_cho}</td>
	                    <td>
	                        <input name="mark_an_cho" type="text" value="{DATA.mark_an_cho}"  width="20px"/>
	                    </td>
	                </tr>

	                <tr>
	                    <td>{LANG.mark_an_most}</td>
	                    <td>
	                        <input name="mark_an_most" type="text" value="{DATA.mark_an_most}"  width="20px"/>
	                    </td>
	                </tr>
	                -->
	                <tr>
	                    <td>{LANG.who_view}</td>
	                    <td>
	                        <!-- BEGIN: who_view -->
	                        <div class="row">
	                        	<label><input type="checkbox" name="who_view[]" value="{WHO_VIEW.key}" {WHO_VIEW.checked} />{WHO_VIEW.title}</label>
	                        </div>
	                        <!-- END: who_view -->
	                    </td>
	                </tr>
	                <tr>
	                    <td>{LANG.who_an}</td>
	                    <td>
	                        <!-- BEGIN: who_an -->
	                        <div class="row">
	                        	<label><input type="checkbox" name="who_an[]" value="{WHO_AN.key}" {WHO_AN.checked} />{WHO_AN.title}</label>
	                        </div>
	                        <!-- END: who_an -->
	                    </td>
	                </tr>
	            </tbody>
	      </table>
		</div>
      <div class="text-center">
            <input type="submit" name="submit" value="{LANG.config_confirm}" class="btn btn-primary" />
        </div>
    </form>
</div>
<!-- END: main -->