<!-- BEGIN: main -->
<div style="padding:10px" class="text-center"><strong>{LANG.title_school_list}</strong></div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="text-center">{LANG.stt}</th>
                <th class="text-center">{LANG.student_name}</th>
                <th class="text-center">{LANG.yearold}</th>
                <th class="text-center">{LANG.class}</th>
				<th class="text-center">{LANG.school_title}</th>
				<th class="text-center">{LANG.address}</th>
				<th class="text-center">{LANG.mobile}</th>
                <th class="text-center">{LANG.khoangcach}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{DATA.stt}</td>
                <td>{DATA.student_name}</td>
                <td class="text-center">{DATA.yearold}</td>
                <td class="text-center">{DATA.class}</td>
                <td class="text-left">{DATA.school_name}</td>
				<td>{DATA.address}</td>
				<td>{DATA.mobile}</td>
                <td>{DATA.khoangcach}</td>
			</tr>
			<!-- END: loop -->
            <tfoot style="font-weight:bold">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
				<td>{TOTAL.khoangcach}</td>
            </tfoot>
		</tbody>
	</table>
</div>
<!-- END: main -->