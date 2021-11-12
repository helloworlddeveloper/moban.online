<!-- BEGIN: main -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input class="form-control" type="hidden" value="{aid}" name="aid" />
    <input class="form-control" type="hidden" value="1" name="save_price" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>{LANG.product_title}</th>
                <th class="text-center">{LANG.price_one}</th>
                <th class="text-center">{LANG.price_agency}</th>
            </tr>
            </thead>
            <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td><a href="{VIEW.link}" target="_blank">{VIEW.title}</a></td>
                <td>{VIEW.product_price}</td>
                <td class="text-center">
                    <input onkeyup="this.value=FormatNumber(this.value);" type="text" name="price_agency[{VIEW.id}]" value="{VIEW.price_agency}" class="form-control" />
                </td>
            </tr>
            <!-- END: loop -->
            </tbody>
        </table>
    </div>

    <input type="submit" value="{LANG.save}" class="btn btn-primary">
</form>
<!-- END: main -->