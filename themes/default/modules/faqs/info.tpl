<!-- BEGIN: main -->
	<!-- BEGIN: text -->
	<div class="alert alert-{TYPE}">
		<p class="text-center">
			{TEXT} <br /><br />
			<em class="fa fa-spinner fa-spin fa-4x">&nbsp;</em><br /><br />
			<a href="{URL}" title="{LANG.waiting}">{LANG.waiting}</a>
		</p>
	</div>

	<!-- BEGIN: url -->
	<meta http-equiv="refresh" content="{TIME};url={URL}" />
	<!-- END: url -->

	<!-- END: text -->
<!-- END: main -->