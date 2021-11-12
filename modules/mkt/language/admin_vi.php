<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '18/11/2014, 01:50';
$lang_translator['copyright'] = '@Copyright (C) 2014 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Trang chính';
$lang_module['config'] = 'Cấu hình';
$lang_module['save'] = 'Save';
$lang_module['status'] = 'Trạng thái';
$lang_module['status_1'] = 'Hiển thị';
$lang_module['status_0'] = 'Ẩn';
$lang_module['status_sms_2'] = 'Đã gửi SMS';
$lang_module['status_sms_1'] = 'Hiển thị';
$lang_module['status_sms_0'] = 'Ẩn';
$lang_module['edit'] = 'Sửa';
$lang_module['delete'] = 'Xóa';
$lang_module['search_submit'] = 'Tìm kiếm';

$lang_module['note_num_student'] = 'Ghi chú về số khách hàng';

//from
$lang_module['from'] = 'Dữ liệu từ kênh';
$lang_module['from_name'] = 'Tên kênh dữ liệu';
$lang_module['error_required_from_name'] = 'Tên mối quan hệ không bỏ trống';
$lang_module['from_name_exit_error'] = 'Tên mối quan hệ đã tồn tại';

//config
$lang_module['config_per_page'] = 'Số bản ghi hiển thị trên 1 trang';
$lang_module['config_captcha'] = 'Bật mã bảo vệ khi submit dữ liệu';
$lang_module['config_facebook_page'] = 'Link facebook page để like auto';
$lang_module['config_auto_like'] = 'Cho phép tự động like khi có khách vào site';
$lang_module['config_gmap_lat'] = 'Kinh độ mặc định';
$lang_module['config_gmap_lng'] = 'Vĩ độ mặc định';
$lang_module['ip_mask'] = 'Mask IP';
$lang_module['ip_address'] = 'Địa chỉ IP được phép truy cập kiểm tra hiện trạng phòng';
$lang_module['adminip_note'] = 'Chú ý: nếu nhập IP được phép truy cập, bạn cần nắm được cấu trúc sau: Mỗi số IP được xác định bằng 4 đại lượng A.B.C.D. Nếu số IP của bạn có cả 4 đại lượng A, B, C, D bất biến, hãy chọn giá trị của dòng Mask IP là 255.255.255.255. Nếu chỉ có A, B, C cố định - chọn 255.255.255.xxx v.v...';
$lang_module['config_Google_Maps_API_Key'] = 'Google_Maps_API_Key';
$lang_module['user_permission'] = 'Tên  tài khoản phân quyền'; 
$lang_module['feature'] = 'Chức năng';
$lang_module['permission'] = 'Quyền thao tác';
$lang_module['permission_view'] = 'Xem';
$lang_module['permission_edit'] = 'Sửa';
$lang_module['permission_order'] = 'Sắp xếp';
$lang_module['permission_add'] = 'Thêm mới';
$lang_module['permission_del'] = 'Xóa';
$lang_module['config_sms'] = 'Cấu hình gửi SMS';
$lang_module['config_sms_on'] = 'Bật chức năng gửi tin nhắn';
$lang_module['config_sms_type'] = 'Loaị tin nhắn cấu hình gửi';
$lang_module['config_sms_type_2'] = 'Brandname chăm sóc khách hàng (cần đăng ký trước với esms.vn)';
$lang_module['config_sms_type_4'] = 'Đầu số cố định Notify';
$lang_module['config_sms_type_6'] = 'Tin nhắn chăm sóc khách hàng (không dùng để quảng cáo)';
$lang_module['config_sms_type_8'] = 'Đầu số cố định 10 số (cần đăng ký trước với esms.vn)';
$lang_module['apikey'] = 'API Key';
$lang_module['secretkey'] = 'Secret Key';
$lang_module['brandname'] = 'Brand Name gửi tin';

