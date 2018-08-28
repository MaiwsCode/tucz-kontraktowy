<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczekontraktowe_tuczkontraktowy_Faktury_zakup_paszy  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_zakup_pasz';
 
    }
    function fields() { // - here you choose the fields to add to the record browser


                // id kontraktu 
                $contract_id = new RBO_Field_Select(_M("contract"));
                $contract_id->from('kontrakty')->fields('farmer' ,'data_start');
       
                //nazwa dokumentu
                $document = new RBO_Field_Text(_M("document"));
                $document->set_required()->set_visible()->set_length(180);

                //feed name 
                $feed = new RBO_Field_Select(_M('feed type'),'pasze');
                $feed->set_required()->set_visible();

                //data wystawienia
                $date = new RBO_Field_Date(_M("Date release"));
                $date->set_required()->set_visible();
       
                //ilosc 
                $amount = new RBO_Field_Integer(_M("amount"));
                $amount->set_required()->set_visible();
                
                //cena za kg
                $price_per_kg = new RBO_Field_Currency(_M("Cena za kilogram"));
                $price_per_kg->set_required()->set_visible();

                //wartosc netto
                $netto = new RBO_Field_Currency(_M("value netto"));
                $netto->set_required()->set_visible();

                //vat 
                $vat = new RBO_Field_Integer(_M("VAT"));
                $vat->set_required()->set_visible();

                //wartosc brutto
                $brutto = new RBO_Field_Currency(_M("value brutto"));
                $brutto->set_required()->set_visible();

                //nr faktury
                $fv = new RBO_Field_Text(_M("Nr faktury"));
                $fv->set_required()->set_visible()->set_length(180);
       
                //dostawca
                $deliverer = new RBO_Field_Select(_M('deliverer'));
                $deliverer->from('company')->fields('company_name')->set_visible()->set_required();
       
                //wartosc brutto
                $brutto_sale = new RBO_Field_Currency(_M("Kwota brutto sprzedaży"));
                $brutto_sale->set_required()->set_visible();



               return array($contract_id,$document,$feed,$amount,$price_per_kg,$vat,$brutto,
                            $date,$fv,$deliverer,$netto,$brutto_sale); // - remember to return all defined fields
 
 
    }
    
}

?>