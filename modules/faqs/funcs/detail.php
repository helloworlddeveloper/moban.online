<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:32:04 AM
 */

if( ! defined( 'NV_IS_MOD_FAQS' ) ) die( 'Stop!!!' );
//ALTER TABLE `nv4_vi_faqs_question` ADD `view_hits` INT(11) NOT NULL AFTER `number`;
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$qid = 0;

if( isset( $array_op[1] ) and preg_match( '/^([a-zA-Z0-9\-\_]+)\-([\d]+)$/', $array_op[1], $matches ) )
{
	$qid = $matches[2];
	$alias = $matches[0];
}

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
elseif( ! nv_function_exists( 'nv_aleditor' ) and file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js' ) )
{
	define( 'NV_EDITOR', true );
	define( 'NV_IS_CKEDITOR', true );
	$my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

	function nv_aleditor( $textareaname, $width = '100%', $height = '450px', $val = '' )
	{
		$return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
		$return .= "<script type=\"text/javascript\">
        CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {width: '" . $width . "',height: '" . $height . "',});
        </script>";
		return $return;
	}
}

$show_captcha = false;
if( ! defined( 'NV_IS_MODADMIN' ) and ( $arr_config['is_captcha'] == 2 or $arr_config['is_captcha'] == 3 ) )
{
	$show_captcha = true;
}

if( $nv_Request->isset_request( 'bc', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_answer WHERE id=" . $id;
	$result = $db->query( $query );
	$ro_a = $result->fetch();

	if( ! empty( $ro_a ) )
	{
		$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_question SET most=" . $ro_a['id'] . " WHERE qid=" . $ro_a['qid'] );
		if( $arr_config['is_mark'] == 1 )
		{
			$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_note SET mark=mark+" . $arr_config['mark_an_cho'] . " , choanser= choanser+1 WHERE userid=" . $user_info['userid'] );
			$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_note SET mark=mark+" . $arr_config['mark_an_most'] . " , mostanser= mostanser+1 WHERE userid=" . $ro_a['userid'] );
		}

		die( 'OK' );
	}
	else
	{
		die( $lang_module['bc_chon_no'] );
	}

}
$hoten = $email = $noidung = $error = '';

function nv_return_post_result( $message )
{
	die( '<script type="text/javascript">parent.nv_complete(\'' . $message . '\');</script>' );
}

if( $nv_Request->get_string( 'type', 'post', '' ) == "sendemail" )
{
	$fcode = $nv_Request->get_title( 'fcode', 'post', '' );
	$noidung = $nv_Request->get_string( 'answer', 'post', '' );
	$id = $nv_Request->get_int( 'id', 'post', '' );
	$email = $nv_Request->get_string( 'email', 'post', '' );
	$hoten = $nv_Request->get_string( 'full_name', 'post', '' );

	$check_valid_email = nv_check_valid_email( $email );

	$query = $db->query( "SELECT id, userid FROM " . NV_PREFIXLANG . "_" . $module_data . "_answer where qid=" . $id );
	$news_contents = $query->fetch();

	if( ! nv_capcha_txt( $fcode ) and $show_captcha )
	{
		$error = $lang_module['error_captcha'];
	}
	elseif( $news_contents['id'] > 0 )
	{
		$error = $lang_module['error_ansss'];
	}
	elseif( empty( $noidung ) )
	{
		$error = $lang_module['error_an'];
	}

	if( empty( $error ) )
	{
		if( ! file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name ) )
		{
			nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name );
		}

		$file_name = '';

		$status = ( ! defined( 'NV_IS_MODADMIN' ) and $arr_config['duyetqu'] == 1 ) ? 0 : 1;

		$file_name = '';
		$query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_answer (id , qid, userid ,answer , cus_name ,cus_email ,
			 addtime , file ,status)
			 VALUES (NULL," . $qid . ",0," . $db->quote( $noidung ) . "," . $db->quote( $hoten ) . ",
			 " . $db->quote( $email ) . ", " . intval( NV_CURRENTTIME ) . ", " . $db->quote( $file_name ) . "," . $status . ")";
		$id_post = $db->query( $query );

		if( $id_post )
		{
			$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_question SET number=number+1, answer = 1 WHERE qid=" . $qid );

			$lang = ! $status ? $lang_module['answer_waiting'] : $lang_module['answer_ok'];
			$contents = nv_theme_info( $lang, 'info', $client_info['selfurl'], 3 );

			include ( NV_ROOTDIR . "/includes/header.php" );
			echo nv_site_theme( $contents );
			include ( NV_ROOTDIR . "/includes/footer.php" );
			die();
		}
		else
		{
			die( nv_return_post_result( $lang_module['no_send'] ) );
		}
	}
}

