<?php
/**
 * Created by PhpStorm.
 * User: Mati
 * Date: 05.06.2019
 * Time: 12:53
 */

$cid = $_REQUEST['cid'];
define('CID', $cid);
define('READ_ONLY_SESSION',true);

require_once('../../include.php');

ModuleManager::load_modules();

$template = new \PhpOffice\PhpWord\TemplateProcessor(__DIR__.'/harmonogramy/template.docx');


$recordID = $_REQUEST['recordID'];

$record = Utils_RecordBrowserCommon::get_record('kontrakty', $recordID);


$zal = Utils_RecordBrowserCommon::get_records("kontrakty_zalozenia", array('id_tuczu' => $record['id']));
foreach ($zal as $z){$zal = $z;break;}
$farmer =  Utils_RecordBrowserCommon::get_record("company", $record['farmer']);
$rbo_limits = new RBO_RecordsetAccessor('kontrakty_limity');
$limits = $rbo_limits->get_records( array('id_tuczu' => $record['id']),array(),array());
$deliver = Utils_RecordBrowserCommon::get_record("company", $zal['deliverer']);
$all_limits = 0;
$starter = 0;
$grower = 0;
$finisher = 0;
foreach($limits as $limit){
    $all_limits += $limit->amount;
    $starter = ($limit->feed_type == 'starter' ? $starter = $limit->amount : $starter);
    $grower = ($limit->feed_type == 'grower' ? $grower = $limit->amount : $grower);
    $finisher = ($limit->feed_type == 'finisher' ? $finisher = $limit->amount : $finisher);
}
$full_address = $farmer["address_1"].", ".$farmer["postal_code"]." ".$farmer["city"]."  ";
$end_date =  strtotime($record['data_start']);
$end_date += (90 * 24*60*60);
$end_date = date("Y-m-d", $end_date);
$template->setValue('deliverName', $deliver['company_name']);
$template->setValue('deliverAdress', $deliver['address_1']);
$template->setValue('deliverPostalCode', $deliver['postal_code']);
$template->setValue('deliverCity', $deliver['city']);
$template->setValue('deliverNIP', $deliver['tax_id']);
$template->setValue('date', $record['data_start']);
$template->setValue('amount', $zal['planned_amount']);
$template->setValue('weight', $zal['weight_pig_start']);
$template->setValue('who',  $farmer["company_name"]);
$template->setValue('where', $full_address);
$template->setValue('from', $record['data_start']);
$template->setValue('to', $end_date);
$template->setValue('how_many',  number_format($all_limits ,"0",","," "));
$template->setValue('starter', number_format($starter,"0",","," "));
$template->setValue('grower', number_format($grower,"0",","," "));
$template->setValue('finisher', number_format($finisher,"0",","," "));
$template->setValue('koncentrat', '0');
$template->setValue('paid_day', $end_date);
$name = "harmonogram_dostawy_paszy.docx";
header("Content-Description: File Transfer");
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$name.'"');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
$template->saveAs('php://output');
exit();