$lang_module['viewstudent'] = 'Xem danh sách khách hàng';
$lang_module['viewschool'] = 'Xem danh sách trường học';
$lang_module['title'] = "Tiêu đề";
$lang_module['stt'] = "STT";
$lang_module['chonprovince'] = 'Chọn tỉnh/thành phố';
$lang_module['province'] = 'Quản lý tỉnh thành';
$lang_module['editprovince'] = "Sửa chủ đề";
$lang_module['addprovince'] = "Thêm Tỉnh/ thành phố";
$lang_module['province_title'] = 'Tên tỉnh thành';
$lang_module['editprovince'] = "Sửa Tỉnh/ thành phố";
$lang_module['errorIsEmpty'] = "Lỗi! Bạn chưa khai báo ô";
$lang_module['logChangeWeight'] = "Thay đổi thứ tự của chủ đề";
$lang_module['errorChangeWeight'] = "Lỗi! Thứ tự của chủ đề chưa được thay đổi";
$lang_module['errorCatNotExists'] = "Lỗi!Tỉnh mà bạn chọn không tồn tại";
$lang_module['errorWardtNotExists'] = "Lỗi!Xã phường mà bạn chọn không tồn tại";
$lang_module['errorDisNotExists'] = "Lỗi! Quận/ huyện mà bạn chọn không tồn tại";
$lang_module['errorCatYesRow'] = "Lỗi! Tỉnh này đang chứa các huyện. Để xóa nó, bạn cần xóa các huyện đã";
$lang_module['delConfirm'] = "Bạn thực sự muốn xóa? Nếu đồng ý, bạn sẽ không thể phục hồi lại dữ liệu sau này!";
$lang_module['logDelCat'] = "Xóa chủ đề";
$lang_module['province_select'] = "Chọn tỉnh/thành phố";
$lang_module['district_select'] = "Chọn Quận/huyện";
$lang_module['editdistrict'] = 'Sửa quận/huyện';
$lang_module['adddistrict'] = 'Thêm quận huyện';
$lang_module['thuoc'] = 'thuộc';

//khach hang
$lang_module['student'] = 'Danh sách khách hàng';
$lang_module['addcustomer'] = 'Thêm khách hàng mới';
$lang_module['customertype_search'] = 'Lọc theo loại khách hàng';
$lang_module['customertype_search_1'] = 'Khách công ty';
$lang_module['customertype_search_0'] = 'Khách của NPP';
$lang_module['viewstudentonmap'] = 'Xem khách hàng trên bản đồ';
$lang_module['full_name'] = 'Họ và tên';
$lang_module['birthday'] = 'Ngày sinh';
$lang_module['email'] = 'Email';
$lang_module['facebook'] = 'Tài khoản Facebook';
$lang_module['sex'] = 'Giới tính';
$lang_module['sex_0'] = 'Không xác định';
$lang_module['sex_1'] = 'Nam';
$lang_module['sex_2'] = 'Nữ';
$lang_module['address'] = 'Địa chỉ';
$lang_module['mobile'] = 'Điện thoại';
$lang_module['from_by'] = 'Dữ liệu đến từ kênh nào';
$lang_module['from_by'] = 'Dữ liệu đến từ kênh nào';
$lang_module['from_1'] = 'Biết qua website';
$lang_module['from_2'] = 'Bạn bè giới thiệu';
$lang_module['from_3'] = 'Qua tờ tơi';
$lang_module['from_4'] = 'Qua tư vấn';
$lang_module['from_5'] = 'Kênh khác';
$lang_module['customer_status_0'] = 'Mới đăng ký';
$lang_module['customer_status_1'] = 'Dữ liệu chính xác';
$lang_module['customer_status_2'] = 'Sai số';
$lang_module['history_student_pagetitle'] = 'Chăm sóc khách hàng';
$lang_module['status_accept_1'] = 'Đồng ý';
$lang_module['status_accept_0'] = 'Không đồng ý';

$lang_module['eventtype'] = 'Loại dữ liệu';
$lang_module['eventtype_name'] = 'Tên loại dữ liệu';
$lang_module['eventcontent'] = 'Ghi lịch sử chăm sóc khách hàng';
$lang_module['addevent'] = 'Ghi lịch sử';
$lang_module['admin_action'] = 'Người thực hiện';
$lang_module['content_history'] = 'Nội dung';
$lang_module['date'] = 'Ngày';
$lang_module['filter_cancel'] = 'Hủy bỏ';
$lang_module['filter_action'] = 'Lọc dữ liệu';
$lang_module['filter_clear'] = 'Xóa trắng';
$lang_module['filter_enterkey'] = 'Nhập từ khóa tìm kiếm';
$lang_module['status_search'] = 'Tìm theo trạng thái';
$lang_module['province_search'] = 'Tìm theo tỉnh/thành phố';
$lang_module['district_search'] = 'Tìm theo quận/huyện';
$lang_module['search_title'] = 'Từ khóa tìm kiếm';
$lang_module['search'] = 'Tìm kiếm';

