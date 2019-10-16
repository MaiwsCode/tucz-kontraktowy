<?php

define('CID',false);
#define('READ_ONLY_SESSION',true);
require_once('../../include.php');
ModuleManager::load_modules();


if($_GET['action'] == 'add'){
    $_SESSION['expanded'][] = $_GET['id'];
}else{
    $expand =  $_SESSION['expanded'];
    $tmp = array();
    foreach($expand as $key => $val){
        if($val != $_GET['id']){
            $tmp[] = $val;
        }
    }
    $_SESSION['expanded'] = $tmp;
}