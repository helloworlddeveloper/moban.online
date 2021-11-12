<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */
if (!defined('NV_MAINFILE')) die('Stop!!!');

define('NV_TABLE_EMKT', $db_config['dbsystem'] . '.' . NV_PREFIXLANG . '_emailmarketing');

function save_data_customer($fullname, $gender, $birthday, $phone, $email, $key_action)
{
    global $db, $module_name, $module_data, $module_config;

    $groups = $module_config['emailmarketing']['new_customer_group'];

    try {
        $count = $db->query('SELECT COUNT(*) FROM ' . NV_TABLE_EMKT . '_customer WHERE phone=' . $db->quote($phone))
            ->fetchColumn();
        if ($count == 0) {
            $count = $db->query('SELECT COUNT(*) FROM ' . NV_TABLE_EMKT . '_customer WHERE email=' . $db->quote($email))
                ->fetchColumn();
            if ($count == 0) {
                $_sql = 'INSERT INTO ' . NV_TABLE_EMKT . '_customer(fullname, gender, birthday, phone, email, groups, addtime) VALUES(:fullname, :gender, :birthday, :phone, :email, :groups, ' . NV_CURRENTTIME . ')';
                $data_insert = array();
                $data_insert['fullname'] = $fullname;
                $data_insert['gender'] = $gender;
                $data_insert['birthday'] = $birthday;
                $data_insert['phone'] = $phone;
                $data_insert['email'] = $email;
                $data_insert['groups'] = $groups;

                $new_id = $db->insert_id($_sql, 'id', $data_insert);
                if ($new_id > 0) {
                    $customer_group = explode(',', $groups);
                    $sth = $db->prepare('INSERT INTO ' . NV_TABLE_EMKT . '_customer_groups (customerid, groupid) VALUES(:customerid, :groupid)');
                    foreach ($customer_group as $customer_group_id) {
                        if (!in_array($customer_group_id, $row['customer_group_old'])) {
                            $sth->bindParam(':customerid', $new_id, PDO::PARAM_INT);
                            $sth->bindParam(':groupid', $customer_group_id, PDO::PARAM_INT);
                            $sth->execute();
                        }
                    }
                }
            }
        }
        if (isset($module_config['emailmarketing'][$module_data . '_' . $key_action . '_reply']) && !empty($module_config['emailmarketing'][$module_data . '_' . $key_action . '_reply']) && !empty($phone)) {
            $_sql = 'INSERT INTO ' . NV_TABLE_EMKT . '_smsqueue(to_phone, content) VALUES(:to_phone, :content)';
            $data_insert = array();
            $data_insert['to_phone'] = $phone;
            $data_insert['content'] = $module_config['emailmarketing'][$module_data . '_' . $key_action . '_reply'];
            $new_id = $db->insert_id($_sql, 'id', $data_insert);
        }
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
        return $e->getMessage();
    }
}
