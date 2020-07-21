<?php

define('CID',false);
#define('READ_ONLY_SESSION',true);
require_once('../../include.php');
ModuleManager::load_modules();

if ($_POST['updatePerRecord']) {
    $records = Utils_RecordBrowserCommon::get_records("kontrakty_zalozenia", ['id_tuczu' => $_POST['updateRecordId'] ], [], [] );
    foreach ($records as $record) {
        Utils_RecordBrowserCommon::update_record('kontrakty_zalozenia', $record['id'], ['weightslist' => $_POST['values']] );
    }
}
if ($_POST['updatePerRecord'] == 0) {
    $listToUpdateJSON = $_POST['values'];
    $listToUpdate = json_decode($listToUpdateJSON, true);
    $actualValues = [];
    for($i = 0; $i <= count($listToUpdate) - 1 ; $i++) {
        $actualValues[] = $listToUpdate[$i]['v'];
    }
    Utils_CommonDataCommon::new_array("/Kontrakty/premia", $actualValues, $overwrite = true);
    for($i = 0; $i <= count($actualValues) - 1 ; $i++) {
        Utils_CommonDataCommon::new_array("Kontrakty/premia/" . $i, [$i => $listToUpdate[$i]['m'] ] );
    }
}