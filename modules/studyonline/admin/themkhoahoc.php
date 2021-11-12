<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 20 Mar 2015 02:51:05 GMT
 */

if(!defined('NV_IS_FILE_ADMIN'))
	die('Stop!!!');

if($nv_Request->isset_request('get_alias_title','post'))
{
	$alias = $nv_Request->get_title('get_alias_title','post','');
	$alias = change_alias($alias);
	die($alias);
}
$array_block_cat_module = array();
$id_block_content = array();
$sql = 'SELECT bid, adddefault, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db->query($sql);
while(list($bid_i,$adddefault_i,$title_i) = $result->fetch(3))
{
	$array_block_cat_module[$bid_i] = $title_i;
	if($adddefault_i)
	{
		$id_block_content[] = $bid_i;
	}
}

$row = array();
$array_keywords_old = array();
$error = array();
$row['id'] = $nv_Request->get_int('id','post,get',0);
$row['numviewtime'] = 10;
$page_title = $lang_module['add_khoahoc'];

if($row['id'] > 0)
{
	$row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc WHERE id=' . $row['id'])->fetch();
	if(empty($row))
	{
		Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
		die();
	}

	$_query = $db->query('SELECT tid, keyword FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $row['id'] . ' ORDER BY keyword ASC');
	while($_tmp = $_query->fetch())
	{
		$array_keywords_old[$_tmp['tid']] = $_tmp['keyword'];
	}
	$row['keywords'] = implode(', ',$array_keywords_old);
	$row['keywords_old'] = $row['keywords'];

	$id_block_content = array();
	$sql = 'SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block where id=' . $row['id'];
	$result = $db->query($sql);
	while(list($bid_i) = $result->fetch(3))
	{
		$id_block_content[] = $bid_i;
	}
	$page_title = $lang_module['edit_khoahoc'] . ' ' . $row['title'];
}
else
{
	$row['isvip'] = $row['subjectid'] = $row['classid'] = $row['id'] = $row['timestudy'] = $row['timeend'] = 0;
	$row['isfreetrial'] = $row['status'] = 1;
	$row['teacherid'] = $row['hometext'] = $row['listtag'] = '';
}

if($nv_Request->isset_request('submit','post'))
{
	$row['title'] = $nv_Request->get_title('title','post','');
	$row['image'] = $nv_Request->get_title('image','post','');
	$row['hometext'] = $nv_Request->get_editor('hometext','',NV_ALLOWED_HTML_TAGS);
	$row['description'] = $nv_Request->get_string('description','post','');
	$row['description'] = nv_nl2br($row['description']);
	$row['titleseo'] = $nv_Request->get_title('titleseo','post',$row['title']);
	$row['alias'] = $nv_Request->get_title('alias','post','');
	$row['alias'] = (empty($row['alias'])) ? change_alias($row['title']) : change_alias($row['alias']);
	$row['isvip'] = $nv_Request->get_int('isvip','post',0);
	$row['isfreetrial'] = $nv_Request->get_int('isfreetrial','post',0);
	$row['numlession'] = $nv_Request->get_int('numlession','post',0);
    $row['requirewatch'] = $nv_Request->get_int('requirewatch','post',0);
	$row['numviewtime'] = $nv_Request->get_int('numviewtime','post',0);
	$row['price'] = $nv_Request->get_float('price','post',0);
	$row['timestudy'] = $nv_Request->get_title('timestudy','post','');
	$row['timeend'] = $nv_Request->get_title('timeend','post','');
	$row['subjectid'] = $nv_Request->get_int('subjectid','post',0);
	$row['classid'] = $nv_Request->get_int('classid','post',0);
	$row['teacherid'] = $nv_Request->get_array('teacherid','post',array());
	$row['teacherid'] = implode(',',$row['teacherid']);
	$row['status'] = $nv_Request->get_int('status','post',0);
	$row['listtag'] = implode(',',$nv_Request->get_array('listtag','post',''));

	$row['keywords'] = $nv_Request->get_array('keywords','post','');
	$row['keywords'] = implode(', ',$row['keywords']);

	// Tu dong xac dinh keywords
	if($row['keywords'] == '' and !empty($module_config[$module_name]['auto_tags']))
	{
		$keywords = ($row['hometext'] != '') ? $row['hometext'] : $row['description'];
		$keywords = nv_get_keywords($keywords,100);
		$keywords = explode(',',$keywords);

		// Ưu tiên lọc từ khóa theo các từ khóa đã có trong tags thay vì đọc từ từ điển
		$keywords_return = array();
		foreach($keywords as $keyword_i)
		{
			$sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id where keyword = :keyword');
			$sth->bindParam(':keyword',$keyword_i,PDO::PARAM_STR);
			$sth->execute();
			if($sth->fetchColumn())
			{
				$keywords_return[] = $keyword_i;
				if(sizeof($keywords_return) > 20)
				{
					break;
				}
			}
		}

		if(sizeof($keywords_return) < 20)
		{
			foreach($keywords as $keyword_i)
			{
				if(!in_array($keyword_i,$keywords_return))
				{
					$keywords_return[] = $keyword_i;
					if(sizeof($keywords_return) > 20)
					{
						break;
					}
				}
			}
		}
		$row['keywords'] = implode(',',$keywords_return);
	}

	$id_block_content_post = array_unique($nv_Request->get_typed_array('bids','post','int',array()));

	if(is_file(NV_DOCUMENT_ROOT . $row['image']))
	{
		$row['image'] = substr($row['image'],strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/'));
	}
	else
	{
		$row['image'] = '';
	}

	if(empty($row['title']))
	{
		$error[] = $lang_module['error_required_khoahoc_name'];
	}
	if(empty($row['image']))
	{
		$error[] = $lang_module['error_required_khoahoc_image'];
	}
	if(empty($row['description']))
	{
		$error[] = $lang_module['error_required_khoahoc_description'];
	}
	if($row['subjectid'] == 0)
	{
		$error[] = $lang_module['error_required_khoahoc_subjectid'];
	}
	if($row['classid'] == 0)
	{
		$error[] = $lang_module['error_required_khoahoc_classid'];
	}
    if($row['numviewtime'] == 0)
	{
		$error[] = $lang_module['error_required_numviewtime'];
	}
	if(empty($row['teacherid']))
	{
		$error[] = $lang_module['error_required_teacherid'];
	}
	if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/',$nv_Request->get_string('timestudy','post'),$m))
	{
		$row['timestudy'] = mktime(0,0,0,$m[2],$m[1],$m[3]);
	}
	else
	{
		$row['timestudy'] = 0;
	}

	if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/',$nv_Request->get_string('timeend','post'),$m))
	{
		$row['timeend'] = mktime(0,0,0,$m[2],$m[1],$m[3]);
	}
	else
	{
		$row['timeend'] = 0;
	}
	if(empty($error))
	{
		$ok_exc = 0;
		try
		{
			if($row['id'] == 0)
			{
				$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc 
                (title, image, titleseo, alias, description, hometext, classid, subjectid, teacherid, numlession, requirewatch, numviewtime, price, timestudy, timeend, addtime, numview, numlike, numviewtrial, numbuy, isvip, isfreetrial, total_rating, click_rating, listtag, status) 
                VALUES ( ' . $db->quote($row['title']) . ', ' . $db->quote($row['image']) . ', ' . $db->quote($row['titleseo']) . ', ' . $db->quote($row['alias']) . ', ' . $db->quote($row['description']) . ', ' . $db->quote($row['hometext']) . ', 
                ' . $row['classid'] . ', ' . $row['subjectid'] . ', ' . $db->quote($row['teacherid']) . ', ' . $row['numlession'] . ', ' . $row['requirewatch'] . ', ' . $row['numviewtime'] . ',
                ' . floatval($row['price']) . ', ' . $row['timestudy'] . ', ' . $row['timeend'] . ', ' . NV_CURRENTTIME . ',0,0,0,0,' . $row['isvip'] . ',' . $row['isfreetrial'] . ',5,1,' . $db->quote($row['listtag']) . ', ' . $row['status'] . ')';
				$row['id'] = $db->insert_id($sql);
				if($row['id'] > 0)
				{
					$ok_exc = 1;
					$array_block_fix = array();
					foreach($id_block_content_post as $bid_i)
					{
						$db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (' . $bid_i . ', ' . $row['id'] . ', 0)');
						$array_block_fix[] = $bid_i;
					}
					$array_block_fix = array_unique($array_block_fix);
					foreach($array_block_fix as $bid_i)
					{
						nv_studyonline_fix_block($bid_i,false);
					}
				}
			}
			else
			{
				$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_khoahoc SET 
                title=:title, image=:image, titleseo=:titleseo, alias=:alias, description=:description, hometext=:hometext, requirewatch=:requirewatch, classid=:classid, subjectid=:subjectid, teacherid=:teacherid, numlession=:numlession, numviewtime=:numviewtime, price=:price, timestudy=:timestudy, timeend=:timeend, isvip=:isvip, isfreetrial=:isfreetrial, listtag=:listtag, status=:status WHERE id=' . $row['id']);
				$stmt->bindParam(':title',$row['title'],PDO::PARAM_STR);
				$stmt->bindParam(':image',$row['image'],PDO::PARAM_STR);
				$stmt->bindParam(':titleseo',$row['titleseo'],PDO::PARAM_STR);
				$stmt->bindParam(':alias',$row['alias'],PDO::PARAM_STR);
                $stmt->bindParam(':requirewatch',$row['requirewatch'],PDO::PARAM_INT);
				$stmt->bindParam(':description',$row['description'],PDO::PARAM_STR,strlen($row['description']));
				$stmt->bindParam(':hometext',$row['hometext'],PDO::PARAM_STR,strlen($row['hometext']));
				$stmt->bindParam(':classid',$row['classid'],PDO::PARAM_INT);
				$stmt->bindParam(':subjectid',$row['subjectid'],PDO::PARAM_INT);
				$stmt->bindParam(':teacherid',$row['teacherid'],PDO::PARAM_STR);
				$stmt->bindParam(':numlession',$row['numlession'],PDO::PARAM_INT);
				$stmt->bindParam(':numviewtime',$row['numviewtime'],PDO::PARAM_INT);
				$stmt->bindParam(':price',$row['price'],PDO::PARAM_INT);
				$stmt->bindParam(':timestudy',$row['timestudy'],PDO::PARAM_INT);
				$stmt->bindParam(':timeend',$row['timeend'],PDO::PARAM_INT);
				$stmt->bindParam(':isvip',$row['isvip'],PDO::PARAM_INT);
				$stmt->bindParam(':isfreetrial',$row['isfreetrial'],PDO::PARAM_INT);
				$stmt->bindParam(':listtag',$row['listtag'],PDO::PARAM_STR);
				$stmt->bindParam(':status',$row['status'],PDO::PARAM_INT);
				$exc = $stmt->execute();
				if($exc)
				{
					$ok_exc = 1;
					$id_block_content_new = array_diff($id_block_content_post,$id_block_content);
					$id_block_content_del = array_diff($id_block_content,$id_block_content_post);

					$array_block_fix = array();
					foreach($id_block_content_new as $bid_i)
					{
						$db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (' . $bid_i . ', ' . $row['id'] . ', 0)');
						$array_block_fix[] = $bid_i;
					}
					foreach($id_block_content_del as $bid_i)
					{
						$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id = ' . $row['id'] . ' AND bid = ' . $bid_i);
						$array_block_fix[] = $bid_i;
					}

					$array_block_fix = array_unique($array_block_fix);
					foreach($array_block_fix as $bid_i)
					{
						nv_studyonline_fix_block($bid_i,false);
					}
				}
			}
			if($ok_exc == 1)
			{
				if($row['keywords'] != $row['keywords_old'])
				{
					$keywords = explode(',',$row['keywords']);
					$keywords = array_map('strip_punctuation',$keywords);
					$keywords = array_map('trim',$keywords);
					$keywords = array_diff($keywords,array(''));
					$keywords = array_unique($keywords);
					foreach($keywords as $keyword)
					{
						$keyword = str_replace('&',' ',$keyword);
						if(!in_array($keyword,$array_keywords_old))
						{
							$alias_i = ($module_config[$module_name]['tags_alias']) ? get_mod_alias($keyword) : str_replace(' ','-',$keyword);
							$alias_i = nv_strtolower($alias_i);
							$sth = $db->prepare('SELECT tid, alias, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0');
							$sth->bindParam(':alias',$alias_i,PDO::PARAM_STR);
							$sth->bindParam(':keyword',$keyword,PDO::PARAM_STR);
							$sth->execute();

							list($tid,$alias,$keywords_i) = $sth->fetch(3);
							if(empty($tid))
							{
								$array_insert = array();
								$array_insert['alias'] = $alias_i;
								$array_insert['keyword'] = $keyword;

								$tid = $db->insert_id("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_tags (numnews, alias, description, image, keywords) VALUES (1, :alias, '', '', :keyword)","tid",$array_insert);
							}
							else
							{
								if($alias != $alias_i)
								{
									if(!empty($keywords_i))
									{
										$keyword_arr = explode(',',$keywords_i);
										$keyword_arr[] = $keyword;
										$keywords_i2 = implode(',',array_unique($keyword_arr));
									}
									else
									{
										$keywords_i2 = $keyword;
									}
									if($keywords_i != $keywords_i2)
									{
										$sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET keywords= :keywords WHERE tid =' . $tid);
										$sth->bindParam(':keywords',$keywords_i2,PDO::PARAM_STR);
										$sth->execute();
									}
								}
								$db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews+1 WHERE tid = ' . $tid);
							}

							// insert keyword for table _tags_id
							try
							{
								$sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id (id, tid, keyword) VALUES (' . $row['id'] . ', ' . intval($tid) . ', :keyword)');
								$sth->bindParam(':keyword',$keyword,PDO::PARAM_STR);
								$sth->execute();
							}
							catch (PDOException $e)
							{
								$sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id SET keyword = :keyword WHERE id = ' . $row['id'] . ' AND tid=' . intval($tid));
								$sth->bindParam(':keyword',$keyword,PDO::PARAM_STR);
								$sth->execute();
							}
							unset($array_keywords_old[$tid]);
						}
					}

					foreach($array_keywords_old as $tid => $keyword)
					{
						if(!in_array($keyword,$keywords))
						{
							$db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid = ' . $tid);
							$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $row['id'] . ' AND tid=' . $tid);
						}
					}
				}
				$nv_Cache->delMod($module_name);
				Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=khoahoc');
				die();
			}
		}
		catch (PDOException $e)
		{
			$error[] = $e->getMessage();
		}
	}
}

$row['teacherid'] = explode(',',$row['teacherid']);

$row['hometext'] = nv_br2nl($row['hometext']);
if($row['timestudy'] == 0)
{
	$row['timestudy'] = '';
}
else
{
	$row['timestudy'] = date('d/m/Y',$row['timestudy']);
}
if($row['timeend'] == 0)
{
	$row['timeend'] = '';
}
else
{
	$row['timeend'] = date('d/m/Y',$row['timeend']);
}
if(!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['image']))
{
	$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
}

