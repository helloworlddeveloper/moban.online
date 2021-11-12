<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 14 Mar 2017 10:56:01 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_translator['author'] = 'EDUSGROUP.JSC (thangbv@edus.vn)';
$lang_translator['createdate'] = '24/12/2014, 06:56';
$lang_translator['copyright'] = '@Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Trang chính';
$lang_module['teacher'] = 'Quản lý giáo viên';
$lang_module['subject'] = 'Quản lý môn học';
$lang_module['config'] = 'Cấu hình';
$lang_module['save'] = 'Save';
$lang_module['edit'] = 'Sửa';
$lang_module['delete'] = 'Xóa';
$lang_module['alias'] = 'Alias';
$lang_module['edit_time'] = 'Cập nhật';
$lang_module['status'] = 'Trạng thái';
$lang_module['status_0'] = 'Ẩn';
$lang_module['status_1'] = 'Hiển thị';
$lang_module['icon'] = 'Icon minh họa';
$lang_module['description'] = 'Giới thiệu ngắn';

//Lang for function subject
$lang_module['weight'] = 'STT';
$lang_module['subject_name'] = 'Môn học';
$lang_module['error_required_subject_name'] = 'Lỗi: bạn cần nhập tên môn học';
$lang_module['color_event'] = 'Màu sắc text hiển thị';
//Lang for function class
$lang_module['class'] = 'Quản lý lớp học';
$lang_module['number'] = 'STT';
$lang_module['class_name'] = 'Tên lớp';
$lang_module['class_name_money'] = 'Học phí mặc định';
$lang_module['error_required_class_name'] = 'Lỗi: bạn cần nhập dữ liệu cho tên lớp';

//Lang for function teacher
$lang_module['subject_id'] = 'Dạy môn';
$lang_module['teacher_name'] = 'Tên giáo viên';
$lang_module['teacher_hometext'] = 'Giới thiệu ngắn';
$lang_module['teacher_infotext'] = 'Giới thiệu chi tiết';
$lang_module['avatar'] = 'Hình đại diện';
$lang_module['teacher_phone'] = 'Điện thoại liên hệ';
$lang_module['teacher_address'] = 'Địa chỉ liên hệ';
$lang_module['teacher_email'] = 'Email liên hệ';
$lang_module['error_required_teacher_name'] = 'Lỗi: bạn cần nhập dữ liệu cho Tên giáo viên';
$lang_module['error_required_teacher_hometext'] = 'Lỗi: bạn cần nhập dữ liệu cho Giới thiệu ngắn';
$lang_module['facebooklink'] = 'Link facebook';
$lang_module['hometext'] = 'Nội dung chi tiết';
$lang_module['addnew_teacher'] = 'Thêm giáo viên mới';

$lang_module['list_subject'] = 'Danh sách môn học';
$lang_module['list_subject_study'] = 'Môn giảng dạy';
$lang_module['listtag'] = 'Thẻ';
//Lang for function tag
$lang_module['tag'] = 'Quản lý thẻ';
$lang_module['tag_name'] = 'Tên thẻ';
$lang_module['tag_icon'] = 'Icon';
$lang_module['error_required_tag_name'] = 'Lỗi: bạn cần nhập dữ liệu cho Tên thẻ';
$lang_module['error_required_tag_icon'] = 'Lỗi: bạn cần nhập dữ liệu cho Icon';

$lang_module['error_required_numviewtime'] = 'Số lần xem phải lớn hơn 0!';
$lang_module['khoahoc_manage'] = 'Quản lý khóa học';
$lang_module['add_khoahoc'] = 'Thêm khóa học';
$lang_module['edit_khoahoc'] = 'Sửa khóa học';
$lang_module['filter_action'] = "Lọc dữ liệu";
$lang_module['filter_cancel'] = "Hủy bỏ";
$lang_module['filter_clear'] = "Xóa trắng";
$lang_module['filter_order_by'] = "Sắp xếp theo %s thứ tự";
$lang_module['filter_all_class'] = "- Tất cả các lớp -";
$lang_module['filter_all_subject'] = "- Tất cả các môn -";
$lang_module['filter_enterkey'] = "Nhập từ khóa";
$lang_module['title_khoahoc'] = 'Tên khóa học';
$lang_module['subject_of_khoahoc'] = 'Môn học';
$lang_module['teacher_of_khoahoc'] = 'Giáo viên dạy';
$lang_module['class_of_khoahoc'] = 'Khóa học cho lớp';
$lang_module['titleseo'] = 'Tiêu đề  cho SEO';
$lang_module['numlession'] = 'Số bài học trong khóa';
$lang_module['numviewtime'] = 'Số lần được xem bài giảng';
$lang_module['price'] = 'Giá cả khóa';
$lang_module['images'] = 'Ảnh minh họa';
$lang_module['timestudy'] = 'Ngày khai giảng';
$lang_module['timeend'] = 'Thời gian kết thúc';
$lang_module['isvip'] = 'Khóa học VIP';
$lang_module['numview'] = 'Lượt xem';
$lang_module['numlike'] = 'Like facebook';
$lang_module['numbuy'] = 'Lượt mua';
$lang_module['isfreetrial'] = 'Học thử miễn phí';
$lang_module['error_required_khoahoc_name'] = 'Lỗi: bạn chưa nhập tên khóa học';
$lang_module['error_required_teacherid'] = 'Lỗi: bạn chưa chọn giáo viên cho khóa học';
$lang_module['error_required_khoahoc_image'] = 'Lỗi: bạn chưa chọn ảnh minh họa cho khóa học';
$lang_module['error_required_khoahoc_description'] = 'Lỗi: bạn chưa chọn giới thiệu ngắn cho khóa học';
$lang_module['error_required_khoahoc_subjectid'] = 'Lỗi: bạn chưa chọn môn học của khóa học';
$lang_module['error_required_khoahoc_classid'] = 'Lỗi: bạn chưa chọn lớp học của khóa học';
$lang_module['error_delete_khoahoc'] = 'Lỗi: Khóa học này đã có người đăng ký nên không thể xóa!';
$lang_module['addtime'] = 'Ngày tạo';

