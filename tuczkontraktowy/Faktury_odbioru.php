<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczekontraktowe_tuczkontraktowy_Faktury_odbioru  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_odbiory';
 
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

        //ilosc swin
        $amount = new RBO_Field_Integer(_M("amount szt"));
        $amount->set_required()->set_visible();

        //wartosc netto 
        $netto = new RBO_Field_Currency(_M("value netto"));
        $netto->set_required()->set_visible();

        //waga zywa brutton
        $brutto = new RBO_Field_Currency(_M("Waga żywa brutto"));
        $brutto->set_required()->set_visible();

        //waga miesa
        $meat_weight = new RBO_Field_Currency(_M("meat weight"));
        $meat_weight->set_required()->set_visible();

        //waga miesa
        $meat_weight = new RBO_Field_Text(_M("Mięsność"));
        $meat_weight->set_required()->set_visible()->set_length(80);



        return array($contract_id,$date,$fv,$amount,$netto,$brutto,$meat_weight,$meat_weight); // - remember to return all defined fields
 
 
    }
    
}

?>