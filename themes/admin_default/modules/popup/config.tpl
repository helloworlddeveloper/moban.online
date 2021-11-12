<!-- BEGIN: main -->
<form action="{ACTION}" method="post">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td>{LANG.active}</td>
				<td><input type="checkbox" name="active" value="1" {ACTIVE} /></td>
			</tr>
			<tr>
				<td>{LANG.timer_open}</td>
				<td><input type="text" class="form-control" style="width:300px;float:left" name="timer_open" value="{DATA.timer_open}" /> {LANG.second}</td>
			</tr>
			<tr>
				<td>{LANG.timer_close}</td>
				<td><input class="form-control" style="width:300px;float:left" type="text" name="timer_close" value="{DATA.timer_close}" /> {LANG.second}</td>
			</tr>
			<tr>
				<td><input class="btn btn-primary" type="submit" value="{LANG.save}" name="save" /></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->
