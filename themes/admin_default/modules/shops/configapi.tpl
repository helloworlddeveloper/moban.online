<!-- BEGIN: main -->
<div id="users">
	<form action="{FORM_ACTION}" method="post">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col style="width: 260px" />
					<col/>
				</colgroup>
				<tfoot>
					<tr>
						<td colspan="2"><input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary" /></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td>{LANG.allow_accept}</td>
						<td><input type="checkbox" value="1" name="allow_accept"{allow_accept}/></td>
					</tr>
					<tr>
						<td>{LANG.domain_accept}</td>
						<td><input class="form-control w200" name="domain_accept" value="{DATA.domain_accept}" /></td>
					</tr>
                    <tr>
						<td>{LANG.apikey}</td>
						<td>
                            <input class="form-control w200" name="apikey" value="{DATA.apikey}" />
                            <a href="javascript:void(0);" onclick="return nv_genpass();" class="btn btn-primary btn-xs">{LANG.genpass}</a>
                        </td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>

<script>
    function nv_genpass(){
        var chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZ";
        var string_length = 20;
        var randomstring = '';
        var charCount = 0;
        var numCount = 0;

        for (var i=0; i<string_length; i++) {
            // If random bit is 0, there are less than 3 digits already saved, and there are not already 5 characters saved, generate a numeric value.
            if((Math.floor(Math.random() * 2) == 0) && numCount < 3 || charCount >= 5) {
                var rnum = Math.floor(Math.random() * 10);
                randomstring += rnum;
                numCount += 1;
            } else {
                // If any of the above criteria fail, go ahead and generate an alpha character from the chars string
                var rnum = Math.floor(Math.random() * chars.length);
                randomstring += chars.substring(rnum,rnum+1);
                charCount += 1;
            }
        }
        $('input[name=apikey]').val(randomstring);
    }
</script>
<!-- END: main -->