<!-- BEGIN: main -->
<h1>File được chia sẻ với bạn!</h1>
<!-- BEGIN: items -->
<div class="panel panel-default">
	<div class="panel-body">
		<!-- BEGIN: loop -->
			<div>
				<h3><a href="{ITEM.download_link}">{ITEM.title}</a></h3>
				<ul class="list-inline">
					<li><em class="fa fa-download">&nbsp;</em> {LANG.download_hits}: {ITEM.download_hits}</li>	
                    <li><em class="fa fa-file-archive-o">&nbsp;</em> {LANG.filesize}: {ITEM.filesize}</li>
				</ul>
			</div>
		<!-- END: loop -->
	</div>
</div>
<!-- END: items -->
<!-- END: main -->