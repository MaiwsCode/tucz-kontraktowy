<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczekontraktowe_tuczkontraktowy_Faktury_weterynarz  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_wet';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


        // id kontraktu 
        $contract_id = new RBO_Field_Select(_M("contract"));
        $contract_id->from('kontrakty')->fields('farmer' ,'data_start');

        //data wystawienia
        $date = new RBO_Field_Date(_M("Date release"));
        $date->set_required()->set_visible();

        //nr faktury
        $fv = new RBO_Field_Text(_M("Nr faktury"));
        $fv->set_required()->set_visible()->set_length(180);;

        //dostawca
        $deliverer = new RBO_Field_Select(_M('deliverer'));
        $deliverer->from('company')->fields('company_name')->set_visible()->set_required();

        //wartosc netto 
        $netto = new RBO_Field_Currency(_M("value netto"));
        $netto->set_required()->set_visible();

        //uwagi 
        $comments= new RBO_Field_Text(_M("comments"));
        $comments->set_required()->set_visible()->set_length(255);


        return array($contract_id,$date,$fv,$deliverer,$netto,$comments); // - remember to return all defined fields
 
 
    }
    
}

?>