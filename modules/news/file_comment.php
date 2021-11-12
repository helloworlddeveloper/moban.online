<?php


$array_comment[] = array(
    'name' => 'Nguyễn Thuỳ Linh',
    'content' => 'Cho hỏi là cách đăng ký đại lý thì như thế nào ah?!'
);

$array_comment[] = array(
    'name' => 'Thu Thảo',
    'content' => 'Mình gửi đơn đăng ký vào website rồi ạ.'
);

$array_comment[] = array(
    'name' => 'xeko Nguyễn',
    'content' => 'Điều kiện đăng ký đại lý như nào vậy ad ơi?'
);

$array_comment[] = array(
    'name' => 'Trịnh Thu Thuỷ',
    'content' => 'Sản phẩm tốt quá. Hi vọng sẽ sớm được hợp tác cùng GM WHITE'
);

$array_comment[] = array(
    'name' => 'bích thuyến',
    'content' => 'Bao giờ duyệt xong đơn đăng ký báo cho mình với nhé.'
);

$array_comment[] = array(
    'name' => 'Hà giang Minh',
    'content' => 'Cần bao nhiêu tiền để làm đại lý vậy?.'
);

$array_comment[] = array(
    'name' => 'golest wing',
    'content' => 'Mình ở Thái Nguyên muốn đăng ký thì làm thế nào ah?'
);

$array_comment[] = array(
    'name' => 'Chiến Binh nữ Sinh',
    'content' => 'Mình đã đăng ký rồi ad nhé'
);
$array_comment[] = array(
    'name' => 'alex Nguyễn',
    'content' => 'Cho mình hỏi chính sách làm đại lý độc quyền tại Hưng Yên'
);
$array_comment[] = array(
    'name' => 'Trương Nguyen',
    'content' => 'Mình là sinh viên có làm CTV được không ạ'
);
$array_comment[] = array(
    'name' => 'HOA QUA NHAP KHAU',
    'content' => 'Quan tâm.'
);
$array_comment[] = array(
    'name' => 'binh trong',
    'content' => 'Cách hợp tác như nào vậy shop?'
);
$array_comment[] = array(
    'name' => 'Hưng nguyễn',
    'content' => 'cần tìm Tổng đại lý GM WHITE tại Tây Nguyên, Đồng nai: 0981565675'
);
$array_comment[] = array(
    'name' => 'HƯƠNG GIANG',
    'content' => 'Mình muốn nhờ shop tư vấn cách mở cửa hàng độc quyền ạ'
);
$array_comment[] = array(
    'name' => 'Nguyễn Giang',
    'content' => 'em thấy quảng cáo trên tivi thì phải.'
);
$array_comment[] = array(
    'name' => 'Nguyên Trần',
    'content' => '4.0 Mỹ phẩm liệu có thật không, công nghệ của bạn có tốt như bạn nói không?'
);


$area = $module_info['funcs']['detail']['func_id'];
$pid = 0;
$fileupload = '';
$userid = 0;
$status = 1;

shuffle($array_comment);
foreach ($array_comment as $comment ) {
    $time = NV_CURRENTTIME - rand(2000 , 10000);
    $module = $module_name;
    $email = change_alias( $comment['name'] ) . '@gmail.com';
    $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_comment (
                            module, area, id, pid, content, attach, post_time, userid, post_name, post_email, post_ip, status
                        ) VALUES (
                            :module, ' . $area . ', ' . $commentid . ', ' . $pid . ', :content, :attach, ' . $time . ', ' . $userid . ', :post_name, :post_email,
                            :post_ip, ' . $status . '
                        )';
    $data_insert = array();
    $data_insert['module'] = $module;
    $data_insert['content'] = $comment['content'];
    $data_insert['attach'] = $fileupload;
    $data_insert['post_name'] = $comment['name'];
    $data_insert['post_email'] = $email;
    $data_insert['post_ip'] = NV_CLIENT_IP;
    $new_id = $db->insert_id($_sql, 'cid', $data_insert);
}

