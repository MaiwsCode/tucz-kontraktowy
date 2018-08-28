<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczekontraktowe_tuczkontraktowy_Kosztytransportu  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_koszty_transportu';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


        // id kontraktu 
        $contract_id = new RBO_Field_Select(_M("contract"));
        $contract_id->from('kontrakty')->fields('farmer' ,'data_start');

        //data 
        $date = new RBO_Field_Date(_M("Date"));
        $date->set_required()->set_visible();

        //cena transportu
        $cost = new RBO_Field_Currency(_M("Transport cost"));
        $cost->set_required()->set_visible();
        
        //ilosc swin
        $amount = new RBO_Field_Integer(_M("amount"));
        $amount->set_required()->set_visible();

        //wartosc netto
        $netto = new RBO_Field_Currency(_M("value netto"));
        $netto->set_required()->set_visible();

        //odbiorca nie wiem czy wymagany
       // $company = new RBO_Field_Select(_M("company"));
       // $company->from('company')->set_crits_callback("tuczkontraktowyCommon::critOnlyUbojnia")->fields('company_name')->set_visible()->set_required();

        return array($contract_id,$date,$cost,$amount,$netto); // - remember to return all defined fields
 
 
    }
    
}

?>