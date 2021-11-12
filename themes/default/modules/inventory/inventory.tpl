<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<form onsubmit="return check_data_inventory(this)" name="block_list" method="post" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
    <div class="table-responsive">
        <div class="text-center">
            <h1><strong>{LANG.bienbankiemke}</strong></h1>
            <p>
                {LANG.donvi}
                <select onchange="load_departmentid(this.value)" name="departmentid">
                    <option value="0">--{LANG.select_inventory}--</option>
                    <!-- BEGIN: department -->
                    <option value="{DEPARTMENT.id}"{DEPARTMENT.sl}>{DEPARTMENT.title}</option>
                    <!-- END: department -->
                </select>
            </p>
        </div>
        <div class="text-left">
            <div class="item-data clearfix">
                {LANG.thoigiankiemke}:
                <input name="hour" value="{DATA.hour}" class="dotted-input w50">
                {LANG.hour}
                <input name="minute" value="{DATA.minute}" class="dotted-input w50">
                {LANG.day}
                <input name="day" value="{DATA.day}" class="dotted-input w50">
                {LANG.month}
                <input name="month" value="{DATA.month}" class="dotted-input w50">
                {LANG.year}
                <input name="year" value="{DATA.year}" class="dotted-input w50">
            </div>
            <div class="clearfix">&nbsp;</div>
            <p>{LANG.thanhphamkiemke}:</p>
            <div class="item-data">
                <div id="thanhphankiemke" class="tokenarea">
                    <!-- BEGIN: keywords -->
                    <span class="uiToken removable" title="{KEYWORDS}" ondblclick="$(this).remove();"> {KEYWORDS} <input type="hidden" autocomplete="off" name="keywords[]" value="{KEYWORDS}" /> <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a> </span>
                    <span class="fl">&nbsp;&nbsp;{LANG.chucvu}:&nbsp;</span>
                    <input class="fl dotted-input" name="chucvu[]">
                    <!-- END: keywords -->
                </div>
                <div class="message_body" style="width: 300px">
                    <input id="keywords-search" type="text" placeholder="{LANG.input_user_system}" class="form-control textInput" style="width: 100%;" />
                </div>
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>
        <table class="table table-striped table-bordered table-hover">
            <thead class="text-center" style="font-weight: bold">
            <tr>
                <td rowspan="2" class="text-center">{LANG.tt}</td>
                <td rowspan="2" class="text-center">{LANG.product_list}</td>
                <td rowspan="2" class="text-center">{LANG.select_unit}</td>
                <td rowspan="2" class="text-center">{LANG.time_in}</td>
                <td rowspan="2" class="text-center">{LANG.select_department}</td>
                <td colspan="3" class="text-center">{LANG.solieusoketoan}</td>
                <td rowspan="2" class="text-center">{LANG.solieukiemkethucte}</td>
                <td colspan="2" class="text-center">{LANG.tinhtrang}</td>
                <td colspan="2" class="text-center">{LANG.sotaisanthuathieu}</td>
                <td rowspan="2" class="text-center">{LANG.ghichu}</td>
            </tr>
            <tr>
                <td>{LANG.amount}</td>
                <td>{LANG.nguyengia}</td>
                <td>{LANG.giatriconlai}</td>
                <td>{LANG.hong}</td>
                <td>{LANG.dangsd}</td>
                <td>{LANG.thua}</td>
                <td>{LANG.thieu}</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                <td>12</td>
                <td>13</td>
                <td>14</td>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: producttype -->
            <tr>
                <td colspan="14"><strong>{PRODUCTTYPE.title}</strong></td>
            </tr>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.tt}</td>
                <td class="text-left">{ROW.title}</td>
                <td>{ROW.unit}</td>
                <td>{ROW.time_in}</td>
                <td class="text-center">{ROW.department}</td>
                <td class="text-center">{ROW.amount}</td>
                <td class="text-center">{ROW.price}</td>
                <td class="text-center">{ROW.price_conlai}<input type="hidden" name="price_conlai[{ROW.id}]" value="{ROW.price_conlai}"></td>
                <td class="text-center"><input type="text" name="solieukiemkethucte[{ROW.id}]" data_id="{ROW.id}" data_max_amount="{ROW.amount_check}" class="solieukiemkethucte numberinput dotted-input editinput"></td>
                <td class="text-center"><input type="text" name="hong[{ROW.id}]" data_id="{ROW.id}"  data_max_amount="{ROW.amount_check}" class="hong numberinput dotted-input editinput"></td>
                <td class="text-center" id="dangsudung_{ROW.id}"></td>
                <td class="text-center"><input type="text" name="thua[{ROW.id}]" data_id="{ROW.id}" class="thua numberinput dotted-input editinput"></td>
                <td class="text-center"><input type="text" name="thieu[{ROW.id}]" data_id="{ROW.id}" class="thieu numberinput dotted-input editinput"></td>
                <td class="text-center"><input type="text" name="ghichu[{ROW.id}]" class="editinput dotted-input"></td>
            </tr>
            <!-- END: loop -->
            <!-- END: producttype -->
            </tbody>
        </table>
        <div class="text-center clear">
            <div class="form-group">
                <div class="input-group">
						<span class="input-group-addon">
							{LANG.nguyennhanthuathieu}
						</span>
                    <input type="text" maxlength="160" name="nguyennhan" class="form-control" placeholder="{LANG.nguyennhanthuathieu}" />
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
						<span class="input-group-addon">
							{LANG.kiennghi}
						</span>
                    <input type="text" maxlength="160" name="kiennghi" class="form-control" placeholder="{LANG.kiennghi}" />
                </div>
            </div>
            <input type="hidden" name="submit" value="1">
            <input type="hidden" name="departmentid" value="{departmentid}">
            <input class="btn btn-primary" type="submit" name="submit" value="{LANG.submit}"><br><br>
        </div>
    </div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.cookie.js"></script>
