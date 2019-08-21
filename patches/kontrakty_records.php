<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');

$records = Utils_RecordBrowserCommon::get_records("kontrakty" , array() ,array() ,array());

Utils_RecordBrowserCommon::delete_record_field('kontrakty','note');

Utils_RecordBrowserCommon::new_record_field('kontrakty',
    array(
        'name' => _M('Note'),
        'type' => 'text',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
        'param' => '128',
    )
);

Utils_RecordBrowserCommon::new_record_field('kontrakty',
    array(
        'name' => _M('Comments'),
        'type' => 'long text',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
    )
);

foreach ($records as $record) {
    Utils_RecordBrowserCommon::update_record("kontrakty", $record['id'] , array('note' => $record['note']) , false);
}