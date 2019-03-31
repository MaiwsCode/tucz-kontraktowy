<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 

class tuczkontraktowy_zalozenia  extends RBO_Recordset {
 
 function table_name() { // - choose a name for the table that will be stored in EPESI database

     return 'kontrakty_zalozenia';

 }
 function fields() { // - here you choose the fields to add to the record browser

     //tucz ID 
     $tucz_id = new RBO_Field_Integer(_M("id tuczu"));

     //Waga wstawienia warchlaka
     $weight_pig_start = new RBO_Field_Text(_M("weight pig start"));
     $weight_pig_start->set_visible()->set_length(30);

     //Oczekiwana waga wyjściowa 
     $weight_pig_end = new RBO_Field_Text(_M("weight pig end"));
     $weight_pig_end->set_visible()->set_length(30);

     //Starter
     $price_st = new RBO_Field_Text(_M("price starter"));
     $price_st->set_visible()->set_length(30);

     //Grower
     $price_gr = new RBO_Field_Text(_M("price grower"));
     $price_gr->set_visible()->set_length(30);

     //Finisher
     $price_fin = new RBO_Field_Text(_M("price finisher"));
     $price_fin->set_visible()->set_length(30);

     //Cena warchlaka
     $price_pig = new RBO_Field_Currency(_M("price pig"));
     $price_pig->set_visible()->set_required();

     //Zużycie na kg przyrostu |
     //                        V
     //Starter 
    $avg_usage = new RBO_Field_CommonData(_M("avg_usage"));
    $avg_usage->from('Kontrakty/sr_zuzycie')->set_required()->set_visible();
    
     //Lekarstwa
     $med = new RBO_Field_Currency(_M("med"));
     $med->set_visible();

     //Ubytki
     $lose = new RBO_Field_Float(_M("lose"));
     $lose->set_visible();

     //Rolnik
     $farmer = new RBO_Field_Currency(_M("farmer"));
     $farmer->set_visible();
	 
	 $planned_amount = new RBO_Field_Integer(_M("planned_amount"));
	 $planned_amount->set_visible();

    //max kg na starterze
    $st_to = new RBO_Field_Float(_M("starter to"));
    $st_to->set_visible();

    //min kg na grower
    //$gr_from = new RBO_Field_Float(_M("grower from"));
    //$gr_from->set_visible();

    //max kg na grower
    $gr_to = new RBO_Field_Float(_M("grower to"));
    $gr_to->set_visible();

    //dostawca paszy
     $deliverer = new RBO_Field_Select(_M('deliverer'));
     $deliverer->from('company')->set_crits_callback("tuczkontraktowyCommon::critOnlyVendor")->fields('company_name')->set_visible()->set_required();


     return array($tucz_id,$weight_pig_start,$weight_pig_end,$price_st,
     $price_gr,$price_fin,$price_pig,$avg_usage,$med,$lose,$farmer,
    $st_to,$gr_to,$planned_amount,$deliverer);


 }
 
}

?>