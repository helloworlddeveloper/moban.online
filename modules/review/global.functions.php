<?php


$array_images = array( 'gif', 'jpg', 'jpeg', 'pjpeg', 'png', 'bmp', 'ico' );
$array_flash = array( 'swf', 'swc', 'flv' );
$array_archives = array( 'rar', 'zip', 'tar' );
$array_documents = array( 'doc', 'xls', 'chm', 'pdf', 'docx', 'xlsx' );
$array_video = array( 'flv', 'mp4', 'm4p', 'm4v', 'avi', 'mov' );

/**
 * nv_getFileInfo()
 *
 * @param mixed $pathimg
 * @param mixed $file
 * @return
 */
function nv_getFileInfo($pathimg, $file)
{
    global $array_images, $array_flash, $array_archives, $array_documents, $array_video;

    clearstatcache();

    unset($matches);
    preg_match("/([a-zA-Z0-9\.\-\_\\s\(\)]+)\.([a-zA-Z0-9]+)$/", $file, $matches);

    $info = array();
    $info['name'] = $file;
    if (isset($file{17})) {
        $info['name'] = substr($matches[1], 0, (18 - strlen($matches[2]))) . '...' . $matches[2];
    }

    $info['ext'] = $matches[2];
    $info['type'] = 'file';

    $stat = @stat(NV_ROOTDIR . '/' . $pathimg . '/' . $file);
    $info['filesize'] = $stat['size'];

    $ext = strtolower($matches[2]);

    if (in_array($ext, $array_images)) {
        $size = @getimagesize(NV_ROOTDIR . '/' . $pathimg . '/' . $file);
        $info['type'] = 'image';
        $info['srcwidth'] = intval($size[0]);
        $info['srcheight'] = intval($size[1]);

    } elseif (in_array($ext, $array_flash)) {
        $info['type'] = 'flash';
        if ($matches[2] == 'swf') {
            $size = @getimagesize(NV_ROOTDIR . '/' . $pathimg . '/' . $file);
            if (isset($size, $size[0], $size[1])) {
                $info['size'] = $size[0] . '|' . $size[1];
            }
        }
    } elseif (in_array($ext, $array_archives)) {
        $info['type'] = 'zip';
    } elseif (in_array($ext, $array_documents)) {
        $info['type'] = 'file';
    } elseif (in_array($ext, $array_video)) {
        $info['type'] = 'video';
    }

    $info['userid'] = 0;
    $info['mtime'] = $stat['mtime'];

    return $info;
}