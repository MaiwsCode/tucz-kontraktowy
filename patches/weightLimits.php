<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');

$actucalArray = [ 
     0 => 'gt_139.10',
     1 => 'gte_136.10;lte_139.09',
     2 => 'gte_133.10;lte_136.09',
     3 => 'gte_130.10;lte_133.09',
     4 => 'gte_126.10;lte_130.09',
     5 => 'gte_122.10;lte_126.09',
     6 => 'gte_118.10;lte_122.09',
     7 => 'gte_115.10;lte_118.09',
     8 => 'lte_115.09',
];

$actualAvg = [
    0 => '3.10',
    1 => '3.05',
    2 => '3.00',
    3 => '2.95',
    4 => '2.90',
    5 => '2.85',
    6 => '2.80',
    7 => '2.75',
    8 => '2.70',

];

$oldWeights = [ 
    0 => 'gte_126.10',
    1 => 'gte_122.10;lte_126.09',
    2 => 'gte_118.10;lte_122.09',
    3 => 'gte_115.10;lte_118.09',
    4 => 'lte_115.09',
];

$oldAvg = [
   0 => '2.90',
   1 => '2.85',
   2 => '2.80',
   3 => '2.75',
   4 => '2.70',

];

Utils_CommonDataCommon::new_array("Kontrakty/premia", $actucalArray);

for($i = 0; $i <= count($actucalArray) - 1 ; $i++) {
    Utils_CommonDataCommon::new_array("Kontrakty/premia/" . $i, [$i => $actualAvg[$i] ] );
}


Utils_RecordBrowserCommon::new_record_field('kontrakty_zalozenia',
    array(
        'name' => _M('weightslist'),
        'type' => 'long text',
        'extra'=> false,
        'visible'=> true,
        'required' => false,
        'QFfield_callback'=> array('tuczkontraktowyCommon', 'QFfield_weightsList'),
    )
);

$tuczeOldList = Utils_RecordBrowserCommon::get_records('kontrakty', ['<data_start' => '2020-04-01', ], [], []);
$idOldList = [];

foreach($tuczeOldList as $tucz){
    $idOldList[] = $tucz['id'];
}

$recordsOld = Utils_RecordBrowserCommon::get_records('kontrakty_zalozenia', ['id_tuczu' => $idOldList], [], []);

$OldBonusArray = [];
for($i = 0; $i <= count($oldWeights) - 1; $i++) {
    $OldBonusArray[] = array('v' => $oldWeights[$i] , 'm' => $oldAvg[$i]);
}		

$weightArray = json_encode($OldBonusArray);
                
foreach($recordsOld as $record){
    Utils_RecordBrowserCommon::update_record('kontrakty_zalozenia', $record['id'],
    [
        'weightslist' => $weightArray,
    ] );
}

$tuczeList = Utils_RecordBrowserCommon::get_records('kontrakty', ['>=data_start' => '2020-04-01', ], [], []);
$idList = [];

foreach($tuczeList as $tucz){
    $idList[] = $tucz['id'];
}

$records = Utils_RecordBrowserCommon::get_records('kontrakty_zalozenia', ['id_tuczu' => $idList], [], []);

$currentBonusArray = [];
for($i = 0; $i <= count($actucalArray) - 1; $i++) {
    $currentBonusArray[] = array('v' => $actucalArray[$i] , 'm' => $actualAvg[$i]);
}		

$weightArray = json_encode($currentBonusArray);
                
foreach($records as $record){
    Utils_RecordBrowserCommon::update_record('kontrakty_zalozenia', $record['id'],
    [
        'weightslist' => $weightArray,
    ] );
}