if(defined('NV_EDITOR'))
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
$row['hometext'] = htmlspecialchars(nv_editor_br2nl($row['hometext']));
if(defined('NV_EDITOR') and nv_function_exists('nv_aleditor'))
{
	$row['hometext'] = nv_aleditor('hometext','100%','300px',$row['hometext']);
}
else
{
	$row['hometext'] = '<textarea style="width:100%;height:300px" name="hometext">' . $row['hometext'] . '</textarea>';
}

$row['ckisvip'] = ($row['isvip'] == 0) ? ' checked=checked' : '';
$row['ckisfreetrial'] = ($row['isfreetrial'] == 0) ? ' checked=checked' : '';

$xtpl = new XTemplate($op . '.tpl',NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('module_file',$module_file);
$xtpl->assign('LANG',$lang_module);
$xtpl->assign('NV_LANG_VARIABLE',NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA',NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL',NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE',NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE',NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME',$module_name);
$xtpl->assign('OP',$op);
$xtpl->assign('ROW',$row);

if(sizeof($array_block_cat_module))
{
	foreach($array_block_cat_module as $bid_i => $bid_title)
	{
		$xtpl->assign('BLOCKS',array(
			'title' => $bid_title,
			'bid' => $bid_i,
			'checked' => in_array($bid_i,$id_block_content) ? 'checked="checked"' : ''));
		$xtpl->parse('main.block_cat.loop');
	}
	$xtpl->parse('main.block_cat');
}

if(!empty($error))
{
	$xtpl->assign('ERROR',implode('<br />',$error));
	$xtpl->parse('main.error');
}
if(!empty($row['keywords']))
{
	$keywords_array = explode(',',$row['keywords']);
	foreach($keywords_array as $keywords)
	{
		$xtpl->assign('KEYWORDS',$keywords);
		$xtpl->parse('main.keywords');
	}
}

$array_select_status = array();
$array_select_status[0] = $lang_module['status_0'];
$array_select_status[1] = $lang_module['status_1'];
foreach($array_select_status as $key => $title)
{
	$xtpl->assign('OPTION',array(
		'key' => $key,
		'title' => $title,
		'selected' => ($key == $row['status']) ? ' selected="selected"' : ''));
	$xtpl->parse('main.select_status');
}

foreach($array_class as $class)
{
	$xtpl->assign('OPTION',array(
		'key' => $class['id'],
		'title' => $class['title'],
		'selected' => ($class['id'] == $row['classid']) ? ' selected="selected"' : ''));
	$xtpl->parse('main.classes_class');
}

foreach($array_subject as $subject)
{
	$xtpl->assign('OPTION',array(
		'key' => $subject['id'],
		'title' => $subject['title'],
		'selected' => ($subject['id'] == $row['subjectid']) ? ' selected="selected"' : ''));
	$xtpl->parse('main.classes_subject');
}

foreach($array_teacher as $teacher)
{
	if(in_array($teacher['id'],$row['teacherid']))
	{
		$xtpl->assign('TEACHER',$teacher);
		$xtpl->parse('main.classes_teacher');
	}
}

$row['listtag'] = explode(',',$row['listtag']);
foreach($array_tag as $tag)
{
	$tag['ck'] = (in_array($tag['tag_id'],$row['listtag'])) ? ' checked=checked' : '';
	$xtpl->assign('TAG',$tag);
	$xtpl->parse('main.listtag');
}

if(empty($row['id']))
{
	$xtpl->parse('main.auto_get_alias');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
