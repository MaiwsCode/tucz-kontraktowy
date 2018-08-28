<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczekontraktowe_tuczkontraktowy_Upadki extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_upadki';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


        // id kontraktu 
        $contract_id = new RBO_Field_Select(_M("contract"));
        $contract_id->from('kontrakty')->fields('farmer' ,'data_start');

        //data upadku
        $date_fall = new RBO_Field_Date(_M("Date fall"));
        $date_fall->set_required()->set_visible();

        //ilosc padłych
        $amount_fall = new RBO_Field_Integer(_M("amount fall"));
        $amount_fall->set_required()->set_visible();

        //waga padłych
        $weight_fall = new RBO_Field_Currency(_M("weight fall"));
        $weight_fall->set_required()->set_visible();

        return array($contract_id , $date_fall , $amount_fall , $weight_fall); // - remember to return all defined fields
 
 
    }
    
}

?>