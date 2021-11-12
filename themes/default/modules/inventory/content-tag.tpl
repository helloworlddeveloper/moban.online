<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<form name="block_list" method="post" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
    <div class="table-responsive">
        <div class="text-center">
            <h1><strong>{LANG.tag_product}</strong></h1>
        </div>
        <div class="text-left">
            <div class="item-data clearfix">
                {LANG.bienbangiaonhan}:
                <input name="bienbanso" value="{DATA.sohopdong}" class="dotted-input w50">
                {LANG.day}
                <input name="day" value="{DATA.day}" class="dotted-input w50">
                {LANG.month}
                <input name="month" value="{DATA.month}" class="dotted-input w50">
                {LANG.year}
                <input name="year" value="{DATA.year}" class="dotted-input w50">
            </div>
            <div class="item-data clearfix">
                {LANG.tenkyhieu}:
                <input name="tenkyhieu" value="{DATA.tenkyhieu}" class="dotted-input w150">
                {LANG.sohieu}
                <input name="sohieu" value="{DATA.sokyhieu}" class="dotted-input w150">
            </div>
            <div class="item-data clearfix">
                {LANG.nuocsanxuat}:
                <input name="nuocsanxuat" value="{DATA.nuocsanxuat}" class="dotted-input w150">
                {LANG.namsanxuat}
                <input name="namsanxuat" value="{DATA.namsanxuat}" class="dotted-input w150">
            </div>
            <div class="item-data clearfix">
                {LANG.bophanquanly}:
                <input name="bophanquanly" value="{DATA.bophanquanly}" class="dotted-input w150">
                {LANG.namduavaosudung}
                <input name="namduavaosudung" value="{DATA.namsudung}" class="dotted-input w150">
            </div>
            <div class="item-data clearfix">
                {LANG.congxuat}:
                <input name="congxuat" value="{DATA.congsuat}" class="dotted-input" style="width: 450px">
            </div>
            <div class="item-data clearfix">
                {LANG.dinhchisudung}
                {LANG.day}
                <input name="ngaydinhchi" value="{DATA.ngaydinhchi}" class="dotted-input w50">
                {LANG.month}
                <input name="thangdinhchi" value="{DATA.thangdinhchi}" class="dotted-input w50">
                {LANG.year}
                <input name="namdinhchi" value="{DATA.namdinhchi}" class="dotted-input w50">
            </div>
            <div class="item-data clearfix">
                {LANG.lydodinhchi}:
                <input name="lydodinhchi" value="{DATA.lydodinhchi}" class="dotted-input" style="width: 450px">
            </div>
            <div class="clearfix">&nbsp;</div>
        </div>
        <table id="sohieu_table" class="table table-striped table-bordered table-hover">
            <thead class="text-center" style="font-weight: bold">
            <tr>
                <td rowspan="2" class="text-center">{LANG.sohieuchungtu}</td>
                <td colspan="2" class="text-center">{LANG.nguyengia_taisan}</td>
                <td colspan="3" class="text-center">{LANG.giatrihaomon}</td>
            </tr>
            <tr>
                <td>{LANG.day}</td>
                <td>{LANG.nguyengia}</td>
                <td>{LANG.year}</td>
                <td>{LANG.giatrihaomon}</td>
                <td>{LANG.luyke}</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center"><input style="width: 100%" type="text" value="{DETAIL.sochungtu}" name="sochungtu[]" class="more_sohieu dotted-input editinput"></td>
                <td class="text-center"><input style="width: 100%" type="text" value="{DETAIL.ngaynhap}" name="ngayghichungtu[]" class="more_sohieu ngayghichungtu dotted-input editinput"></td>
                <td class="text-center"><input style="width: 100%" onkeyup="this.value=FormatNumber(this.value);" type="text" value="{DETAIL.price}" name="nguyengia[]" class="more_sohieu numberinput dotted-input editinput"></td>
                <td class="text-center"><input style="width: 100%" type="text" name="namsudung[]" value="{DETAIL.namsudung}" class="more_sohieu numberinput dotted-input editinput"></td>
                <td class="text-center"><input style="width: 100%" onkeyup="this.value=FormatNumber(this.value);" type="text" value="{DETAIL.giatrihaomon}" name="giatrihaomon[]" class="more_sohieu numberinput dotted-input editinput"></td>
                <td class="text-center"><input style="width: 100%" onkeyup="this.value=FormatNumber(this.value);" type="text" value="{DETAIL.luyke}" name="luyke[]" class="more_sohieu numberinput dotted-input editinput"></td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
        <div class="clear">
            <strong>{LANG.dungcuphutungkemtheo}</strong>
            <table id="table_dungcu" class="table table-striped table-bordered table-hover">
                <thead class="text-center" style="font-weight: bold">
                <tr>
                    <td class="text-center">{LANG.stt}</td>
                    <td class="text-center">{LANG.tenquycachdungcu}</td>
                    <td class="text-center">{LANG.donvitinh}</td>
                    <td class="text-center">{LANG.amount}</td>
                    <td class="text-center">{LANG.giatri}</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                </tr>
                </thead>
                <tbody>
                <!-- BEGIN: loopdungcu -->
                <tr>
                    <td class="text-center">{DUNGCU.stt}</td>
                    <td class="text-center"><input style="width: 100%" type="text" value="{DUNGCU.tencongcu}" name="tencongcu[]" class="more_dungcu dotted-input editinput"></td>
                    <td class="text-center"><input style="width: 100%" type="text" value="{DUNGCU.donvitinh}" name="donvitinh[]" class="more_dungcu dotted-input editinput"></td>
                    <td class="text-center"><input style="width: 100%" type="text" value="{DUNGCU.soluong}" name="soluong[]" class="more_dungcu numberinput dotted-input editinput"></td>
                    <td class="text-center"><input style="width: 100%" type="text" value="{DUNGCU.giatri}" onkeyup="this.value=FormatNumber(this.value);" name="giatri[]" class="more_dungcu numberinput dotted-input editinput"></td>
                </tr>
                <!-- END: loopdungcu -->
                </tbody>
            </table>
            <div class="item-data clearfix">
                {LANG.ghigiamtscd}
                <input name="ghigiamtscd" value="{DATA.ghigiamtscd}" class="dotted-input w50">
                {LANG.day}
                <input name="ngayghigiam" value="{DATA.ngayghigiamtscd}" class="dotted-input w50">
                {LANG.month}
                <input name="thangghigiam" value="{DATA.thangghigiamtscd}" class="dotted-input w50">
                {LANG.year}
                <input name="namghigiam" value="{DATA.namghigiamtscd}" class="dotted-input w50">
            </div>
            <div class="text-center">
                <input type="hidden" name="submit" value="1">
                <input name="id" value="{DATA.id}" type="hidden">
                <input class="btn btn-primary" type="submit" name="submit" value="{LANG.submit}">
                <div class="clearfix">&nbsp;</div>
            </div>
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
    var html_sohieu = '<tr>\n' +
        '                <td class="text-center"><input style="width: 100%" type="text" name="sochungtu[]" class="more_sohieu dotted-input editinput"></td>\n' +
        '                <td class="text-center"><input style="width: 100%" type="text" name="ngayghichungtu[]" class="more_sohieu ngayghichungtu dotted-input editinput"></td>\n' +
        '                <td class="text-center"><input style="width: 100%" onkeyup="this.value=FormatNumber(this.value);" type="text" name="nguyengia[]" class="more_sohieu numberinput dotted-input editinput"></td>\n' +
        '                <td class="text-center"><input style="width: 100%" type="text" name="namsudung[]" class="more_sohieu numberinput dotted-input editinput"></td>\n' +
        '                <td class="text-center"><input style="width: 100%" onkeyup="this.value=FormatNumber(this.value);" type="text" name="giatrihaomon[]" class="more_sohieu numberinput dotted-input editinput"></td>\n' +
        '                <td class="text-center"><input style="width: 100%" onkeyup="this.value=FormatNumber(this.value);" type="text" name="luyke[]" class="more_sohieu numberinput dotted-input editinput"></td>\n' +
        '            </tr>';
    $(document).ready(function() {
        $(document).on("focus", ".more_sohieu", function () {
            if( $( "#sohieu_table tr:last" ).index() ==  $(this).parent().parent().index() ){
                $('#sohieu_table').append(html_sohieu);
            }

        });
    });
    var stt_dungcu = {stt};
    $(document).ready(function() {
        $(document).on("focus", ".more_dungcu", function () {
            if( $( "#table_dungcu tr:last" ).index() ==  $(this).parent().parent().index() ){
                var html_dungcu = '<tr>\n' +
                    '                    <td class="text-center">' + stt_dungcu + '</td>\n' +
                    '                    <td class="text-center"><input style="width: 100%" type="text" name="tencongcu[]" class="more_dungcu dotted-input editinput"></td>\n' +
                    '                    <td class="text-center"><input style="width: 100%" type="text" name="donvitinh[]" class="more_dungcu dotted-input editinput"></td>\n' +
                    '                    <td class="text-center"><input style="width: 100%" type="text" name="soluong[]" class="more_dungcu numberinput dotted-input editinput"></td>\n' +
                    '                    <td class="text-center"><input style="width: 100%" onkeyup="this.value=FormatNumber(this.value);" type="text" name="giatri[]" class="more_dungcu numberinput dotted-input editinput"></td>\n' +
                    '                </tr>';
                $('#table_dungcu').append(html_dungcu);
                stt_dungcu++;
            }
        });
        $(document).on("focus", ".ngayghichungtu", function () {
            $(this).datepicker({
                showOn: "focus",
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                buttonImage: nv_base_siteurl + "assets/images/calendar.gif",
                buttonImageOnly: true
            });
        });
    });
</script>
<!-- END: main -->