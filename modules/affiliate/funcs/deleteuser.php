<?php


$db_slave->sqlreset()
    ->select('*')
    ->from($db_config['prefix'] . '_' . $module_data . '_users')
    ->where('pendingdelete > 0 AND pendingdelete<=' . NV_CURRENTTIME . ' AND ishidden=0' )
    ->order('pendingdelete ASC');

$result = $db_slave->query($db_slave->sql());
$data_content = array();
while ( $row = $result->fetch()) {
    $result_i = $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET ishidden=1, numsubcat=0, subcatid= ' . $db->quote('') . ' WHERE userid=' . $row['userid']);
    if( $result_i ){
        $subcatid = array();
        if( !empty( $row['subcatid'] )){
            $subcatid = explode(',', $row['subcatid'] );
            foreach ( $subcatid as $catid_i ){
                //cap nhat lai tuyen tren cho cac npp tuyen duoi tk bi ngung kich hoat
                $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET parentid=' . $row['parentid'] . ' WHERE userid=' . $catid_i);
            }
        }

        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $row['parentid'];
        $data_users_parent = $db->query($sql)->fetch();
        $subcatid_parent = explode(',', $data_users_parent['subcatid'] );
        $subcatid_parent = array_merge( $subcatid_parent, $subcatid );
        $key = array_search($row['userid'], $subcatid_parent);
        if (false !== $key) {
            unset($subcatid_parent[$key]);
        }

        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET numsubcat=' . count( $subcatid_parent ) . ', subcatid=' . $db->quote( implode(',', $subcatid_parent ) ) . ' WHERE userid=' . $row['parentid'] );
    }
}
file_put_contents( NV_ROOTDIR . '/deleteusser.txt', date('d/m/Y H:i', NV_CURRENTTIME) . '\n', 1);
exit('1');
