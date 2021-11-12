<!-- BEGIN: main -->
	<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.config_sms}</div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-4 text-right"><strong>{LANG.config_sms_on}</strong></label>
                    <div class="col-sm-20">
                        <input type="checkbox" value="1" name="sms_on" {SMS_ON}/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><strong>{LANG.apikey}</strong></label>
                    <div class="col-sm-20">
                        <input type="text" class="form-control" value="{DATA.apikey}" name="apikey" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><strong>{LANG.secretkey}</strong></label>
                    <div class="col-sm-20">
                        <input type="text" class="form-control" value="{DATA.secretkey}" name="secretkey" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><strong>{LANG.config_sms_type}</strong></label>
                    <div class="col-sm-20">
                        <select class="form-control" name="sms_type">
                            <!-- BEGIN: sms_type -->
                            <option value="{SMS_TYPE.key}"{SMS_TYPE.selected}>{SMS_TYPE.title}</option>
                            <!-- END: sms_type -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"><strong>{LANG.brandname}</strong></label>
                    <div class="col-sm-20">
                        <input type="text" class="form-control" value="{DATA.brandname}" name="brandname" />
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
    			<colgroup>
                    <col style="width: 400px;" />
    				<col style="width: auto;" />
    			</colgroup>
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center">{LANG.user_permission}</th>
                        <th rowspan="2" class="text-center">{LANG.feature}</th>
                        <th class="text-center" colspan="5">{LANG.permission}</th>
                    </tr>
                    <tr>
                        <th class="text-center">{LANG.permission_view}</th>
                        <th class="text-center">{LANG.permission_add}</th>
                        <th class="text-center">{LANG.permission_edit}</th>
                        <th class="text-center">{LANG.permission_del}</th>
                        <th class="text-center">{LANG.permission_order}</th>
                    </tr>
                </thead>
    			<tbody>
    			     <!-- BEGIN: user_permisson -->
    				    <tr>
    					   <td rowspan="{total_op}">
                                <strong>{USER_DATA.username}&nbsp;-&nbsp;{USER_DATA.full_name}</strong>
                                <input type="hidden" name="user_permisson[]" value="{USER_DATA.userid}" />
                            </td>
                        </tr>
                        <!-- BEGIN: list_op -->
                        <tr>
                            <td>
                                {LANG_OP}
                            </td>
                            <td class="text-center"><label><input name="{OP_PERMINSSION}_{USER_DATA.userid}_view" type="checkbox" value="1"{checked_view} /></label></td>
                            <td class="text-center"><label><input name="{OP_PERMINSSION}_{USER_DATA.userid}_add" type="checkbox" value="1"{checked_add} /></label></td>
                            <td class="text-center"><label><input name="{OP_PERMINSSION}_{USER_DATA.userid}_edit" type="checkbox" value="1"{checked_edit} /></label></td>
                            <td class="text-center"><label><input name="{OP_PERMINSSION}_{USER_DATA.userid}_del" type="checkbox" value="1"{checked_del} /></label></td>
                            <td class="text-center"><label><input name="{OP_PERMINSSION}_{USER_DATA.userid}_order" type="checkbox" value="1"{checked_order} /></label></td>
    				    </tr>
                        <!-- END: list_op -->
    				<!-- END: user_permisson -->
    			</tbody>
    		</table>
            <div class="table-responsive">
        		<table class="table table-striped table-bordered table-hover">
        			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.group_content}</caption>
        			<thead>
        				<tr class="text-center">
        					<th>{GLANG.mod_groups}</th>
        					<th>{LANG.group_addhistory}</th>
        				</tr>
        			</thead>
        			<tbody>
        				<!-- BEGIN: config_mkt -->
        				<tr>
        					<td><strong>{ROW.group_title}</strong><input type="hidden" value="{ROW.group_id}" name="array_group_id[]" /></td>
        					<td class="text-center"><input type="checkbox" value="1" name="array_addhistory[{ROW.group_id}]"{ROW.addhistory}/></td>
        				</tr>
        				<!-- END: config_mkt -->
        			</tbody>
        		</table>
        	</div>
        <div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
        </div>
	</form>
<!-- END: main -->