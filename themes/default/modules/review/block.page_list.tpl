<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-hover table-banggia">
		<thead>
		<tr>
			<th>STT</th>
			<th>Tên tiếng việt</th>
			<th>Tên tiếng anh</th>
			<th>Giá bán lẻ</th>
		</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<th scope="row">{ROW.weight}</th>
			<td><a target="_blank" href="{ROW.link}" title="{ROW.title}">{ROW.title_clean60}</a></td>
			<td><a target="_blank" href="{ROW.link}" title="{ROW.title_english}">{ROW.title_english_clean60}</a></td>
			<td>{ROW.price}</td>
		</tr>
		<!-- END: loop  -->
		</tbody>
	</table>
</div>
<!-- END: main -->