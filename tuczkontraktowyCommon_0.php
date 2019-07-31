<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class tuczkontraktowyCommon extends ModuleCommon {


	public static function menu() {
		return array(__('Module') => array('__submenu__' => 1, __('Tucz kontraktowy') => array(
	    	'__icon__'=>'tucz.png','__icon_small__'=>'tucz.png'
			)));
	}
	public static function tuczeTabViewLabel() {
        return array('label' => 'Tucze', 'show' => true);
    }

	public static function critDates() {
		$date_start = date("Y-m-d");
		$newdate = strtotime ( '-14 days' , strtotime ( $date_start ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );


    	return array('>=planed_purchase_date' => $newdate);
	}

	public static function critOnlyUbojnia() {
    	return array('group' => array('ubojnia') );
	}
    public static function critOnlyVendor() {
        return array('group' => array('vendor') );
    }

	public static function critNoEqualEditable() {
    	return array('status' => array('editable'));
	}
	
	public static function critOnlyFarmers() {
    	return array('group' => array('farmer') );
	}
	public static function labelPlan() {
		return array('label' => 'Założenia', 'show' => true);
	}
	public static function labelTucze() {
		return array('label' => 'Warchlaki', 'show' => true);
	}
	public static function labelPasze() {
		return array('label' => 'Pasze', 'show' => true);
	}
    public static function upadkiLabel() {
        return array('label' => 'Upadki', 'show' => true);
    }
	public static function labelOdbiory() {
		return array('label' => 'Odbiory', 'show' => true);
	}
	public static function labelInne() {
		return array('label' => 'Inne', 'show' => true);
	}
	public static function limitsLabel() {
		return array('label' => 'Limity', 'show' => false);
	}
	public static function labelTransporty() {
		return array('label' => 'Transport', 'show' => true);
	}
	public static function labelExtra() {
		return array('label' => 'Dodatkowe informacje', 'show' => true);
	}
	// label zmien przed nastepnym reinstalem na pozycje_label
	public static function labelDetails() {
		return array('label' => 'Szczegóły faktury', 'show' => true);
	}
	public static function labelPrzewaga() {
		return array('label' => 'Przeważenie', 'show' => true);
	}
	public static function rolnikLabel() {
		return array('label' => 'Raport Rolnik', 'show' => true);
	}
	public static function szefowaLabel() {
		if(Base_AclCommon::i_am_sa() == "1" || Base_AclCommon::i_am_admin() == "1" ){
			return array('label' => 'Raport Szefowa', 'show' => true);
		}
		else{
			return array('label' => 'Raport Szefowa', 'show' => false);
		}
	}
    public static function fakturyLabel() {
        return array('label' => 'Powiązane faktury', 'show' => true);
    }
	public static function get_fv_ids($table_name, $tucz_id){
		$rbo = new RBO_RecordsetAccessor($table_name);
		$records = $rbo->get_records(array("id_tuczu" => $tucz_id),array(),array());
		$ids = array();
		foreach($records as $r){
			$ids[] = $r['fakt_poz'];
		}
		return $ids;
	}
	public static function autoselect_fv($str, $crits, $format_callback) {
        $str = explode(' ', trim($str));
        foreach ($str as $k=>$v)
            if ($v) {
                $v = "%$v%";
                $crits = Utils_RecordBrowserCommon::merge_crits($crits, array('(~fv_numer'=>$v));
            }
        $recs = Utils_RecordBrowserCommon::get_records('kontrakty_faktury', $crits, array(), array('fv_numer'=>'ASC'), 10);
        $ret = array();
        foreach($recs as $v) {
            $ret[$v['fv_numer']."__".$v['fv_numer']] = call_user_func($format_callback, $v, true);
        }
        return $ret;
    }
    public static function fv_format($record, $nolink=false){

        $ret = $record['fv_numer'];
        return $ret;
    }


	public static function view_upadki($record, $mode){
		if($mode == "adding" ){
			$record['id_tuczu'] = $_SESSION['tucz_id'];
			Epesi::js(
				'jq("#_id_tuczu__data").parent().css("display","none");'
			);
			Epesi::js('jq(".name").html("");
			jq(".name").html("<div>'.$_SESSION["display_current_name_view"].'</div>");');
			return $record;
		}
	}

	public static function view_wazenie($record,$mode){

	}

	//pozycje z faktur
	public static function on_add_details($record, $mode){
		if($mode == 'adding'){
		    $record['typ_faktury'] = $record['select'];
			if( $_SESSION['fv_mode']){
				$record['faktura'] = $_SESSION['fv_mode'];
				unset($_SESSION['fv_mode']);
			}

			Epesi::js('jq(".name").html("");
				jq(".name").html("<div> '. $_SESSION['display_current_name_view'].'</div>");');
			if($_SESSION['jedn'] == "j0"){
				$record['jednostki'] = 0;
			}
			
			return $record;
		}
		if($mode == "added" ) {
            $_SESSION['fakt_poz'] = $record['id'];
            $_SESSION['adding_type'] = $record['typ_faktury'];
            if ($record['typ_faktury'] == "T"){
                    Utils_RecordBrowserCommon::new_record(tuczkontraktowyCommon::table_names($record['typ_faktury']), array('id_tuczu' => $_SESSION['tucz_id'], 'fakt_poz' => $record['id'],
                        'date_recived' => date("Y-m-d"), 'price_netto'=> '0' , 'weight_alive_brutto' => '0', 'weight_meat' => '0', 'meatiness' =>0 , 'badweight' => 0 ,
                        "suboptimal" => 0, "premiowane" => 0, "konfiskaty" => 0));
            }
            if ($record['typ_faktury'] == "W") {
                Utils_RecordBrowserCommon::new_record(tuczkontraktowyCommon::table_names($record['typ_faktury']), array('id_tuczu' => $_SESSION['tucz_id'],
                                                        'fakt_poz' => $record['id'], 'amount' => 0, 'weight_on_drop' => 0));
            }
            if ($record['typ_faktury'] == "P") {
                Utils_RecordBrowserCommon::new_record(tuczkontraktowyCommon::table_names($record['typ_faktury']), array('id_tuczu' => $_SESSION['tucz_id'],
                    'fakt_poz' => $record['id'], 'feed_type' => 'starter' , 'weightcarempty' => 0 , 'weightcarfull' => 0 ));
            }
            if ($record['typ_faktury'] == "OTH") {
                Utils_RecordBrowserCommon::new_record(tuczkontraktowyCommon::table_names($record['typ_faktury']), array('id_tuczu' => $_SESSION['tucz_id'],
                    'fakt_poz' => $record['id'], 'type' => 'Wet'));
            }
            if ($record['typ_faktury'] == "TR") {
                Utils_RecordBrowserCommon::new_record(tuczkontraktowyCommon::table_names($record['typ_faktury']), array('id_tuczu' => $_SESSION['tucz_id'],
                    'fakt_poz' => $record['id'], 'date' => date("Y-m-d"),'amount' => '0', 'netto'=> 0));
            }
		}
		if($mode == 'editing'){
			$_SESSION['oldType'] = $record['typ_faktury'];
		}

        if($mode == "delete") {
            //get_records
            if ($record['typ_faktury']) {
                $table = tuczkontraktowyCommon::table_names($record['typ_faktury']);
                $records = Utils_RecordBrowserCommon::get_records($table, array('fakt_poz' => $record['id'], 'id_tuczu' => $_SESSION['tucz_id']), array(), array());
                foreach ($records as $r) {
                    Utils_RecordBrowserCommon::delete_record(
                        $table, $r['id']
                            );
                }
            }
        }

		if($mode == "display" ||  $mode ==  'editing' ){

			if(isset($_SESSION['display_current_name_view'])){
				Epesi::js('jq(".name").html("");
				jq(".name").html("<div> '. $_SESSION['display_current_name_view'].'</div>");');
			}else{
				Epesi::js('jq(".name").html("");
				jq(".name").html("<div> Pozycja faktury - '. Utils_RecordBrowserCommon::get_record(
					'kontrakty_faktury', $record['faktura'])['fv_numer'] .'</div>");');
				return $record;
			}

		}
	}
	public static function set_current_tucz($record, $mode){
		if($mode == "display"){
			$_SESSION['tucz_id'] = $record['id'];


		}
		if($mode == 'added'){
			/*if($record['data_end'] == null ){
				$date_end = strtotime ( '+90 days' , strtotime ( $record['data_start'] ));
				$date_end = date('Y-m-d', $date_end);				
				Utils_RecordBrowserCommon::update_record("kontrakty", $record['id'],
				array('data_end' =>  $date_end));
			}*/
		}
		if($mode == 'adding'){
				Epesi::js('jq(".name").html("");
				jq(".name").html("<div> Dodawanie nowego tuczu </div>");');	
				$record['status'] = "Planned";
				return $record;
		}
		if($mode == 'editing'){
			Epesi::js('jq(".name").html("");
			jq(".name").html("<div> Edytowanie danych do tuczu </div>");');	
			return $record;
	}
	}
	public static function view_zalozenia($record,$mode){
		if($mode == "editing" || $mode == 'adding'){
			$tucz_name = Utils_RecordBrowserCommon::get_record('kontrakty', $_SESSION['tucz_id']);
			$def = Utils_CommonDataCommon::get_array("Kontrakty/zalozenia_domyslne");			
			$feed = Utils_CommonDataCommon::get_array("Kontrakty/limity_tuczu_na_paszy");	
			Epesi::js('jq(".name").html("");
			jq(".name").html("<div> Dane do założeń tuczu - '.$tucz_name['name_number'].'</div>");');
			$record['id_tuczu'] = $_SESSION['tucz_id'];
			if($record['farmer'] == null){
				$record['farmer'] = $def['rolnik']."__2";
			}
			if($record['lose'] == null){
				$def['ubytek'] = str_replace(",", ".", $def['ubytek']);
				$record['lose'] = $def['ubytek'];
			}
			if($record['med'] == null){
				$record['med'] = $def['lekarz']."__2";
			}
			if($record['weight_pig_start'] == null){
				$record['weight_pig_start'] = $def['weight_pig_start'];
			}
			if($record['weight_pig_end'] == null){
				$record['weight_pig_end'] = $def['weight_pig_end'];
			}
			if($record['price_starter'] == null){
				$record['price_starter'] = $def['cena_starter'];
			}else {
                $enter_value = $record['price_starter'];
                $check_float = $enter_value;
                $index = strpos($check_float,".");
                if($index){
                    $check_float[$index] = ",";
                    $enter_value = $check_float;
                }
                $enter_value = preg_replace("/[^0-9 , . ]/", '', $enter_value);
                $record['price_starter'] =  $enter_value;

            }
			if($record['price_grower'] == null){
				$record['price_grower'] = $def['cena_grover'];
			}else {
                $enter_value = $record['price_grower'];
                $check_float = $enter_value;
                $index = strpos($check_float,".");
                if($index){
                    $check_float[$index] = ",";
                    $enter_value = $check_float;
                }
                $enter_value = preg_replace("/[^0-9 , . ]/", '', $enter_value);
                $record['price_grower'] =  $enter_value;

            }
			if($record['price_finisher'] == null){
				$record['price_finisher'] = $def['cena_finisher'];
			}else {
                $enter_value = $record['price_finisher'];
                $check_float = $enter_value;
                $index = strpos($check_float,".");
                if($index){
                    $check_float[$index] = ",";
                    $enter_value = $check_float;
                }
                $enter_value = preg_replace("/[^0-9 , . ]/", '', $enter_value);
                $record['price_finisher'] =  $enter_value;
            }
			if($record['starter_to'] == null){
				$record['starter_to'] = $feed['starter_grower'];
			}
			if($record['grower_to'] == null){
				$record['grower_to'] = $feed['grower_finisher'];
			}
			if($record['weight_pig_start'] == null){
				$record['weight_pig_start'] = $def['domyslna_waga_wejsciowa'];
			}
			if($record['weight_pig_end'] == null){
				$record['weight_pig_end'] = $def['domyslna_waga_wyj'];
			}
			return $record;
		}
		if($mode == "edited"){
			$def = Utils_CommonDataCommon::get_array("Kontrakty/zalozenia_domyslne");	
			$p1 = $record['price_starter'];
			$p2 = $record['price_grower'];
			$p3 = $record['price_finisher'];
			if($record['price_starter'] != null){
                $enter_value = $p1;
                $check_float = $enter_value;
                $index = strpos($check_float,".");
                if($index){
                    $check_float[$index] = ",";
                    $p1 = $check_float;
                }
                $enter_value = preg_replace("/[^0-9 , . ]/", '', $enter_value);
                $p1 = $enter_value;
			}
			if($record['price_grower'] != null){
                $enter_value = $p2;
                $check_float = $enter_value;
                $index = strpos($check_float,".");
                if($index){
                    $check_float[$index] = ",";
                    $p2 = $check_float;
                }
                $enter_value = preg_replace("/[^0-9 , . ]/", '', $enter_value);
                $p2 = $enter_value;
			}
			if($record['price_finisher'] != null){
                $enter_value = $p3;
                $check_float = $enter_value;
                $index = strpos($check_float,".");
                if($index){
                    $check_float[$index] = ",";
                    $p3 = $check_float;
                }
                $enter_value = preg_replace("/[^0-9 , . ]/", '', $enter_value);
                $p3 = $enter_value;
			}
			Utils_RecordBrowserCommon::update_record('kontrakty_zalozenia', $record['id'], array('price_starter' => $p1, 'price_grower' => $p2 , 'price_finisher' => $p3), $full_update=false, $date=null, $dont_notify=false);
		}
	}
	public static function view_transporty($record,$mode){
		if($mode == "adding"){
			$record['id_tuczu'] = $_SESSION['tucz_id'];
			$record['fakt_poz'] = $_SESSION['fakt_poz'];
			//tuczkontraktowyCommon::hide_no_editable_fields();
			return $record;
		}
	}

	public static function view_pasze($defaults, $mode){

	}
	public static function view_warchlak($defaults, $mode){
		if($mode == "display"){
			tuczkontraktowyCommon::hide_no_editable_fields();
		}
		if($mode == "adding"){
			$defaults['id_tuczu'] = $_SESSION['tucz_id'];
			$defaults['fakt_poz'] = $_SESSION['fakt_poz'];
			tuczkontraktowyCommon::hide_no_editable_fields();
			return $defaults;
		}
		if($mode == 'editing'){
			tuczkontraktowyCommon::hide_no_editable_fields();
		}
		if($mode == 'view'){
			tuczkontraktowyCommon::hide_no_editable_fields();
		}
		
	}
	public static function view_inne($record, $mode){
		if($mode == "adding"){
			$record['id_tuczu'] = $_SESSION['tucz_id'];
			$record['fakt_poz'] = $_SESSION['fakt_poz'];
			tuczkontraktowyCommon::hide_no_editable_fields();
			Epesi::js('jq(".name").html("");
				jq(".name").html("<div>'.$_SESSION["display_current_name_view"].'</div>");');
			return $record;
		}
		
	}

	public static function view_limity($record,$mode){
		if($mode == "adding"){
			$record['id_tuczu'] = $_SESSION['tucz_id'];
			$record['fakt_poz'] = $_SESSION['fakt_poz'];
			tuczkontraktowyCommon::hide_no_editable_fields();
			Epesi::js('jq(".name").html("");
				jq(".name").html("<div>'.$_SESSION["display_current_name_view"].'</div>");');
			return $record;
		}
		if($mode == "display"){
			tuczkontraktowyCommon::hide_no_editable_fields();
			Epesi::js('jq(".name").html("");
				jq(".name").html("<div>'.$_SESSION["display_current_name_view"].'</div>");');
		}

	}
	public static function view_odbior($record, $mode){
		if($mode == "adding"){
			$record['id_tuczu'] = $_SESSION['tucz_id'];
			$record['fakt_poz'] = $_SESSION['fakt_poz'];
			tuczkontraktowyCommon::hide_no_editable_fields();
			Epesi::js('jq(".name").html("");
				jq(".name").html("<div>'.$_SESSION["display_current_name_view"].'</div>");');
			return $record;
		}
		if($mode == "display"){
			tuczkontraktowyCommon::hide_no_editable_fields();
			Epesi::js('jq(".name").html("");
				jq(".name").html("<div>'.$_SESSION["display_current_name_view"].'</div>");');
		}

	}
	public static function hide_no_editable_fields(){
		Epesi::js('
			jq("#_id_tuczu__data").parent("tr").css("display","none");
			jq("#_fakt_poz__data").parent("tr").css("display","none");
		');

	}
    public static function table_names($table_id){
        $table_name = "";
        if($table_id == "W") {
            $table_name = "kontrakty_faktury_dostawa_warchlaka";
        }
        else if($table_id == "T") {
            $table_name = "kontrakty_faktury_odbior_tucznika";
        }
        else if($table_id == "P") {
            $table_name = "kontrakty_faktury_dostawa_paszy";
        }
        else if($table_id == "OTH"){
            $table_name = "kontrakty_inne";
        }
        else if($table_id == "TR"){
            $table_name = "kontrakty_faktury_transporty";
        }
        return $table_name;
    }


}
?>