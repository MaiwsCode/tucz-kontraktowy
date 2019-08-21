<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class tuczkontraktowyInstall extends ModuleInstall {

    public function install() {
        $ret = true;

        Base_ThemeCommon::install_default_theme($this->get_type());
        Base_LangCommon::install_translations($this->get_type());

        Utils_CommonDataCommon::new_array("Faktury/pasze", array('finisher' => 'Finisher' , 'starter' => 'Starter', 'grower' => 'Grower'));
        Utils_CommonDataCommon::new_array("Faktury/status", 
                                        array('editable' => 'W edycji' ,
                                              'complete' => 'Kompletna', 
                                              'aceppted' => 'Zatwierdzona'));
                                                
        Utils_CommonDataCommon::new_array("Faktury/fv_type", array('0' => 'Sprzedaż' , '1' => 'Zakup', '2' => 'Transport'));

        Utils_CommonDataCommon::new_array("Kontrakty/status", array('Planned' => 'Planowany' , 'Accepted' => 'Zatwierdzony',
         'InProgress' => 'W trakcie', 'Ended'=> 'Zakończony', 'Done' => "Rozliczony"));

        Utils_CommonDataCommon::new_array("Kontrakty/sr_zuzycie", array('1' => '2,80' , '2' => '2,85', '3' => '2,90'));
		
		Utils_CommonDataCommon::new_array("Kontrakty/zalozenia_domyslne", array(
		"domyslna_waga_wejsciowa" => "30",
		"domyslna_waga_wyj" => "125", "cena_starter" => "1,0000", "cena_grover" => "1,0000" ,
		"cena_finisher" => "1,0000" , "lekarz" => "10,00" , "ubytek" => "3" , "rolnik" => "37"));

        Utils_CommonDataCommon::new_array("Kontrakty/sr_zuzycie/1", array('starter' => '2' , 'grower' => '2,5', 'finisher' => '3,53'));
        Utils_CommonDataCommon::new_array("Kontrakty/sr_zuzycie/2", array('starter' => '2,1' , 'grower' => '2,55', 'finisher' => '3,56'));
        Utils_CommonDataCommon::new_array("Kontrakty/sr_zuzycie/3", array('starter' => '2,1' , 'grower' => '2,61', 'finisher' => '3,62'));

        Utils_CommonDataCommon::new_array("Kontrakty/limity_tuczu_na_paszy", array('starter_grower' => '45' , 'grower_finisher' => '80'));
        Utils_CommonDataCommon::new_array("Kontrakty/inne", array('Wet' => 'Weterynarz'));

        Utils_CommonDataCommon::new_array("Kontrakty/zaliczki/statusy", array('0' => 'Nierozliczony' , '1' => 'Rozliczony'));

        Utils_RecordBrowserCommon::new_addon('company', "tuczkontraktowy", 'loans',
            array('tuczkontraktowyCommon', 'loansLabel'));
         
        $fields = array(
            array('name' => _M('company'), 'type'=>'crm_company', 'param'=>array('field_type'=>'select',
                     'crits'=>array(), 'format'=>array('CRM_ContactsCommon','contact_format_no_company')),
                     'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_company'), 
                     'required'=>true, 'extra'=>false, 'visible'=>true, 'filter'=>true),
            array('name' => _M('tucz'), 'type'=>'select','param'=>"kontrakty::name_number",
                     'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_tucz')),
            array('name' => _M('note'),     'type'=>'text',  'param'=>256, 'required'=>false,  'extra'=>false, 'visible'=>true),
            array('name' => _M('value'),    'type'=>'currency', 'required'=>true, 'extra'=>false, 'visible'=>true),
            array('name' => _M('payment_deadline'), 'type'=>'date', 'required'=>true, 'extra'=>false, 'visible'=>true),
            array('name' => _M('status'),   'type'=>'commondata',  'param'=>array('Kontrakty/zaliczki/statusy'),
            'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_status'), 'required'=>true, 'extra'=>false, 'visible'=>true),
            array('name' => _M('comments'), 'type'=>'text',  'param'=>128, 'required'=>false, 'extra'=>false, 'visible'=>true),
        );
        Utils_RecordBrowserCommon::install_new_recordset('loans', $fields);
        
        Utils_RecordBrowserCommon::add_access('loans', 'view', 'ACCESS:employee');
        Utils_RecordBrowserCommon::add_access('loans','edit', 'ACCESS:employee');
        Utils_RecordBrowserCommon::add_access('loans','delete', 'ADMIN');
        Utils_RecordBrowserCommon::add_access('loans','add', 'ACCESS:employee');
         
        $fields = array(
            array('name' => _M('tucz'), 'type'=>'select','param'=>"kontrakty::name_number",
                     'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_autoTucz')),
            array('name' => _M('note'),     'type'=>'text',  'param'=>256, 'required'=>false,  'extra'=>false, 'visible'=>true),
            array('name' => _M('value'),    'type'=>'currency', 'required'=>true, 'extra'=>false, 'visible'=>true),
            array('name' => _M('payment_date'), 'type'=>'date', 'required'=>true, 'extra'=>false, 'visible'=>true),
            array('name' => _M('status'),   'type'=>'commondata',  'param'=>array('Kontrakty/zaliczki/statusy'),
            'QFfield_callback'=>array('tuczkontraktowyCommon', 'QFfield_status'), 'required'=>true, 'extra'=>false, 'visible'=>true),
            array('name' => _M('comments'), 'type'=>'text',  'param'=>128, 'required'=>false, 'extra'=>false, 'visible'=>true),
            );
        Utils_RecordBrowserCommon::install_new_recordset('kontrakty_advances', $fields);
        
        Utils_RecordBrowserCommon::add_access('kontrakty_advances', 'view', 'ACCESS:employee');
        Utils_RecordBrowserCommon::add_access('kontrakty_advances','edit', 'ACCESS:employee');
        Utils_RecordBrowserCommon::add_access('kontrakty_advances','delete', 'ADMIN');
        Utils_RecordBrowserCommon::add_access('kontrakty_advances','add', 'ACCESS:employee');
        

        $fields = array(
            array('name' => _M('tucz'), 'type'=>'integer'),
            array('name' => _M('pig_weight'),     'type'=>'text',  'param'=>64, 'required'=>false,  'extra'=>false, 'visible'=>true),
        );
            
        Utils_RecordBrowserCommon::install_new_recordset('kontrakty_extra_data', $fields);

        Utils_RecordBrowserCommon::add_access('kontrakty_extra_data', 'view', 'ACCESS:employee');
        Utils_RecordBrowserCommon::add_access('kontrakty_extra_data','edit', 'ACCESS:employee');
        Utils_RecordBrowserCommon::add_access('kontrakty_extra_data','delete', 'ADMIN');
        Utils_RecordBrowserCommon::add_access('kontrakty_extra_data','add', 'ACCESS:employee');

        Utils_CommonDataCommon::new_array("Faktury/fv_sub_type", array('T' => 'Tucznik' , 'TR' => "Transport",
        'W' => 'Warchlak', 'O' => 'Owca', 'P'=> 'Pasza', 'OTH'=> "Tucze - koszty inne"));


        Utils_CommonDataCommon::new_array("Faktury/jednostki", array('0' => 'Kg' , '1' => 'szt'));

        Utils_CommonDataCommon::new_array("Faktury/vat", array('5' => '5%%' , '8' => '8%%', '23' => '23%%'));

        //zalozenia
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'plan',
            array($this->get_type() . 'Common', 'labelPlan'));
        // 
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'tucze_list',
            array($this->get_type() . 'Common', 'labelTucze'));

        //
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'pasze_list',
            array($this->get_type() . 'Common', 'labelPasze'));

        //upadki
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'upadki_list',
            array($this->get_type() . 'Common', 'upadkiLabel'));

        //
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'odbiory_list',
            array($this->get_type() . 'Common', 'labelOdbiory'));

        //
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'inne_list',
            array($this->get_type() . 'Common', 'labelInne'));


        //ustawienie typu faktury
        Utils_RecordBrowserCommon::new_addon('kontrakty_faktury_pozycje', $this->get_type(), 'ExtraValue',
            array($this->get_type() . 'Common', 'labelExtra'));

        //przewazenia swiń
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'przewaga_view',
            array($this->get_type() . 'Common', 'labelPrzewaga'));

        //transporty
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'transporty_view',
            array($this->get_type() . 'Common', 'labelTransporty'));

        
        //limity
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'limits_list',
        array($this->get_type() . 'Common', 'limitsLabel'));

        //raport rolnik
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'raport_rolnik',
                array($this->get_type() . 'Common', 'rolnikLabel'));

        //raport szefowa
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'raport_szefowa',
            array($this->get_type() . 'Common', 'szefowaLabel'));

        //zaliczki
        Utils_RecordBrowserCommon::new_addon('kontrakty', "tuczkontraktowy", 'advances',
            array('tuczkontraktowyCommon', 'advancesLabel'));

        //raport faktury
        Utils_RecordBrowserCommon::new_addon('kontrakty', $this->get_type(), 'faktury_list',
            array($this->get_type() . 'Common', 'fakturyLabel'));
        //    
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_faktury_pozycje', 
                            array($this->get_type () . 'Common', 'on_add_details'));


        //widok pasz                   
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_faktury_dostawa_paszy', 
                            array($this->get_type () . 'Common', 'view_pasze'));

        //widok limitów                   
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_limity', 
                            array($this->get_type () . 'Common', 'view_limity'));
        //widok warchlaka                   
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_faktury_dostawa_warchlaka', 
                            array($this->get_type () . 'Common', 'view_warchlak'));
        
        //widok inne                   
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_faktury_inne_faktury_tucz', 
                            array($this->get_type () . 'Common', 'view_inne'));
        
        //widok odbior                   
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_faktury_odbior_tucznika', 
                            array($this->get_type () . 'Common', 'view_odbior'));        

        //widok transporty                   
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_faktury_transporty', 
                            array($this->get_type () . 'Common', 'view_transporty'));               

        //ustaw w sesji obecnie przeglądany tucz                   
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty', 
                            array($this->get_type () . 'Common', 'set_current_tucz')); 
                  
        //widok upadku
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_upadki', 
                            array($this->get_type () . 'Common', 'view_upadki'));
                                    
        //widok przewazenia
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_wazenie', 
                            array($this->get_type () . 'Common', 'view_wazenie'));

        //zaliczki                    
        Utils_RecordBrowserCommon::new_addon('company', "tuczkontraktowy", 'advances',
                        array('tuczkontraktowyCommon', 'advancesLabel')); 

        //kontrakty zalozenia                    
        Utils_RecordBrowserCommon::register_processing_callback('kontrakty_zalozenia', 
            array($this->get_type () . 'Common', 'view_zalozenia'));
        // wyłączyć klawisz usuń dla wszystkich poza administratorem, zarówno w widoku tabeli jak i w podglądzie tuczu  
        $table = new tuczkontraktowy_Kontrakty();
        $success = $table->install();  
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_Faktury();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_Faktury_poz();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_Upadki();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_Wazenie();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_dostawaPaszy();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_dostawaWarchlaka();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_faktury_tucz_inne();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_odbior_tucznika();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_transporty();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_limity();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_zalozenia();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new tuczkontraktowy_inne();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        return $ret;
    }

    public function uninstall() {

        Utils_CommonDataCommon::remove("Faktury/vat");
        Utils_CommonDataCommon::remove("Faktury/jednostki");
        Utils_CommonDataCommon::remove("Faktury/fv_type");
        Utils_CommonDataCommon::remove("Faktury/pasze");
        Utils_CommonDataCommon::remove("Faktury/fv_sub_type");
        Utils_CommonDataCommon::remove("Faktury/status");

        Utils_RecordBrowserCommon::delete_addon("kontrakty_faktury",$this->get_type(),'pozycje');
        Utils_RecordBrowserCommon::delete_addon("kontrakty",$this->get_type(),'pasze_list');
        Utils_RecordBrowserCommon::delete_addon("kontrakty",$this->get_type(),'odbiory_list');
        Utils_RecordBrowserCommon::delete_addon("kontrakty",$this->get_type(),'inne_list');
        Utils_RecordBrowserCommon::delete_addon("kontrakty_faktury_pozycje",$this->get_type(),'ExtraValue');
        Utils_RecordBrowserCommon::delete_addon("kontrakty",$this->get_type(),'przewaga_view');
        Utils_RecordBrowserCommon::delete_addon("kontrakty",$this->get_type(),'labelTucze');

        $table = new tuczkontraktowy_Kontrakty();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_Faktury();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_Faktury_poz();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_Upadki();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_Wazenie();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_dostawaPaszy();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_dostawaWarchlaka();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_faktury_tucz_inne();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_odbior_tucznika();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_transporty();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_limity();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_zalozenia();
        $success = $table->uninstall();
        $table = new tuczkontraktowy_inne();
        $success = $table->uninstall();
        Utils_RecordBrowserCommon::uninstall_recordset('kontrakty_advances');
        Utils_RecordBrowserCommon::uninstall_recordset('loans');
        Utils_RecordBrowserCommon::uninstall_recordset('kontrakty_extra_data');
        $ret = true;
        return $ret; 
    }

    public function requires($v) {

        return array(); 
    }
    public function info() { // Returns basic information about the module which will be available in the epesi Main Setup
		return array (
				'Author' => 'Mateusz Kostrzewski',
				'License' => 'MIT 1.0',
				'Description' => '' 
		);
	}
    public function version() {

        return array('1.1');
    }
    public function simple_setup() { // Indicates if this module should be visible on the module list in Main Setup's simple view
		return array (
				'package' => __ ( 'Tucze kontraktowe' ),
				'version' => '1.1'
		); // - now the module will be visible as "HelloWorld" in simple_view
	}

}

?>