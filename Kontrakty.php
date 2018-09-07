<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczkontraktowy_Kontrakty  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty';
 
    }
    function fields() { // - here you choose the fields to add to the record browser

        //data wstawienia -> start tuczu
        $data_start = new RBO_Field_Date(_M("Data start"));
        $data_start->set_required()->set_visible();

        //data zakonczenia / zabrania tuczu -> koniec tuczu
        $data_end = new RBO_Field_Date(_M("Data end"));
        $data_end->set_visible();

        //hodowca -> rolnik
        $farmer = new RBO_Field_Select(_M('farmer'));
        $farmer->from('company')->set_crits_callback("tuczkontraktowyCommon::critOnlyFarmers")->fields('company_name')->set_visible()->set_required();

        //notatka 
        $note = new RBO_Field_LongText(_M("Note"));
        $note->set_visible();


        //kolczyk
        $kolczyk = new RBO_Field_Text(_M("Kolczyk"));
        $kolczyk->set_visible()->set_length(80);

        //nazwa 
        $name = new RBO_Field_Text(_M("name_number"));
        $name->set_visible()->set_required()->set_length(255);

        $status = new RBO_Field_CommonData(_M("status"));
        $status->from('Kontrakty/status')->set_required()->set_visible();

        return array($data_start,$data_end,$farmer,
        $note,$kolczyk,$name,$status); // - remember to return all defined fields
 
 
    }
    
}

?>