$lang_module['filter_err_submit'] = 'Chọn ít nhất một dữ kiện để tìm kiếm'; 
$lang_module['qlbaihoc'] = 'Quản lý bài học';
$lang_module['thembaihoc'] = 'Thêm bài học';
$lang_module['thembaihocchokhoahoc'] = 'Thêm bài học cho';
$lang_module['title_baihoc'] = 'Tên bài học';
$lang_module['image_baihoc'] = 'Hình minh họa cho bài học';
$lang_module['add_morevideo'] = 'Thêm 1 video';
$lang_module['list_video_baihoc'] = 'Danh sách video bài học';
$lang_module['video_path'] = 'Url bài học';
$lang_module['video_title'] = 'Tên cho video bài học';
$lang_module['fileaddtack'] = 'Tài liệu đính kèm';
$lang_module['price_baihoc'] = 'Giá bài học';
$lang_module['timephathanh'] = 'Phát hành';
$lang_module['error_required_timephathanh'] = 'Bạn chưa chọn thời gian phát hành';
$lang_module['error_required_baihoc_name'] = 'Lỗi: Bạn chưa nhập tên bài học';
$lang_module['error_required_baihoc_description'] = 'Lỗi: Bạn chưa nhập giới thiệu cho bài học';
$lang_module['edit_baihoc'] = 'Sửa bài học';
$lang_module['error_required_list_video'] = 'Lỗi: Chưa có video bài giảng!';
$lang_module['from'] = 'Từ';
$lang_module['to'] = 'Đến';

$lang_module['voucher'] = 'Mã giảm giá';
$lang_module['voucher_name'] = 'Tên chương trình';
$lang_module['addnew_voucher'] = 'Thêm Voucher mới';
$lang_module['allowfor'] = 'Áp dụng cho khóa học';
$lang_module['totalvoucher'] = 'Số mã tạo';
$lang_module['timeallow'] = 'Thời gian áp dụng';
$lang_module['error_required_voucher_name'] = 'Bạn chưa nhập tên chương trình Voucher';
$lang_module['voucher_of_khoahoc'] = 'Nhập tên khóa học hoặc bỏ trống';
$lang_module['statuscode_0'] = 'Chưa dùng';
$lang_module['statuscode_1'] = 'Đã dùng';
$lang_module['code_voucher'] = 'Mã Voucher';
$lang_module['timeuse'] = 'TG sử dụng';
$lang_module['userid'] = 'TK sử dụng';
$lang_module['buyhistoryid'] = 'Khóa học dùng mã';

$lang_module['groups_khoahoc'] = 'Quản lý nhóm khóa học';
$lang_module['add_block_cat'] = 'Thêm nhóm khóa học';
$lang_module['edit_block_cat'] = 'Sửa nhóm khóa học';
$lang_module['name'] = 'Tiêu đề';
$lang_module['no_name'] = 'Không có tiêu đề';
$lang_module['titlesite'] = 'Tùy chỉnh Tiêu đề site';
$lang_module['keywords'] = 'Từ khóa';
$lang_module['content_homeimg'] = 'Hình minh họa';
$lang_module['adddefaultblock'] = 'Chọn mặc định khi tạo bài viết';
$lang_module['numlinks'] = 'Số liên kết';
$lang_module['block'] = 'Quản lý block';
$lang_module['content_block'] = 'Thêm vào nhóm';
$lang_module['delete_from_block'] = 'Xóa khóa học khỏi block';


