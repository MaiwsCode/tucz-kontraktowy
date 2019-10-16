<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');


$fields = array(
    array('name' => _M('parent'), 'type'=>'select','param'=>'loans',
             'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_parent'), 
             'required'=>true, 'extra'=>false, 'visible'=>true, 'filter'=>true),
    array('name' => _M('tucz'), 'type'=>'select','param'=>"kontrakty::name_number",
             'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_tucz')),
    array('name' => _M('value'),    'type'=>'currency', 'required'=>true, 'extra'=>false, 'visible'=>true),
    array('name' => _M('payment_deadline'), 'type'=>'date', 'required'=>true, 'extra'=>false, 'visible'=>true),
    array('name' => _M('status'),   'type'=>'commondata',  'param'=>array('Kontrakty/zaliczki/statusy'),
    'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_status'), 'required'=>true, 'extra'=>false, 'visible'=>true),
    );


Utils_RecordBrowserCommon::install_new_recordset('loans_parts', $fields);

Utils_RecordBrowserCommon::add_access('loans_parts', 'view', 'ACCESS:employee');
Utils_RecordBrowserCommon::add_access('loans_parts','edit', 'ACCESS:employee');
Utils_RecordBrowserCommon::add_access('loans_parts','delete', 'ADMIN');
Utils_RecordBrowserCommon::add_access('loans_parts','add', 'ACCESS:employee');