<!-- BEGIN: main -->
<div class="notices">
<ul>
	<!-- BEGIN: row -->
		<li {ROW.class}> 
			<!-- BEGIN: link -->
				{ROW.STT} . <a href="{ROW.link}">{ROW.title} &nbsp; {ROW.html}</a>
			<!-- END: link -->
			<!-- BEGIN: nolink -->
				{ROW.STT} . {ROW.title}&nbsp;{ROW.html}
			<!-- END: nolink -->
			 
		</li>
	<!-- END: row -->
</ul>
</div>
<!-- BEGIN: pages -->

<div class="pages">{generate_page}</div>

<!-- END: pages -->
<!-- END: main -->