$lang_module['setting'] = 'Cấu hình module';
$lang_module['setting_indexfile'] = 'Phương án thể hiện trang chủ';
$lang_module['setting_homesite'] = 'Kích thước của hình tại trang chủ';
$lang_module['setting_thumbblock'] = 'Kích thước của hình tại các block ';
$lang_module['setting_imagefull'] = 'Kích thước của hình dưới phần mở đầu bài viết ';
$lang_module['setting_per_page'] = 'Số bài viết được hiển thị cùng với phần giới thiệu ngắn gọn trên một trang';
$lang_module['setting_st_links'] = 'Số bài viết chỉ hiển thị link';
$lang_module['setting_idf_df'] = 'Mặc định';
$lang_module['setting_copyright'] = 'Nội dung hiển thị nếu bài viết có lựa chọn giữ bản quyền bài viết';
$lang_module['setting_view'] = 'Cấu hình hiển thị';
$lang_module['setting_post'] = 'Cấu hình đăng bài';
$lang_module['numview_guest'] = 'Lượt xem bài giảng không cần đăng nhập';
$lang_module['setting_alias_lower'] = 'Chuyển Liên kết tĩnh về chữ thường khi tạo mới';

$lang_module['setting_auto_tags'] = 'Tự động tạo từ khóa cho khóa học nếu không nhập từ khóa lúc đăng bài';
$lang_module['viewcat_page'] = 'Cách thể hiện Chuyên mục';
$lang_module['viewcat_page_new'] = 'danh sách, mới lên trên';
$lang_module['viewcat_page_old'] = 'danh sách, cũ lên trên';
$lang_module['viewcat_main_left'] = 'chuyên mục, tin khác nằm bên trái';
$lang_module['viewcat_main_right'] = 'chuyên mục,tin khác nằm bên phải';
$lang_module['viewcat_main_bottom'] = 'chuyên mục,tin khác nằm bên dưới';
$lang_module['viewcat_two_column'] = 'chuyên mục thành 2 cột';
$lang_module['viewcat_grid_new'] = 'theo lưới, mới lên trên';
$lang_module['viewcat_grid_old'] = 'theo lưới, cũ lên trên';
$lang_module['viewcat_none'] = 'không hiển thị';
$lang_module['no_allowed_rating'] = 'Không hiển thị';
$lang_module['showhometext'] = 'Hiển thị phần Giới thiệu ngắn gọn khi xem bài viết';
$lang_module['show_no_image'] = 'Hiển thị ảnh No-Image nếu không bài viết không có hình minh họa';
$lang_module['facebookAppID'] = 'Facebook App ID';
$lang_module['facebookAppIDNote'] = ' (Có dạng 1419186468293063, <a href="http://wiki.nukeviet.vn/nukeviet:admin:news:facebookapi" target="_blank">xem chi tiết</a>)';
$lang_module['socialbutton'] = 'Hiển thị các công cụ Like facebook, G+, Twitter khi xem bài viết';
$lang_module['allowed_rating_point'] = 'Hiển thị đánh giá bài viết trên google nếu bài viết có số điểm';
$lang_module['setting_videostreaming'] = 'Trình chiếu video streaming';
$lang_module['videostreaming_option'] = 'Chọn loại';
$lang_module['videostreaming_option_0'] = 'Chạy thông thường qua server';
$lang_module['videostreaming_option_1'] = 'Chạy thông qua Wowza Streaming';
$lang_module['server_streaming'] = 'Địa chỉ server Wowza Streaming';

$lang_module['content_tag'] = 'Các tag cho bài viết';
$lang_module['content_tag_note'] = 'Để tạo tự động, hãy copy toàn bộ nội dung bài viết vào ô trống dưới đây và bấm';
$lang_module['timeamount'] = 'Thời lượng bài giảng';
$lang_module['minute'] = 'phút';

//tags
$lang_module['tags'] = 'Quản lý Tags';
$lang_module['add_tags'] = 'Thêm Tags';
$lang_module['edit_tags'] = 'Sửa Tags';
$lang_module['tags_alias'] = 'Lọc bỏ dấu tiếng việt, các ký tự khác a-z, 0-9, - trong Liên kết tĩnh của tags';
$lang_module['alias_search'] = 'Để hiển thị các tags khác, bạn dùng chức năng tìm kiếm để hiển thị nhiều kết quả hơn';
$lang_module['tags_all_link'] = 'Chế độ xem các tags chưa có mô tả đang được kích hoạt, nhấp vào đây để xem tất cả các tags';
$lang_module['tags_no_description'] = 'Chưa có mô tả';
$lang_module['search_key'] = 'Từ khóa tìm kiếm';
$lang_module['search'] = 'Tìm kiếm';
$lang_module['search_note'] = 'Từ khóa tìm kiếm không ít hơn 2 ký tự, không lớn hơn 64 ký tự, không dùng các mã html';
$lang_module['emailmarketing'] = 'Tạo mail template';
$lang_module['title_send_mail_baihoc'] = 'Bài học <b>%s</b> đang phát hành.';
$lang_module['send_mail_baihoc_title'] = 'Đang phát hành bài giảng: ';

$lang_module['review'] = 'Quản lý đánh giá';
$lang_module['reviewcontent'] = 'Nội dung';
$lang_module['review_0'] = 'Ẩn';
$lang_module['review_1'] = 'Hiện';
$lang_module['requirewatch'] = 'NPP trực tiếp để xem khóa học';