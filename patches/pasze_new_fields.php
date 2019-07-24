<?php


defined("_VALID_ACCESS") || die('Direct access forbidden');


Utils_RecordBrowserCommon::new_record_field('kontrakty_faktury_dostawa_paszy',
    array(
        'name' => _M('weightCarEmpty'),
        'type' => 'text',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
        'param' => 64,
    )
);
Utils_RecordBrowserCommon::new_record_field('kontrakty_faktury_dostawa_paszy',
    array(
        'name' => _M('weightCarFull'),
        'type' => 'text',
        'extra'=>false,
        'visible'=>true,
        'required' => false,
        'param' => 64,
    )
);
