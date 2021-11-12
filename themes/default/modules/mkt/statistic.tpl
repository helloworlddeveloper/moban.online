<!-- BEGIN: main -->
    <!-- BEGIN: classes-->
    <div class="table-responsive">
    	<table class="table table-striped table-bordered table-hover">
            <caption>Danh lớp học tại trung tâm</caption>
    		<thead>
    			<tr>
    				<th class="text-center">{LANG.stt}</th>
                    <th class="text-center">Khối lớp</th>
                    <th class="text-center">Môn học</th>
    				<th class="text-center">Giáo viên</th>
    				<th class="text-center">Đang học</th>
    				<th class="text-center">Đã nghỉ</th>
    			</tr>
    		</thead>
    		<tbody>
    			<!-- BEGIN: loop -->
    			<tr>
    				<td class="text-center">{DATA.stt}</td>
                    <td>{DATA.class_name}</td>
                    <td>{DATA.subject_name}</td>
                    <td class="text-left">{DATA.teacher}</td>
    				<td class="text-center">{DATA.total_studying}</td>
    				<td class="text-center">{DATA.total_studied}</td>
    			</tr>
    			<!-- END: loop -->
    		</tbody>
    	</table>
    </div>
    <!-- END: classes-->
    <!-- BEGIN: school-->
    <div class="table-responsive">
    	<table class="table table-striped table-bordered table-hover">
            <caption>Danh sách trường học có học sinh đang theo học tại trung tâm</caption>
    		<thead>
    			<tr>
    				<th class="text-center">{LANG.stt}</th>
                    <th class="text-center">{LANG.khoilop}</th>
                    <th class="text-center">{LANG.district}</th>
    				<th class="text-center">{LANG.school_title}</th>
    				<th class="text-center">{LANG.address}</th>
    				<th class="text-center">{LANG.khoangcach}</th>
    				<th class="text-center">{LANG.total_student}</th>
    			</tr>
    		</thead>
    		<tbody>
    			<!-- BEGIN: loop -->
    			<tr>
    				<td>{DATA.stt}</td>
                    <td>{DATA.schooltype_title}</td>
                    <td>{DATA.district}</td>
                    <td class="text-left">{DATA.school_title}</td>
    				<td>{DATA.address}</td>
    				<td>{DATA.khoangcach}</td>
                    <td class="text-center">{DATA.hocsinh}</td>
    			</tr>
    			<!-- END: loop -->
                <tfoot style="font-weight:bold">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{TOTAL.total_school}</td>
    				<td></td>
    				<td>{TOTAL.khoangcach}</td>
                    <td class="text-center">{TOTAL.total_student}</td>
                </tfoot>
    		</tbody>
    	</table>
    </div>
    <!-- END: school-->
<!-- END: main -->