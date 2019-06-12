<?php


defined("_VALID_ACCESS") || die('Direct access forbidden');

$records = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_pozycje" , array() ,array() ,array());

Utils_RecordBrowserCommon::delete_record_field('kontrakty_faktury_pozycje','amount');
Utils_RecordBrowserCommon::new_record_field('kontrakty_faktury_pozycje',
    array(
        'name' => _M('amount'),
        'type' => 'text',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
        'param' => 64,
    )
);
Utils_RecordBrowserCommon::change_field_position("kontrakty_faktury_pozycje", "amount", 6);
foreach ($records as $record) {
    Utils_RecordBrowserCommon::update_record("kontrakty_faktury_pozycje", $record['id'] , array('amount' => $record['amount']) , false);
}


Utils_RecordBrowserCommon::new_record_field('kontrakty_faktury_odbior_tucznika',
    array(
        'name' => _M('konfiskaty'),
        'type' => 'integer',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
    )
);

Utils_RecordBrowserCommon::new_record_field('kontrakty_faktury_odbior_tucznika',
    array(
        'name' => _M('premiowane'),
        'type' => 'integer',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
    )
);

Utils_RecordBrowserCommon::new_record_field('kontrakty_faktury_odbior_tucznika',
    array(
        'name' => _M('suboptimal'),
        'type' => 'integer',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
    )
);

Utils_RecordBrowserCommon::new_record_field('kontrakty_faktury_odbior_tucznika',
    array(
        'name' => _M('badweight'),
        'type' => 'integer',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
    )
);