<script type="text/javascript">
    $(".numberinput").on('keyup', function (event) {
        $(this).val($(this).val().replace(/[a-z]/, ''));
    });
    $(".solieukiemkethucte").on('keyup', function (event) {
        if($(this).val() > parseInt( $(this).attr('data_max_amount'))){
            $(this).val($(this).attr('data_max_amount'));
        }
        var amount_hong = $("input[name^='hong["+ $(this).attr('data_id') +"]']").val()
        if( amount_hong == '') amount_hong = 0;
        else amount_hong = parseInt( amount_hong );
        $('#dangsudung_' + $(this).attr('data_id')).html($(this).val() - amount_hong );
    });
    $(".hong").on('keyup', function (event) {
        var amount_kiemke = parseInt( $("input[name^='solieukiemkethucte["+ $(this).attr('data_id') +"]']").val());
        if($(this).val() > amount_kiemke ){
            $(this).val(amount_kiemke);
        }
        var amount_thucte = $("input[name^='solieukiemkethucte["+ $(this).attr('data_id') +"]']").val()
        if( amount_thucte == ''){
            amount_thucte = $(this).attr('data_max_amount');
            $("input[name^='solieukiemkethucte["+ $(this).attr('data_id') +"]']").val(amount_thucte);
        }
        else amount_thucte = parseInt( amount_thucte );
        $('#dangsudung_' + $(this).attr('data_id')).html( amount_thucte - $(this).val() );
    });
    $(".thua").on('keyup', function (event) {
        var amount_kiemke = parseInt( $("input[name^='solieukiemkethucte["+ $(this).attr('data_id') +"]']").val());
        if($(this).val() > amount_kiemke ){
            $(this).val(amount_kiemke);
        }
    });
    $(".thieu").on('keyup', function (event) {
        $(this).val($(this).val().replace(/[a-z]/, ''));
    });
    var chucvu = '{LANG.chucvu}';
    $("#keywords-search").bind("keydown", function(event, ui) {
        if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
            event.preventDefault();
        }
        /*
        if(event.keyCode==13){
            nv_add_element( 'thanhphamkiemke', ui.item.key, ui.item.value );
            $(this).val('');
            return false;
        }
        */
    }).autocomplete({
        source : function(request, response) {
            $.getJSON(nv_base_siteurl + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=inventory&user", {
                term : extractLast(request.term)
            }, response);
        },
        search : function() {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < 2) {
                return false;
            }
        },
        focus : function() {
            //no action
        },
        select : function(event, ui) {
            // add placeholder to get the comma-and-space at the end
            if(event.keyCode!=13){
                nv_add_element( 'thanhphankiemke', ui.item.key, ui.item.value );
                $(this).val('');
            }
            return false;
        }
    });
</script>
<!-- END: main -->