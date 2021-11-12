<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */
if(! defined('NV_MAINFILE'))
    die('Stop!!!');
if(! nv_function_exists('nv_youtube_blocks'))
{
    function nv_block_config_youtube_blocks($module, $data_block, $lang_block)
    {
        global $db, $language_array, $module_array_cat, $site_mods;
        $html = "";
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['idchanel'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_idchanel" value="' . $data_block['idchanel'] . '"></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['apiyoutube'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_apiyoutube" value="' . $data_block['apiyoutube'] . '"></div>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['numvideo'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_numvideo" value="' . $data_block['numvideo'] . '"></div>';
        $html .= '</div>';

        return $html;
    }
    function nv_block_config_youtube_blocks_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['idchanel'] = $nv_Request->get_title('config_idchanel', 'post', 0);
        $return['config']['apiyoutube'] = $nv_Request->get_title('config_apiyoutube', 'post', 0);
        $return['config']['numvideo'] = $nv_Request->get_int('config_numvideo', 'post', 0);
        return $return;
    }
    function nv_youtube_blocks($block_config)
    {
        global $module_info, $lang_module, $global_config, $site_mods;
        $module = $block_config['module'];

        //set_error_handler('videoListDisplayError');

        //To try without API key: $video_list = json_decode(file_get_contents('example.json'));
        $url_send = 'https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=' . $block_config['idchanel'] . '&maxResults=' . $block_config['numvideo'] . '&key=' . $block_config['apiyoutube'];
        $video_list = json_decode(file_get_contents($url_send), true);

        if(! empty($video_list))
        {
            if(file_exists(NV_ROOTDIR . "/themes/" . $module_info['template'] . "/blocks/block_youtube.tpl"))
            {
                $block_theme = $module_info['template'];
            }
            elseif(file_exists(NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/blocks/block_youtube.tpl"))
            {
                $block_theme = $global_config['site_theme'];
            }
            else
            {
                $block_theme = "default";
            }
            $i = 0;
            $xtpl = new XTemplate("block_youtube.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/blocks");
            $xtpl->assign('channelId', $block_config['idchanel']);
            foreach($video_list['items'] as $item)
            {
                //Embed video
                if(isset($item['id']['videoId']))
                {
                    $xtpl->assign('ITEM', $item);
                    $xtpl->parse('main.loopvideo');
                }
                elseif(isset($item['id']['playlistId']))
                {
                    $xtpl->assign('ITEM', $item);
                    $xtpl->parse('main.loop_playlist_video');

                }
            }
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}
if(defined('NV_SYSTEM'))
{
    $content = nv_youtube_blocks($block_config);
}

?>