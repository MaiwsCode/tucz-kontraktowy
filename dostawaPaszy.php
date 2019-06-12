<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczkontraktowy_dostawaPaszy  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_dostawa_paszy';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


                // id kontraktu 
                $tucz_id = new RBO_Field_Integer(_M("ID tuczu"));
       
                //nazwa dokumentu
                $fakt_poz = new RBO_Field_Integer(_M("fakt_poz"));
                $fakt_poz->set_required();

                //typ  paszy 
                $feed = new RBO_Field_CommonData(_M('feed type'));
                $feed->from('Faktury/pasze')->set_required()->set_visible();

               return array($tucz_id,$fakt_poz,$feed); // - remember to return all defined fields
 
 
    }
    
}
class tuczkontraktowy_dostawaWarchlaka  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_dostawa_warchlaka';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


                // id kontraktu 
                $tucz_id = new RBO_Field_Integer(_M("ID tuczu"));
       
                //nazwa dokumentu
                $fakt_poz = new RBO_Field_Integer(_M("fakt_poz"));
                $fakt_poz->set_required();

                //ilosc   
                $amount = new RBO_Field_Integer(_M('amount'));
                $amount->set_required()->set_visible();

                $weight_on_drop = new RBO_Field_Float(_M('weight_on_drop'));
                $weight_on_drop->set_visible();

               return array($tucz_id,$fakt_poz,$amount,$weight_on_drop); // - remember to return all defined fields
 
 
    }
    
}
class tuczkontraktowy_faktury_tucz_inne  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_inne_faktury_tucz';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


                // id kontraktu 
                $tucz_id = new RBO_Field_Integer(_M("ID tuczu"));
       
                //nazwa dokumentu
                $fakt_poz = new RBO_Field_Integer(_M("fakt_poz"));
                $fakt_poz->set_required();

               return array($tucz_id,$fakt_poz); // - remember to return all defined fields
 
 
    }
    
}
class tuczkontraktowy_odbior_tucznika  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_odbior_tucznika';
 
    }
    function fields() { // - here you choose the fields to add to the record browser

                // id kontraktu 
                $tucz_id = new RBO_Field_Integer(_M("ID tuczu"));
       
                //nazwa dokumentu
                $fakt_poz = new RBO_Field_Integer(_M("fakt_poz"));
                $fakt_poz->set_required()->set_visible();

                //data odbioru
                $date_recived = new RBO_Field_Date(_M("date_recived"));
                $date_recived->set_required()->set_visible();

                //ilosc sztuk
                $amount = new RBO_Field_Integer(_M("amount"));
                $amount->set_required()->set_visible();

                //waga zywa brutto
                $weight_alive_brutto = new RBO_Field_Text(_M("weight_alive_brutto"));
                $weight_alive_brutto->set_required()->set_visible()->set_length(60);

                //mięsność
                $meatiness = new RBO_Field_Text(_M("meatiness"));
                $meatiness->set_required()->set_visible()->set_length(60);

                $konfiskaty = new RBO_Field_Integer(_M("konfiskaty"));
                $konfiskaty->set_visible();

                $premiowane = new RBO_Field_Integer(_M("premiowane"));
                $premiowane->set_visible();

                $suboptimal = new RBO_Field_Integer(_M("suboptimal"));
                $suboptimal->set_visible();

                $badWeight = new RBO_Field_Integer(_M("badweight"));
                $badWeight->set_visible();


               return array($tucz_id,$fakt_poz,$weight_alive_brutto,$amount,$meatiness,$date_recived , $konfiskaty, $premiowane, $suboptimal, $badWeight); // - remember to return all defined fields
 
 
    }
    
}

class tuczkontraktowy_transporty  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_transporty';
 
    }
    function fields() { // - here you choose the fields to add to the record browser

                // id kontraktu 
                $tucz_id = new RBO_Field_Integer(_M("ID tuczu"));
       
                //nazwa dokumentu
                $fakt_poz = new RBO_Field_Integer(_M("fakt_poz"));
                $fakt_poz->set_required();

                //data
                $data = new RBO_Field_Date(_M("date"));
                $data->set_required()->set_visible();
                //koszt transportu
                $transport_cost = new RBO_Field_Currency(_M("transport cost"));
                $transport_cost->set_required()->set_visible();

                //ilosc
                $amount = new RBO_Field_Integer(_M("amount"));
                $amount->set_required()->set_visible();

                //wartosc netto
                $netto = new RBO_Field_Currency(_M("netto"));
                $netto->set_required()->set_visible();

                //ubojnia
                $company = new RBO_Field_Select(_M("company"));
                $company->from('company')->set_crits_callback("tuczkontraktowyCommon::critOnlyUbojnia")->fields('company_name')->set_visible()->set_required();

               return array($tucz_id,$fakt_poz,$data,$transport_cost,$amount,$netto,$company); // - remember to return all defined fields
 
 
    }
    
}

class tuczkontraktowy_limity  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_limity';
 
    }
    function fields() { // - here you choose the fields to add to the record browser

                // id kontraktu 
                $tucz_id = new RBO_Field_Integer(_M("ID tuczu"));
       
                //typ paszy
                $feed = new RBO_Field_CommonData(_M('feed type'));
                $feed->from('Faktury/pasze')->set_required()->set_visible();
                //nazwa dokumentu
                $amount = new RBO_Field_Integer(_M("amount"));
                $amount->set_required()->set_visible();

               return array($tucz_id,$amount,$feed); // - remember to return all defined fields
 
 
    }
    
}

class tuczkontraktowy_inne  extends RBO_Recordset {

    function table_name() { // - choose a name for the table that will be stored in EPESI database

        return 'kontrakty_inne';

    }
    function fields() { // - here you choose the fields to add to the record browser

        // id kontraktu
        $tucz_id = new RBO_Field_Integer(_M("ID tuczu"));


        $fakt_poz = new RBO_Field_Integer(_M("fakt_poz"));
        $fakt_poz->set_required();

        //typ
        $other_type = new RBO_Field_CommonData(_M("other_type"));
        $other_type->from('Kontrakty/inne')->set_required()->set_visible();

        return array($tucz_id,$fakt_poz,$other_type); // - remember to return all defined fields


    }

}



?>