$lang_module['copy_data'] = 'Copy dữ liệu từ kênh khác';
$lang_module['import_data'] = 'Nhập dữ liệu Excel';
$lang_module['import_note'] = 'Để nhập dữ liệu từ file Excel, bạn cần <a title="Download file dữ liệu mẫu" href="%1$s"><strong>download file dữ liệu mẫu</strong></a>, sau đó điền đầy đủ dữ liệu mỗi file không quá 2.000 tài khoản sau đó upload lên thư mục <strong>%2$s</strong>';
$lang_module['read_submit'] = 'Đọc dữ liệu';
$lang_module['read_filename']= 'Tên file';
$lang_module['read_filesize']= 'Kích thước'; 
$lang_module['read_complete'] = 'Đọc dữ liệu hoàn thành';

$lang_module['groups'] = 'Các nhóm dữ liệu';
$lang_module['add_block_cat'] = 'Thêm nhóm tin';
$lang_module['edit_block_cat'] = 'Sửa nhóm tin';
$lang_module['name'] = 'Tiêu đề';
$lang_module['keywords'] = 'Từ khóa';
$lang_module['description'] = 'Miêu tả';
$lang_module['content_homeimg'] = 'Hình minh họa';
$lang_module['delete_from_block'] = 'Xóa trường khỏi nhóm tin';
$lang_module['addtoblock'] = 'Thêm trường học vào nhóm dữ liệu';
$lang_module['search_khoangcach'] = 'Nhập thông tin khoảng cách cần đo (m)';
$lang_module['note_maps_center'] = 'Di chuyển vị trí màu đỏ để thay đổi tâm định vị';
$lang_module['search_status'] = 'Lọc theo trạng thái khách hàng';
$lang_module['export_data'] = 'Xuất dữ liệu';
$lang_module['export_student'] = 'Xuất thông tin khách hàng';
$lang_module['khoangcach_tim'] = 'với khoảng cách từ';
$lang_module['export_complete'] = 'Xuất dữ liệu thành công!';

$lang_module['measure'] = 'Thang đo tiềm năng';
$lang_module['error_required_measure_name'] = 'Bạn chưa nhập tên thang đo tiềm năng';
$lang_module['measure_name'] = 'Tên thang đo';
$lang_module['weight'] = 'STT';
$lang_module['measure_select'] = 'Chọn thang điểm';

$lang_module['group_content'] = 'Cấu hình sử dụng chức năng chăm sóc toàn bộ KH ngoài site';
$lang_module['group_addhistory'] = 'Được thêm dữ liệu chăm sóc';

$lang_module['facebook'] = 'Quản lý facebook';
$lang_module['facebook_name'] = 'Tên hiển thị trên facebook';
$lang_module['facebook_uname'] = 'Tên đăng nhập facebook';
$lang_module['facebook_uid'] = 'ID facebook';
$lang_module['facebook_birthday'] = 'Ngày sinh trên facebook';
$lang_module['facebook_sex'] = 'Giới tính trên facebook';
$lang_module['facebook_school_id'] = 'Trường học';
$lang_module['facebook_add_new'] = 'Thêm thông tin Facebook';
$lang_module['facebook_student'] = 'Facebook khách hàng';
$lang_module['input_facebook_student'] = 'Nhập thông tin để tìm kiếm';
$lang_module['edit_time'] = 'Ngày cập nhật';
$lang_module['provinceid'] = 'Tỉnh/TP';
$lang_module['districtid'] = 'Quận/huyện';
$lang_module['event_content'] = 'Lịch sử chăm sóc';
$lang_module['note_data'] = 'Ghi chú dữ liệu';
$lang_module['error_number_phone_format'] = 'Số điện thoại phải bắt đầu bằng số 0 và có từ 10 - 11 chữ số';

