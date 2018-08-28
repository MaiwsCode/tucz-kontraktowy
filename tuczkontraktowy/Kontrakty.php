<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczekontraktowe_tuczkontraktowy_Kontrakty  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty';
 
    }
    function fields() { // - here you choose the fields to add to the record browser



        //kolczyk
        $pig_id = new RBO_Field_Text(_M("pig id"));
        $pig_id->set_required()->set_visible()->set_length(40);

        //cena warchlaka
        $price_pig = new RBO_Field_Currency(_M("price pig"));
        $price_pig->set_required()->set_visible();

        //waga malego warchlaka
        $weight_pig_small = new RBO_Field_Currency(_M("weight small pig"));
        $weight_pig_small->set_required()->set_visible();

        //wstawione z widoku dodaj pasze -> wyliczenie cen i wrzucenie
        $price_feed = new RBO_Field_Currency(_M("price feed"));
        $price_feed->set_visible();

        //waga malego warchlaka
        $weight_pig_big = new RBO_Field_Currency(_M("weight big pig"));
        $weight_pig_big->set_required()->set_visible();

        //data wstawienia -> start tuczu
        $data_start = new RBO_Field_Date(_M("Data start"));
        $data_start->set_required()->set_visible();

        //data zakonczenia / zabrania tuczu -> koniec tuczu
        $data_end = new RBO_Field_Date(_M("Data end"));
        $data_end->set_visible();

        // przywiezione/wstawione sztuki
        $zakupy = new RBO_Field_Select(_M("Zakupy"));
		$zakupy->from(
            'custom_agrohandel_purchase_plans')->set_crits_callback("tuczekontraktowe_tuczkontraktowyCommon::critDates")->fields('company', 'amount');
        $zakupy->visible;

        //ubytek
        $weight_loss = new RBO_Field_Integer(_M("weight loss"));
        $weight_loss->set_visible();

        //srednie zuzycie
        $average = new RBO_Field_Currency(_M('Średnie  zużycie'));
        $average->set_required()->set_visible();

        //hodowca -> rolnik
        $farmer = new RBO_Field_Select(_M('farmer'));
        $farmer->from('company')->set_crits_callback("tuczekontraktowe_tuczkontraktowyCommon::critOnlyFarmers")->fields('company_name')->set_visible()->set_required();



        return array($pig_id,$price_pig,$weight_pig_small,
        $price_feed,$weight_pig_big,$data_start,$data_end,
        $zakupy, $weight_loss,$average, $farmer  ); // - remember to return all defined fields
 
 
    }
    
}

?>