<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUS.,JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );


if( isset( $array_op[1] ) )
{
    $id = 0;
	$array_page = explode( '-', $array_op[1] );
    if( count( $array_page )> 1 ){
        $id = intval( end( $array_page ) );    
    }
	
	$number = strlen( $id ) + 1;
	$alias_url = substr( $array_op[1], 0, -$number );
	//down tu link chia se qua mail - MD5 id file
	if( $id == 0 )
	{
		$data_download = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . " WHERE MD5(id) = " . $db->quote( $array_op[1] ) )->fetch();
	}
	else
	{
		//down neu la chu su huu file
		$data_download = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . " WHERE id = " . $db->quote( $id ) . ' AND user_id=' . $user_info['userid'] )->fetch();
		if( empty( $data_download ) )
		{
			//down neu duoc chia se
			$data_download = $db->query( "SELECT f.* FROM " . NV_PREFIXLANG . '_' . $module_data . "_sharing AS s INNER JOIN " . NV_PREFIXLANG . '_' . $module_data . " AS f ON s.fileid=f.id WHERE s.fileid = " . $db->quote( $id ) . ' AND s.user_id=' . $user_info['userid'] )->fetch();
		}
	}
	if( ! empty( $data_download ) )
	{
		if( file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data_download['fileupload'] ) )
		{

			$upload_dir = 'files';
			$is_zip = false;
			$is_resume = false;
			$max_speed = 0;

			$sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config WHERE config_name='upload_dir' OR config_name='is_zip' OR config_name='is_resume' OR config_name='max_speed'";
			$result = $db->query( $sql );
			while( $row = $result->fetch() )
			{
				if( $row['config_name'] == 'upload_dir' )
				{
					$upload_dir = $row['config_value'];
				}
				elseif( $row['config_name'] == 'is_zip' )
				{
					$is_zip = ( bool )$row['config_value'];
				}
				elseif( $row['config_name'] == 'is_resume' )
				{
					$is_resume = ( bool )$row['config_value'];
				}
				elseif( $row['config_name'] == 'max_speed' )
				{
					$max_speed = ( int )$row['config_value'];
				}
			}

			$file_src = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data_download['fileupload'];
			$file_basename = $file;
			$directory = NV_UPLOADS_REAL_DIR;

			if( $is_zip )
			{
				$upload_dir = NV_UPLOADS_REAL_DIR . '/' . $module_name;
				$subfile = nv_pathinfo_filename( $data_download['fileupload'] );
				$tem_file = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $subfile;

				$file_exists = file_exists( $tem_file );

				if( $file_exists and filemtime( $tem_file ) > NV_CURRENTTIME - 600 )
				{
					$file_src = $tem_file;
					$file_basename = $subfile . '.zip';
					$directory = NV_ROOTDIR . '/' . NV_TEMP_DIR;
				}
				else
				{
					if( $file_exists )
					{
						@nv_deletefile( $tem_file );
					}

					require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';

					$zip = new PclZip( $tem_file );

					$zip->add( $file_src, PCLZIP_OPT_REMOVE_PATH, $upload_dir );

					if( isset( $global_config['site_logo'] ) and ! empty( $global_config['site_logo'] ) and file_exists( NV_ROOTDIR . '/' . $global_config['site_logo'] ) )
					{
						$paths = explode( '/', $global_config['site_logo'] );
						array_pop( $paths );
						$paths = implode( '/', $paths );
						$zip->add( NV_ROOTDIR . '/' . $global_config['site_logo'], PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/' . $paths );
					}

					if( file_exists( NV_ROOTDIR . '/' . NV_DATADIR . '/README.txt' ) )
					{
						$zip->add( NV_ROOTDIR . '/' . NV_DATADIR . '/README.txt', PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/' . NV_DATADIR );
					}

					if( file_exists( $tem_file ) )
					{
						$file_src = $tem_file;
						$file_basename = $subfile . '.zip';
						$directory = NV_ROOTDIR . '/' . NV_TEMP_DIR;
					}
				}
			}

            $download = new NukeViet\Files\Download($file_src, $directory, $file_basename, $is_resume, $max_speed);
			if( $is_zip )
			{
				$mtime = ( $mtime = filemtime( $session_files['fileupload'][$file]['src'] ) ) > 0 ? $mtime : NV_CURRENTTIME;
				$download->set_property( 'mtime', $mtime );
			}
			$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET download_hits=download_hits+1 WHERE id=' . $db->quote( $data_download['id'] ) );
			$download->download_file();
			exit();
		}
		else
		{
			die( 'File not exits' );
		}
	}
}
die( 'Wrong URL' );
