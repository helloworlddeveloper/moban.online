<!-- BEGIN: main -->
<style type="text/css">
    .using-room{
        margin:0 10px; padding: 0 0 0 10px; list-style:nomarl;
    }
    .w250{width:250px}
    table {
        border-collapse: collapse;
        width:100%;
    }
    table, th, td {
        border: 1px solid black;
        text-align:left
    }
</style>
<div style="text-align:center"><strong>Thống kê dữ liệu thu thập ngày {timesearch}</strong></div>
<hr />
<table class="table table-striped table-bordered">
    <tr>
        <th>Dữ liệu học sinh mới</th>
        <td>{STATISTIC.total_new_student}</td>
    </tr>
    <tr>
        <th>Dữ liệu phụ huynh mới</th>
        <td>{STATISTIC.total_new_parent}</td>
    </tr>
    <tr>
        <th>Dữ liệu facebook</th>
        <td>{STATISTIC.total_new_facebook}</td>
    </tr>
</table>  
<!-- BEGIN: data_events-->
<p class="clear">&nbsp;</p>
<div style="text-align:center"><strong>Dữ liệu chăm sóc thống kê trong ngày {timesearch}</strong></div>
<hr />
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Thang đo</th>
            <!-- BEGIN: user-->
            <th style="text-align:center">{admin_name}</th>
            <!-- END: user-->
            <th style="text-align:center">Tổng liên hệ</th>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tr>
        <td>{EVENT.measure_name}</td>
        <!-- BEGIN: user-->
        <td style="text-align:center">{admin_name}</td>
        <!-- END: user-->
        <td style="text-align:center">{EVENT.total}</td>
    </tr>
    <!-- END: loop -->
    <tfoot>
        <tr>
            <th>Tổng dữ liệu chăm sóc</th>
            <!-- BEGIN: usertotal-->
            <th style="text-align:center">{array_sum}</th>
            <!-- END: usertotal-->
            <th style="text-align:center">{total_sum_cham_soc}</th>
        </tr>
    </tfoot>
</table>
<!-- END: data_events-->
<!-- BEGIN: school-->
<p class="clear">&nbsp;</p>
<div style="text-align:center"><strong>Dữ liệu trường học nhập được trong ngày {timesearch}</strong></div>
<hr />
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Người nhập liệu</th>
            <th>Số lượng nhập</th>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tr>
        <td>{SCHOOL.admin_name}</td>
        <td>{SCHOOL.total}</td>
    </tr>
    <!-- END: loop -->
</table>
<!-- END: school-->
<p class="clear">&nbsp;</p> 
<div style="text-align:center"><strong>Thống kê dữ liệu học sinh tại Dạy Tốt ngày {timesearch}</strong></div>
<hr />
<table class="table table-striped table-bordered">
    <tr>
        <th>Tổng học sinh nhập học</th>
        <td>{STATISTIC.total_student_new}</td>
    </tr>
    <tr>
        <th>Tổng học sinh nghỉ học</th>
        <td>{STATISTIC.total_student_nghihoc}</td>
    </tr>
    <tr>
        <th>Tổng học sinh đăng ký học thử</th>
        <td>{STATISTIC.total_student_hocthu}</td>
    </tr>
    <tr>
        <th>Tổng học sinh đăng ký học chính thức</th>
        <td>{STATISTIC.total_student_chinhthuc}</td>
    </tr>
    <tr>
        <th>Tổng học sinh nhập học tính theo môn</th>
        <td>{STATISTIC.total_student_in}</td>
    </tr>
    <tr>
        <th>Tổng học sinh nghỉ học tính theo môn</th>
        <td>{STATISTIC.total_student_out}</td>
    </tr>
    <tr>
        <th>Số ca học thực hiện trong ngày qua điểm danh</th>
        <td>{STATISTIC.total_diemdanh} ca</td>
    </tr>
    <tr>
        <th>Tổng học phí tính đến ngày hôm nay</th>
        <td>{STATISTIC.total_hocphi_thang}</td>
    </tr>
</table>
<!-- BEGIN: tong_hop_tuan-->
<p class="clear">&nbsp;</p> 
<div style="text-align:center"><strong>Thống kê dữ liệu học sinh tại Dạy Tốt tính đến ngày {timesearch}</strong></div>
<hr />
<table class="table table-striped table-bordered">
    <tr>
        <th>Tổng học sinh đã nhập học</th>
        <td>{STATISTIC.total_student_new_to_current}</td>
    </tr>
    <tr>
        <th>Tổng học sinh đã nghỉ học</th>
        <td>{STATISTIC.total_student_nghihoc_to_current}</td>
    </tr>
    <tr>
        <th>Tổng học sinh đã đăng ký học thử</th>
        <td>{STATISTIC.total_student_hocthu_to_current}</td>
    </tr>
    <tr>
        <th>Tổng học sinh đã đăng ký học chính thức</th>
        <td>{STATISTIC.total_student_chinhthuc_to_current}</td>
    </tr>
</table>
<!-- END: tong_hop_tuan-->
<!-- BEGIN: nhan_xet-->
<p class="clear">&nbsp;</p>
<div style="text-align:center"><strong>Dữ liệu nhận xét và gọi điện cho học sinh Dạy Tốt trong ngày {timesearch}</strong></div>
<hr />
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Lớp học</th>
            <th>Ca học</th>
            <th>Học sinh</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th>Người nhập</th>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tr>
        <td class="w250"><strong>{NHAN_XET.classes}</strong></td>
        <td>{NHAN_XET.review_date}</td>
        <td>{NHAN_XET.info_student.fullname}</td>
        <td>{NHAN_XET.note}</td>
        <td>{NHAN_XET.status}</td>
        <td>{NHAN_XET.adminid}</td>
    </tr>
    <!-- END: loop -->
</table>
<!-- END: nhan_xet-->
<!-- BEGIN: eventcontent-->
<p class="clear">&nbsp;</p>
<div style="text-align:center"><strong>Dữ liệu lịch sử chăm sóc trong ngày {timesearch}</strong></div>
<hr />
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Khách hàng</th>
            <th>Người chăm sóc</th>
            <th>Thời điểm</th>
            <th>Nội dung</th>
            <th>Loại sự kiện</th>
        </tr>
    </thead>
    <!-- BEGIN: loop -->
    <tr style="color:{EVENTS.color}">
        <td class="w250"><strong>{EVENTS.info_customer.company}</strong></td>
        <td>{EVENTS.adminid}</td>
        <td>{EVENTS.addtime}</td>
        <td>{EVENTS.content}</td>
        <td>{EVENTS.eventtype}</td>
    </tr>
    <!-- END: loop -->
</table>
<!-- END: eventcontent-->
<!-- END: main -->