<?php

header('Access-Control-Allow-Origin: http://online.daytot.vn');

echo '<html lang="vi" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
</head>
<body>';
if( defined('NV_IS_USER')){
    $full_name = nv_show_name_user($user_info['first_name'], $user_info['last_name'], $user_info['username']);
    $photo = ( !empty( $user_info['photo'] ))? NV_MY_DOMAIN . NV_BASE_SITEURL . $user_info['photo'] : '';
    
    $type_users = 1;
    if(in_array( NV_IS_GROUP_SUPPORT, $user_info['in_groups'] )){
        $type_users = 2;
    }
    
    echo '
    <script type="text/javascript">
    
      onmessage = function(e) {
       // alert("Send2: logined ' . NV_CURRENTTIME . '");
        console.log("logined ' . NV_CURRENTTIME . '");
      	e.ports[0].postMessage("' . $user_info['userid'] . ',' . $user_info['username'] . ',' . $user_info['email'] . ',' . $full_name . ',' . $photo . ',' . $type_users . '");
        };
    </script>';
}else{
    echo '
    <script type="text/javascript">
      onmessage = function(e) {
       // alert("Send2: Nologin ' . NV_CURRENTTIME . '");
        console.log("Nologin ' . NV_CURRENTTIME . '");
      	e.ports[0].postMessage("");
        };
    </script>';
}

echo '</body></html>';