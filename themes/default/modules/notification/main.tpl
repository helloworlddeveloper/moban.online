<!-- BEGIN: main -->
<!-- BEGIN: mess -->
<div class="clear"></div>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header">
		<div class="cat-nav">
			{LANG.yourmess}
		</div>
	</div>
	<div class="cat-news clearfix">
		<table class="tab1">
			<thead>
				<tr>
					<td>{LANG.m_title}</td>
					<td>{LANG.m_sendname}</td>
					<td>{LANG.send_time}</td>
					<td>{LANG.send_action}</td>
				</tr>
			</thead>
			<!-- BEGIN: viewingmess -->
			<tbody>
				<tr>
					<td colspan="4">
						<strong>{LANG.noreadmess}</strong>
					</td>
				</tr>
			</tbody>
			<!-- BEGIN: loop1 -->
			<tbody>
				<tr>
					<td><a href="{ROW1.link}" title="{ROW1.title}">{ROW1.title}</a></td>
					<td>{ROW1.send_name}</td>
					<td>{ROW1.send_time}</td>
					<td><a title="{LANG.delmess} {ROW1.title}" href="javascript:void(0);" onclick="nv_del_mess({ROW1.id});"><span class="delicon">&nbsp;</span></a></td>
				</tr>
			</tbody>
			<!-- END: loop1 -->
			<!-- END: viewingmess -->
			<!-- BEGIN: viewedmess -->
			<tbody>
				<tr>
					<td colspan="4">
						<strong>{LANG.readedmess}</strong>
					</td>
				</tr>
			</tbody>
			<!-- BEGIN: loop2 -->
			<tbody>
				<tr>
					<td><a href="{ROW2.link}" title="{ROW2.title}">{ROW2.title}</a></td>
					<td>{ROW2.send_name}</td>
					<td>{ROW2.send_time}</td>
					<td><a title="{LANG.delmess} {ROW2.title}" href="javascript:void(0);" onclick="nv_del_mess({ROW2.id});"><span class="delicon">&nbsp;</span></a></td>
				</tr>
			</tbody>
			<!-- END: loop2 -->
			<!-- END: viewedmess -->
		</table>
	</div>
</div>
<!-- END: mess -->
<!-- BEGIN: generate_page -->
<div class="generate_page">
{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- BEGIN: nomess -->
<div class="infonomess">
	{LANG.nomess}
</div>
<!-- END: nomess -->
<!-- END: main -->