$lang_module['remkt_time'] = 'Hẹn gọi lại';
$lang_module['filter_from'] = 'Từ ngày';
$lang_module['filter_to'] = 'Đến ngày';
$lang_module['district'] = 'Quận/huyện';
$lang_module['color_event'] = 'Màu sắc thể hiện';

$lang_module['event'] = 'Các sự kiện';
$lang_module['add_events'] = 'Thêm sự kiện';
$lang_module['title_event'] = 'Tên sự kiện';
$lang_module['contactname'] = 'Người phụ trách';
$lang_module['contactmobile'] = 'SĐT liên hệ';
$lang_module['timeevent'] = 'Thời gian diễn ra sự kiện';
$lang_module['timeclose'] = 'Thời gian khóa đăng ký sự kiện';
$lang_module['addressevent'] = 'Địa chỉ diễn ra sự kiện';
$lang_module['provinceid'] = 'Khu vực diễn ra sự kiện';
$lang_module['description_event'] = 'Mô tả về sự kiện';
$lang_module['hour_select'] = 'Chọn giờ';
$lang_module['minute_select'] = 'Chọn phút';
$lang_module['province_select'] = 'Chọn tỉnh/thành phố';
$lang_module['empty_timeevent'] = 'Bạn cần chọn thời gian diễn ra sự kiện';
$lang_module['empty_title_event'] = 'Bạn chưa nhập tên sự kiện';
$lang_module['empty_addressevent'] = 'Bạn chưa nhập địa chỉ diễn ra sự kiện';
$lang_module['empty_provinceid'] = 'Bạn chưa chọn tỉnh/thành phố diễn ra sự kiện';
$lang_module['active'] = 'Kích hoạt';
$lang_module['empty_contactname'] = 'Bạn cần nhập thông tin người phụ trách';
$lang_module['empty_contactmobile'] = 'Bạn cần nhập thông tin sđt liên hệ';
$lang_module['inactive'] = 'Không kích hoạt';
$lang_module['edit_event'] = 'Sửa sự kiện';
$lang_module['errorsave'] = 'Không có dữ liệu thay đổi!';

$lang_module['smscontent'] = 'Tin nhắn gửi khách hàng';
$lang_module['addtime'] = 'Ngày tạo';
$lang_module['content_note'] = 'Tùy biến dữ liệu SMS theo các thông tin dưới đây';
$lang_module['content'] = 'Nội dung';
$lang_module['timesend'] = 'Gửi lúc';
$lang_module['hoursend'] = 'Trước khi sự kiện diễn ra';
$lang_module['hour'] = 'Giờ';
$lang_module['scenario_add'] = 'Thêm tin nhắn cho sự kiện';
$lang_module['scenario_edit'] = 'Sửa tin nhắn cho sự kiện';
$lang_module['add_scenario_detail'] = 'Thêm 1 tin nhắn mới';
$lang_module['error_required_eventid'] = 'Bạn chưa chọn sự kiện thêm sms';
$lang_module['content_note_eventname'] = 'Tên sự kiện';
$lang_module['content_note_timeevent'] = 'Thời gian bắt đầu sự kiện';
$lang_module['content_note_fullname'] = 'Họ tên khách hàng';
$lang_module['content_note_first_name'] = 'Họ khách hàng';
$lang_module['content_note_last_name'] = 'Tên khách hàng';
$lang_module['content_note_email'] = 'Email khách hàng';
$lang_module['content_note_alias'] = 'Bí danh khách hàng (Anh / Chị)';
$lang_module['content_note_phone'] = 'SĐT khách hàng';
$lang_module['content_note_address'] = 'Địa chỉ hội thảo';
$lang_module['adddraft'] = 'Lưu nháp';
$lang_module['campaign_add'] = 'Tạo kịch bản';
$lang_module['error_required_title'] = 'Lỗi: Bạn chưa nhập tiêu đề';
$lang_module['error_required_content'] = 'Lỗi: Bạn chưa nhập nội dung';
$lang_module['error_required_scenarioid'] = 'Lỗi: Hệ thống không tìm thấy kịch bản cần thêm !';
$lang_module['status_accept'] = 'Trạng thái cuộc gọi';
$lang_module['title_note'] = 'Chỉ hiệu lực với Notification app';
$lang_module['sendusers'] = 'Gửi cho NPP';