if( $qid != 0 )
{
	$listqid = $nv_Request->get_string( 'listqid', 'session', '' );
	$listqid = ! empty( $listqid ) ? unserialize( $listqid ) : array();

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_question WHERE qid =" . $qid;
	$result = $db->query( $sql );

	if( $result->rowCount() > 0 )
	{
		$xtpl = new XTemplate( "detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'themes', $module_info['template'] );

		if( $show_captcha )
		{
			$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		}

		if( ! empty( $user_info ) )
		{
			$xtpl->assign( 'full_name', ! empty( $user_info['full_name'] ) ? $user_info['full_name'] : $user_info['username'] );
			$xtpl->assign( 'email', $user_info['email'] );
		}
		$xtpl->assign( 'LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=question" );

		$htmlbodytext = '';
		if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
		{
			$htmlbodytext .= nv_aleditor( 'answer', '99%', '150px', $noidung );
		}
		else
		{
			$htmlbodytext .= "<textarea style=\"width:70%;height:150px\" name=\"answer\" id=\"answer\">" . $noidung . "</textarea>";
		}
		$xtpl->assign( 'HTML_ND', $htmlbodytext );

		$xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=detail&qid=" . $qid );
		while( $rows = $result->fetch() )
		{
			if( ! in_array( $rows['qid'], $listqid ) )
			{
				$listqid[] = $rows['qid'];
				$listqid = serialize( $listqid );
				$nv_Request->set_Session( 'listqid', $listqid );

				$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_question SET view_hits=view_hits+1 WHERE qid=' . $rows['qid'];
				$db->query( $sql );
				++$rows['view_hits'];
			}

			$rows['addtime'] = nv_date( 'd.m.Y', $rows['addtime'] );

			$i = 0;

			$xtpl->assign( 'ROW', $rows );

			if( $rows['showmail'] == 1 )
			{
				$xtpl->parse( 'main.email' );
			}

			$s = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_answer WHERE status=1 AND qid =" . $qid;
			$re = $db->query( $s );

			if( $re->rowCount() > 0 )
			{
				while( $ro = $re->fetch() )
				{
					$i = $i + 1;

					$ro['addtime'] = nv_date( 'd.m.Y H:i', $ro['addtime'] );

					$xtpl->assign( 'LOOP', $ro );
					if( $ro['file'] != '' )
					{
						$xtpl->parse( 'main.an.loop.file' );
					}
					if( $rows['most'] == $ro['id'] )
					{
						$xtpl->parse( 'main.an.loop.bchn' );
					}
					elseif( $rows['most'] == 0 && $rows['userid'] == $user_info['userid'] )
					{
						if( defined( 'NV_IS_USER' ) )
						{
							$xtpl->parse( 'main.an.loop.bc' );
						}
					}
					$xtpl->parse( 'main.an.loop' );
				}

				$xtpl->assign( 'number', $i );
				$xtpl->parse( 'main.an' );
			}

		}
		//$sqls = "SELECT id, userid FROM " . NV_PREFIXLANG . "_" . $module_data . "_answer where userid =" . $user_info['userid'] . " AND qid=" . $qid;
		//$result = $db->query($sqls);

		if( nv_user_in_groups( $arr_config['who_an'] ) )
		{
			if( $show_captcha )
			{
				$xtpl->parse( 'main.anss.captcha' );
			}

			if( ! empty( $error ) )
			{
				$xtpl->assign( 'ERROR', $error );
				$xtpl->parse( 'main.anss.error' );
			}

			$xtpl->parse( 'main.anss' );
		}

		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_question WHERE qid !=" . $qid . " LIMIT 10";
		$result = $db->query( $sql );
		$num = $result->rowCount();

		if( $num )
		{
			while( $rows = $result->fetch() )
			{
				$rows['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=detail/" . $rows['alias'];
				$xtpl->assign( 'LOOP', $rows );
				$xtpl->parse( 'main.othernews.loops' );
			}
			$xtpl->parse( 'main.othernews' );
		}

		if( nv_user_in_groups( $arr_config['who_view'] ) )
		{
			$xtpl->parse( 'main.who_view' );
		}

		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
	else
	{
		$contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['noque'] . '</span>';
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';