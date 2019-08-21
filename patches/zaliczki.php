<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');


Utils_CommonDataCommon::new_array("Kontrakty/zaliczki/statusy", array('0' => 'Nierozliczony' , '1' => 'Rozliczony'));

Utils_RecordBrowserCommon::new_addon('company', "tuczkontraktowy", 'loans',
    array('tuczkontraktowyCommon', 'loansLabel'));
 
    $fields = array(
    array('name' => _M('company'), 'type'=>'crm_company', 'param'=>array('field_type'=>'select',
             'crits'=>array(), 'format'=>array('CRM_ContactsCommon','contact_format_no_company')),
             'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_company'), 
             'required'=>true, 'extra'=>false, 'visible'=>true, 'filter'=>true),
    array('name' => _M('tucz'), 'type'=>'select','param'=>"kontrakty::name_number",
             'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_tucz')),
    array('name' => _M('note'),     'type'=>'text',  'param'=>256, 'required'=>false,  'extra'=>false, 'visible'=>true),
    array('name' => _M('value'),    'type'=>'currency', 'required'=>true, 'extra'=>false, 'visible'=>true),
    array('name' => _M('payment_deadline'), 'type'=>'date', 'required'=>true, 'extra'=>false, 'visible'=>true),
    array('name' => _M('status'),   'type'=>'commondata',  'param'=>array('Kontrakty/zaliczki/statusy'),
    'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_status'), 'required'=>true, 'extra'=>false, 'visible'=>true),
    array('name' => _M('comments'), 'type'=>'text',  'param'=>128, 'required'=>false, 'extra'=>false, 'visible'=>true),
    );

Utils_RecordBrowserCommon::install_new_recordset('loans', $fields);

Utils_RecordBrowserCommon::add_access('loans', 'view', 'ACCESS:employee');
Utils_RecordBrowserCommon::add_access('loans','edit', 'ACCESS:employee');
Utils_RecordBrowserCommon::add_access('loans','delete', 'ADMIN');
Utils_RecordBrowserCommon::add_access('loans','add', 'ACCESS:employee');

$fields = array(
    array('name' => _M('tucz'), 'type'=>'select','param'=>"kontrakty::name_number",
             'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_autoTucz')),
    array('name' => _M('note'),     'type'=>'text',  'param'=>256, 'required'=>false,  'extra'=>false, 'visible'=>true),
    array('name' => _M('value'),    'type'=>'currency', 'required'=>true, 'extra'=>false, 'visible'=>true),
    array('name' => _M('payment_date'), 'type'=>'date', 'required'=>true, 'extra'=>false, 'visible'=>true),
    array('name' => _M('status'),   'type'=>'commondata',  'param'=>array('Kontrakty/zaliczki/statusy'),
    'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_status'), 'required'=>true, 'extra'=>false, 'visible'=>true),
    array('name' => _M('comments'), 'type'=>'text',  'param'=>128, 'required'=>false, 'extra'=>false, 'visible'=>true),
    );
Utils_RecordBrowserCommon::install_new_recordset('kontrakty_advances', $fields);

Utils_RecordBrowserCommon::add_access('kontrakty_advances', 'view', 'ACCESS:employee');
Utils_RecordBrowserCommon::add_access('kontrakty_advances','edit', 'ACCESS:employee');
Utils_RecordBrowserCommon::add_access('kontrakty_advances','delete', 'ADMIN');
Utils_RecordBrowserCommon::add_access('kontrakty_advances','add', 'ACCESS:employee');

Utils_RecordBrowserCommon::new_addon('kontrakty', "tuczkontraktowy", 'advances',
    array('tuczkontraktowyCommon', 'advancesLabel'));

$fields = array(
    array('name' => _M('tucz'), 'type'=>'integer'),
    array('name' => _M('pig_weight'),     'type'=>'text',  'param'=>64, 'required'=>false,  'extra'=>false, 'visible'=>true),
    array('name' => _M('szefowa_notatka'),     'type'=>'text',  'param'=>254, 'required'=>false,  'extra'=>false, 'visible'=>true),
    array('name' => _M('szefowa_value_discount'),     'type'=>'text',  'param'=>64, 'required'=>false,  'extra'=>false, 'visible'=>true),

);
    
Utils_RecordBrowserCommon::install_new_recordset('kontrakty_extra_data', $fields);

Utils_RecordBrowserCommon::add_access('kontrakty_extra_data', 'view', 'ACCESS:employee');
Utils_RecordBrowserCommon::add_access('kontrakty_extra_data','edit', 'ACCESS:employee');
Utils_RecordBrowserCommon::add_access('kontrakty_extra_data','delete', 'ADMIN');
Utils_RecordBrowserCommon::add_access('kontrakty_extra_data','add', 'ACCESS:employee');