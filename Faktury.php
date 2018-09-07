<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
 
class tuczkontraktowy_Faktury  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury';
 
    }
    function fields() { // - here you choose the fields to add to the record browser

        $fv_numer = new RBO_Field_Text(_M("Fv numer"));
        $fv_numer->set_required()->set_visible()->set_length(60);
        
        $date = new RBO_Field_Date(_M("date"));
        $date->set_required()->set_visible();

        $company = new RBO_Field_Select(_M("company"));
        $company->from('company')->fields('company_name')->set_visible()->set_required();

		$typ = new RBO_Field_CommonData('Typ faktury');
        $typ->from('Faktury/fv_type')->set_required()->set_visible();

        $status = new RBO_Field_CommonData(_M("status"));
        $status->from('Faktury/status')->set_required()->set_visible();
 
        return array($fv_numer,$date,$company,$typ,$status); // - remember to return all defined fields
 
 
    }
    
}

class tuczkontraktowy_Faktury_poz  extends RBO_Recordset {
 
    function table_name() { // - choose a name for the table that will be stored in EPESI database
 
        return 'kontrakty_faktury_pozycje';
 
    }
    function fields() { // - here you choose the fields to add to the record browser
        
        $fv_id =  new RBO_Field_Select(_M("Faktura"));
        $fv_id->from('kontrakty_faktury')->set_crits_callback('tuczkontraktowyCommon::critNoEqualEditable')->fields('fv_numer')->set_visible()->set_required();

        $typ = new RBO_Field_CommonData(_M('Typ faktury'));
        $typ->from('Faktury/fv_sub_type')->set_required()->set_visible();

        $amount = new RBO_Field_Integer(_M('amount'));
        $amount->set_required()->set_visible();

		$jedn = new RBO_Field_CommonData('Jednostki');
        $jedn->from('Faktury/jednostki')->set_required()->set_visible();

        $price = new RBO_Field_Currency(_M('Price'));
        $price->set_required()->set_visible();
 
		$vat = new RBO_Field_CommonData('VAT');
        $vat->from('Faktury/vat')->set_required()->set_visible();

        $description = new RBO_Field_Text(_M("Description"));
        $description->set_visible()->set_length(128);

        
        return array($fv_id,$description,$typ,$amount,$jedn,$price,$vat); // - remember to return all defined fields
 
 
    }
    
}

?>