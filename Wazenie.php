<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczkontraktowy_Wazenie  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_wazenie';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


        // id kontraktu 
        $tucz_id = new RBO_Field_Integer(_M("ID tuczu"));

        //data wazenia
        $date_weight = new RBO_Field_Date(_M("Date weight"));
        $date_weight->set_required()->set_visible();

        //numer swini
        $pig_number = new RBO_Field_Integer(_M("pig number"));
        $pig_number->set_required()->set_visible();

        //waga na wazeniu
        $weight = new RBO_Field_Text(_M("weight"));
        $weight->set_required()->set_visible()->set_length(30);

        return array($tucz_id, $date_weight, $pig_number,$weight); // - remember to return all defined fields
 
 
    }
    
}

?>