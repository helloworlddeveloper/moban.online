<!-- BEGIN: main -->
<form class="form-inline" action="" method="post">
    <div role="tabpanel">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="payment_docpay">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <td style="padding:10px"><strong>{LANG.setting_intro_pay}</strong>
                                <br />
                                <span style="font-style:italic">{LANG.document_payment_note}</span></td>
                        </tr>
                        <tr>
                            <td>{content_docpay}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" />
        <input type="hidden" value="1" name="saveintro">
    </div>
</form>
<!-- END: main -->