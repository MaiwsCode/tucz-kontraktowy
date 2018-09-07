<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class tuczkontraktowy extends Module { 

    public function tucze_list($record){
        Base_ThemeCommon::install_default_theme($this->get_type());
		Base_LangCommon::install_translations($this->get_type());
        $help = "<ul style='text-align:left;'><li> W tym miejscu dodajemy dostawe warchlaków </li></ul>";
        print($help);
        $_SESSION['tucz_id'] = $record['id'];
        custom::set_header("TUCZ - ".$record['name_number']);
        custom::create_new_faktura();
        custom::addButton("kontrakty_faktury_pozycje","Dodaj dostawę warchlaka","W");
        $_SESSION['jedn'] = "j0";
        $_SESSION['display_current_name_view'] = "Dostawa";
        $rbo = new RBO_RecordsetAccessor("kontrakty_faktury_dostawa_warchlaka");
        $info = new RBO_RecordsetAccessor("kontrakty_faktury_pozycje");
        $warchlaki = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array());
        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Warchlaki');
        $gb->set_table_columns(
            array(
                array('name'=>'Warchlaki', 'width'=>12),				
				array('name'=>'Waga', 'width'=>12),
				array('name'=>'Cena', 'width'=>12),
				array('name'=>'Ilość (szt)', 'width'=>12),
				array('name'=>'Waga jednostkowa', 'width'=>12),
				array('name'=>'Cena jednostkowa', 'width'=>12),
				array('name'=>'Waga z rozładunku', 'width'=>12),
				array('name'=>'Ubytek', 'width'=>12),

            )
        );
        foreach($warchlaki as $p){	
				$r = $info->get_record($p['fakt_poz']);	
				$ub = "";
				$we = "";
				if($p['weight_on_drop']){
					$we = $p['weight_on_drop'];
					$ub = ($r['amount'] - $p['weight_on_drop'] ) / $p['amount'];
				}
			
                $gb->add_row( $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false),
							  $r['amount']." kg",
							  $r->get_val('price'),
							  $p->get_val('amount'),
                    str_replace(".",",",round(($r['amount'] / $p['amount']),2))." kg/szt" ,
                    str_replace(".",",",round(($r['price'] / $p['amount']),2)). " zł/szt" ,
							  $we,
                    str_replace(".",",",round($ub,2)));
            
        }
        $this->display_module( $gb );
    }

    public function pasze_list($record){
        Base_ThemeCommon::install_default_theme($this->get_type());
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy dostawe pasz</li></ul>";
        print($help);
        custom::set_header("TUCZ - ".$record['name_number']);
        custom::create_new_faktura();
        $exist_types = [];
        $exist_amount = [];
        $price_avg = [];
        $feed_count  = [];
        $price_avg_weight = [];
        custom::addButton("kontrakty_faktury_pozycje","Dodaj dostawę paszy","P");
        $_SESSION['jedn'] = "j0";
        $_SESSION['display_current_name_view'] = "Pasze";
        $rbo = new RBO_RecordsetAccessor("kontrakty_faktury_dostawa_paszy");
        $info = new RBO_RecordsetAccessor("kontrakty_faktury_pozycje");
        $pasze = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array('feed_type'=>'DESC'));
        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Pasze');
        $gb->set_table_columns(
            array(
                array('name'=>'Pasza', 'width'=>10 ),
                array('name'=>'Ilość', 'width'=>45),
                array('name'=>'Cena', 'width'=>45),

            )
        );
        foreach($pasze as $p){
            $feed = $p['feed_type'];
            $exist_types[$feed] =  $feed;
            $feed_count[$feed] += 1;
            $extra = $info->get_record($p['fakt_poz']);
            $exist_amount[$feed] += $extra['amount'];
            $price_avg_weight[$feed] += ($extra['amount'] * floatval(str_replace(",",".",substr($extra['price'], 0, -3))));

            $price_avg[$feed] += floatval(str_replace(",",".",substr($extra['price'], 0, -3)));
            $gb->add_row(
                $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false)." - ".$p['feed_type'],
                $extra['amount']." kg",
                $extra->get_val('price')
            );
        }   
        $this->display_module( $gb );

        foreach ($exist_types as $type){
            $rbo_limits = new RBO_RecordsetAccessor("kontrakty_limity");
            $limits = $rbo_limits->get_records(array('id_tuczu' => $record['id'], 'feed_type' => $type),array(),array());
            $price_avg[$type] = $price_avg[$type] / $feed_count[$type];
            $price_avg_weight[$type] = $price_avg_weight[$type] / $exist_amount[$type];
            $price_avg_weight[$type] = round($price_avg_weight[$type], 4);
            $price_avg[$type]  = str_replace(".","," ,$price_avg[$type]);
            $price_avg_weight[$type] = str_replace(".","," ,$price_avg_weight[$type]);
            print("<table style='background: rgb(255,255,255);
                    background: linear-gradient(252deg, rgba(255,255,255,1) 0%, rgba(244,244,244,0.5606617647058824) 0%,
                    rgba(255,255,255,0.7511379551820728) 100%);text-align:center;' class='ttable'>
                            <tr>
                                <td>".ucfirst($type)."</td>
                                <td> Średnia cena: ". $price_avg[$type] ." zł </td>
                                <td> Średnia cena ważona: ".$price_avg_weight[$type]." </td>
                            </tr>");
            foreach ($limits as $l){
                print("<tr><td colspan='3'>".custom::createProgressBar(($exist_amount[$l['feed_type']]*100) / $l['amount'] )."</td></tr>");
                print("<tr><td></td><td><h3>".$exist_amount[$l['feed_type']]. " / ".$l['amount']."</h3></td><td></td></tr>");
            }
            print("</table>");
        }



    }

    public function transporty_view($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy koszty za transport</li></ul>";
        print($help);
        custom::set_header("TUCZ - ".$record['name_number']);
        custom::create_new_faktura();
        $_SESSION['display_current_name_view'] = "Transporty";
        custom::addButton("kontrakty_faktury_pozycje","Dodaj transport","");
        $_SESSION['adding_type'] = 'transport';

        $rbo = new RBO_RecordsetAccessor("kontrakty_faktury_transporty");
        $info = new RBO_RecordsetAccessor("kontrakty_faktury_pozycje");
        $trans = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array());
        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Pasze');
        $gb->set_table_columns(
            array(
                array('name'=>'Sprzedaż', 'width'=>10 , ),
                array('name'=>'Szczegóły', 'width'=>90),
            )
        );
        foreach($trans as $p){
            $gb->add_row( $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false),
            $p->create_default_linked_label(false, false));
        
        }   
        $this->display_module( $gb );
    }

    public function odbiory_list($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy odbiór tuczników</li></ul>";
        print($help);
        custom::set_header("TUCZ - ".$record['name_number']);
        custom::create_new_faktura();

        $_SESSION['display_current_name_view'] = "Odbiory";
        custom::addButton("kontrakty_faktury_pozycje","Dodaj odbiór tucznika","T");
        $_SESSION['jedn'] = "j0";


        //tuczniki
        $rbo = new RBO_RecordsetAccessor("kontrakty_faktury_odbior_tucznika");
        $info = new RBO_RecordsetAccessor("kontrakty_faktury_pozycje");
        $odbior = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array());
        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Odbiory');

        //warchlaki
        $rbo_pigs = new RBO_RecordsetAccessor("kontrakty_faktury_dostawa_warchlaka");
        $pigs = $rbo_pigs->get_records(array('id_tuczu' => $record['id']),array(),array());
        $pigs_amount = 0;
        foreach ($pigs as $pig){
            $pigs_amount += $pig['amount'];
        }
        //updatki
        $fall_rbo = new RBO_RecordsetAccessor("kontrakty_upadki");
        $falls = $fall_rbo->get_records(array('id_tuczu' => $record['id']),array(),array());
        $falls_amount = 0;
        foreach ($falls as $fall){
            $falls_amount += $fall['amount_fall'];
        }

        $recived = 0;
        $difference = 0;
        $avg_weight = 0;
        $sum_alive_brutto = 0;
        $sum_weight_on_fakt = 0;
        $gb->set_table_columns(
            array(
                array('name'=>'Odbiory', 'width'=>20),
                array('name'=>'Data odbioru', 'width'=>20),
                array('name'=>'Ilość sztuk', 'width'=>20),
                array('name'=>'Waga żywa brutto', 'width'=>20),
                array('name'=>'Mięsność', 'width'=>20),
            )
        );
        foreach($odbior as $p){
            $extra = $info->get_record($p['fakt_poz']);
            $sum_weight_on_fakt += $extra['amount'];
            $recived += $p['amount'];
            $sum_alive_brutto += $p['weight_alive_brutto'];
            $gb->add_row( $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false),
                    $p->get_val("date_recived"),
                    $p['amount']." szt.",
                    $p['weight_alive_brutto']." kg",
                    $p['meatiness']."%"
                );
        
        }
        $this->display_module( $gb );

        $difference = $pigs_amount - $falls_amount - $recived;
        $f1 = str_replace(".",",",round(($sum_alive_brutto / $recived),4));
        $f2 = str_replace(".",",",round(($sum_weight_on_fakt / $sum_alive_brutto),4));
        print("<table style='background: rgb(255,255,255);
                    background: linear-gradient(252deg, rgba(255,255,255,1) 0%, rgba(244,244,244,0.5606617647058824) 0%,
                    rgba(255,255,255,0.7511379551820728) 100%);text-align:center;width:60%;' class='ttable'>");
        print("<tr><td>Suma oddanych</td><td> Różnica </td> <td> Średnia waga oddanej sztuki </td><td> Średnia wydajność </td></tr>");
        print("<tr><td> $recived </td><td> $difference </td> <td> ".$f1." </td><td> ". $f2 ." </td></tr>");
        print("</table>");






    }

    public function inne_list($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy inne faktury jak np. Weterynarz </li></ul>";
        print($help);
        custom::set_header("TUCZ - ".$record['name_number']);
        custom::create_new_faktura();
        $_SESSION['display_current_name_view'] = "Inne faktury";
        custom::addButton("kontrakty_faktury_pozycje","Dodaj szczegóły","");
      //  $_SESSION['adding_type'] = 'inne';

        $rbo = new RBO_RecordsetAccessor("kontrakty_inne");
        $info = new RBO_RecordsetAccessor("kontrakty_faktury_pozycje");
        $inne = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array("other_type"=>"DESC"));
        $rbo_pigs = new RBO_RecordsetAccessor("kontrakty_faktury_dostawa_warchlaka");
        $pigs = $rbo_pigs->get_records(array('id_tuczu' => $record['id']),array(),array());
        $pigs_amount = 0;
        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Inne');
        $gb->set_table_columns(
            array(
                array('name'=>'Inne', 'width'=>25),
                array('name'=>'Typ', 'width'=>25),
                array('name'=>'Cena', 'width'=>25),
                array('name'=>'Ilość', 'width'=>25),
            )
        );
        $types = [];
        foreach ($pigs as $pig){
            $pigs_amount += $pig['amount'];
        }
        foreach($inne as $p){
            $extra = $info->get_record($p['fakt_poz']);
            $types[$p['other_type']]['name'] = $p->get_val('other_type');
            $types[$p['other_type']]['price'] += (floatval(str_replace(",",".",substr($extra['price'], 0, -3)))) * $extra['amount'];
            $gb->add_row( $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false),
                $p->get_val('other_type'),
                $extra->get_val("price"),
                $extra['amount']
                );
        
        }
        $this->display_module( $gb );
        print("<table style='background: rgb(255,255,255);
                    background: linear-gradient(252deg, rgba(255,255,255,1) 0%, rgba(244,244,244,0.5606617647058824) 0%,
                    rgba(255,255,255,0.7511379551820728) 100%);text-align:center;width:60%;' class='ttable'>");
        print("<tr><td>Typ</td><td> Łączny koszt </td> <td> Cena na sztuke warchlaka </td></tr>");
        foreach($types as $type){
            if($pigs_amount > 0) {
                $per_one = $type['price'] / $pigs_amount;
                $per_one .= " zł";
                $per_one = str_replace(".",",",$per_one);
            }else{
                $per_one = "Brak danych w zakładce dostawa";
            }
            print("<tr><td>".$type['name']."</td><td> ".str_replace(".",",",$type['price'])." zł </td><td> $per_one  </td></tr>");

        }
        print("</table>");
    }

    public function ExtraValue($record){
        if($record['typ_faktury'] == "W" || $record['typ_faktury'] == "T" || $record['typ_faktury'] == "P" || $record['typ_faktury'] == "OTH" ) {
            $form = $this->init_module('Libs/QuickForm');
            $rbo = new RBO_RecordsetAccessor(custom::table_names($record['typ_faktury']));
            $r = null;
            $record_exist = false;
            if ($r = $rbo->get_records(array('fakt_poz' => $record['id'], 'id_tuczu' => $_SESSION['tucz_id']), array(), array())) {
                foreach ($r as $rec) {
                    $r = $rec;
                    $record_exist = true;
                    break;
                }
            } else {
                $r = $rbo->new_record();
            }
            $fields = custom::table_fields($record['typ_faktury']);
            foreach ($fields as $field) {
                if ($record_exist) {
                    if ($field['type'] == 'date') {
                        $form->setDefaults(array($field['name'] => date("d-m-Y", strtotime($r[$field['name']]))));
                    }
                    if ($field['type'] == 'select') {
                        $form->addElement($field['type'], $field['name'], __($field['name']), $field['options']);
                        $form->setDefaults(array($field['name'] => $r[$field['name']]));
                    } else {
                        $form->addElement($field['type'], $field['name'], __($field['name']), array('value' => $r[$field['name']]));
                    }
                } else {
                    if ($field['type'] == 'select') {
                        $form->addElement($field['type'], $field['name'], __($field['name']), $field['options']);
                    } else {
                        $form->addElement($field['type'], $field['name'], __($field['name']));
                    }
                    if ($field['type'] == 'date') {
                        $form->setDefaults(array($field['name'] => date("d-m-Y")));
                    }
                }
                if ($field['rule']) {
                    $form->addRule($field['name'], $field['msg'], $field['rule']);
                }
            }
            $form->addElement('submit', 'submit', 'Dodaj/Edytuj');
            $form->display_as_column();

            if ($form->validate_with_message("Zmiany zapisane", "Musisz podać odpowidni typ danych w polach")) {
                $values = $form->exportValues();
                if ($values['date_recived']) {
                    $values['date_recived'] = $values['date_recived']['Y'] . "-" . $values['date_recived']['M'] . "-" . $values['date_recived']['d'];
                }
                if ($record_exist) {
                    //edit
                    $updated_fields = array();
                    $values['id_tuczu'] = $_SESSION['tucz_id'];
                    $values['fakt_poz'] = $record['id'];
                    foreach ($fields as $field) {
                        $updated_fields[$field['name']] = $values[$field['name']];
                    }
                    Utils_RecordBrowserCommon::update_record(
                        custom::table_names($record['typ_faktury']), $r['id'], $updated_fields

                    );
                } else {
                    //add new
                    $values['id_tuczu'] = $_SESSION['tucz_id'];
                    $values['fakt_poz'] = $record['id'];
                    foreach ($fields as $field) {
                        $r[$field['name']] = $values[$field['name']];
                    }
                    $r->save();
                }
            }
        }
    }
    public function przewaga_view($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy kontrolne przeważania</li>
                  <li> Można edytować już istniejące przeważenia wybierając odpowiednią date i wpisując w poszczególne pola nowe wagi następnie 
                  zatwierdzić to przyciskiem zapisz  </li>
        </ul>";
        print($help);
        custom::create_new_faktura();
        custom::set_header("TUCZ - ".$record['name_number']);
            $rbo = new RBO_RecordsetAccessor("kontrakty_wazenie");
            $daty =  DB::GetAll("SELECT DISTINCT f_date_weight FROM kontrakty_wazenie_data_1 WHERE f_id_tuczu = " .$record['id']. "  ORDER BY f_date_weight");
            $gb = &$this->init_module('Utils/GenericBrowser', null, 'Ważenie');
            $gb->set_table_columns(
                array(
                    array('name'=>'Ważenia', 'width'=>30),
                    array('name'=>'Ile dni później', 'width'=>30),
                )
            );
            for($i=0;$i<count($daty);$i++){
                if($i<1){
                    $gb->add_row(   
                    "<a style='margin-left:10px;' ". 
                        $this->create_href ( array ('action' => 'view_record' , 
                                                    'wazenie' => $daty[$i][0])) .">". $daty[$i][0] ."</a>", '---'
                    );
                }else{
                    $now = $daty[$i][0];
                    $before = $daty[($i-1)][0];
                    $days = strtotime($now) - strtotime($before);
                    $days= floor($days/(60*60*24));
                    $gb->add_row(   
                        "<a style='margin-left:10px;' ". 
                            $this->create_href ( array ( 'action' => 'view_record', 'wazenie' => $daty[$i][0])) .">". $daty[$i][0] ."</a>", ($days )

                        );
                }
            }
            $gb->add_row(   
                "<a  style='margin-left:10px;'". $this->create_href ( array ('action' => 'view_all')) ."> Zestawienie wszystkich przeważeń </a>", ''
            );
            $this->display_module( $gb );
        if($_REQUEST['action'] == 'view_record'){
            custom::openButtonsPanel();     
            custom::button("action=show","Wstecz");
            custom::closeButtonsPanel();
            $rbo = new RBO_RecordsetAccessor("kontrakty_wazenie");
            $waga = $rbo->get_records(array('id_tuczu' => $record['id'] ,'date_weight' => $_REQUEST['wazenie'] ),array(),array('pig_number'=> "ASC"));
            $gb = &$this->init_module('Utils/GenericBrowser', null, 'Ważenie');
            $gb->set_table_columns(
                array( 
                    array('name'=>'Numer Świni', 'width'=>30),
                    array('name'=>'Data ważenia', 'width'=>30),
                    array('name'=>'Waga', 'width'=>30),
                )
            );
            foreach($waga as $p){
                $gb->add_row(   $p->get_val('pig_number'),
                                $p->get_val('date_weight'),
                                "<span style='margin-left:5px;' >".$p->record_link($p['weight'])."</span>");
            
            }
            $this->display_module( $gb );
        }
        else if($_REQUEST['action'] == 'view_all'){
            custom::openButtonsPanel();     
            custom::button("action=show","Wstecz");
            custom::closeButtonsPanel();
            $daty =  DB::GetAll("SELECT DISTINCT f_date_weight FROM kontrakty_wazenie_data_1 WHERE f_id_tuczu = " .$record['id']. "  ORDER BY f_date_weight");
            $width = 100 / (count($daty[$i][0]) + 1);

            $next_days =array();
            for($i =0;$i<count($daty);$i++){
                $now = $daty[$i][0];
                $before = $daty[($i-1)][0];
                $days = strtotime($now) - strtotime($before);
                $days= floor($days/(60*60*24));
                $next_days[] = $days;
            }
            $fields = array();
            $fields[0] = array('name'=> 'Nr świni', 'width' =>$width );
            for($i=0;$i<count($daty);$i++){
                $fields[] = array('name'=> $daty[$i][0] , 'width' =>$width );
            }
            $rbo = new RBO_RecordsetAccessor("kontrakty_wazenie");
            $waga = $rbo->get_records(array('id_tuczu' => $record['id'] ),array(),array('pig_number'=> "ASC"));
            $gb = &$this->init_module('Utils/GenericBrowser', null, 'Ważenie');
            $gb->set_table_columns(
               $fields
            );
            for($i = 1;$i<=50;$i++){
                $row = array();
                $row[] = $i;

                for($y=0;$y<count($daty);$y++){
                    $pig = $rbo->get_records(array('id_tuczu' => $record['id'],
                        'pig_number' => $i, 'date_weight' => $daty[$y][0]),array(),array('pig_number'=> "ASC"));
                    if($pig){
                        foreach($pig as $p){
                            if($y > 0){
                                $grow = (intval($p['weight']) - $before);
                                $diff = $grow / $next_days[$y];
                                $diff = round($diff,3);
                                if($diff < 0.8){
                                    $diff = " <span style='color:red;border-left:1px solid grey;'>&nbsp; ". $diff . " Kg/dzień</span>";
                                }else{
                                    $diff = " <span style='color:green;border-left:1px solid grey;'>&nbsp; ". $diff . " Kg/dzień</span>";
                                }
                               /* $fields = array(
                                    'Przyrost' => $grow." Kg",
                                    'Średni przyrost' => $diff . " Kg/dzień");*/
                            }else{$diff = "";}
                            $row[] = "<span style='margin-left:5px;' >".$p->record_link($p['weight'])."</span> ". $diff;
                            $before = intval($p['weight']);
                        }
                    }else{
                        $row[] = "";
                    }
                }
                $gb->add_row_array($row);
            }
           
            $this->display_module( $gb );

        }
            $form = $this->init_module('Libs/QuickForm');
            $form->addElement('date', 'data', 'Data' );
            for($i = 1;$i<=50;$i++){
                $form->addElement('text', 'pig_'.$i, 'Nr świni '.$i." <span style='text-align:right;right:0;'> WAGA: </span>");
            }
            $form->addElement('submit', 'submit', 'Dodaj');
            print("<h3> Dodaj przeważenia </h3>");
            $form->display_as_column();
            print("<BR><BR><BR>");
            if ($form->validate()){
                $array_fields = $form->exportValues();
                $date = $array_fields['data']['Y']."-".$array_fields['data']['M']."-".$array_fields['data']['d'];
                for($i = 1;$i<=50;$i++){
                    if(strlen($array_fields['pig_'.$i]) > 0){
                        $id = $i;
                        $tucz_id = $record['id'];
                        $rec = Utils_RecordBrowserCommon::get_records("kontrakty_wazenie", 
                            array('pig_number' => $id, 'date_weight' => $date, 'id_tuczu' => $tucz_id ));
                        if(count($rec) > 0 ){
                            foreach($rec as $r){
                                $rec = $r;
                                break;
                            }
                        }
                        else{
                            $rec = null;
                        }     
                        if($rec == null){
                            Utils_RecordBrowserCommon::new_record("kontrakty_wazenie", 
                            array('id_tuczu' => $tucz_id, 'pig_number' => $id ,
                                'date_weight' => $date , 'weight' =>  $array_fields['pig_'.$i]));
                        }else{
                            Utils_RecordBrowserCommon::update_record("kontrakty_wazenie", $rec['id'],
                            array('weight' =>  $array_fields['pig_'.$i]));
                        }
                }
            }
            location();
        }
        
    }
    public function plan($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> Aby ustawić wartości domyślne dla pól należy wprowadzić wartości klikając u góry przycisk 'Utwórz nowy lub edytuj' </li></ul>";
        print($help);
        custom::create_new_faktura();
        custom::set_header("TUCZ - ".$record['name_number']);
       // Base_ThemeCommon::install_default_theme($this->get_type());
        $amount = 0;
        $_id = "none";
        $pigs = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_dostawa_warchlaka",array("id_tuczu" => $record['id']));
        foreach($pigs as $pig){
            $amount += $pig['amount'];
        }

        $zal = Utils_RecordBrowserCommon::get_records("kontrakty_zalozenia", array('id_tuczu' => $record['id']));
        if($zal != Null){
            foreach($zal as $z){
                $zal = $z;
                $_id = $zal['id'];
                break;
            }
        }else{
            $zal = "None";
        }
        if($_id != "none"){
            Base_ActionBarCommon::add(
                'add',
                __('Utwórz nowy lub edytuj'), 
                Utils_RecordBrowserCommon::create_record_href('kontrakty_zalozenia',$id=$_id,'edit'),
                null,
                $x
            );
        }else{
            Base_ActionBarCommon::add(
                'add',
                __('Utwórz nowy lub edytuj'), 
                Utils_RecordBrowserCommon::create_new_record_href('kontrakty_zalozenia',$def = array(), $id='none'),
                null,
                $x
            );
        }
        $theme = $this->init_module('Base/Theme');
		$limits = Utils_CommonDataCommon::get_array("Kontrakty/limity_tuczu_na_paszy"); 
        if($zal != "None"){
            $avg_usage = Utils_CommonDataCommon::get_array("Kontrakty/sr_zuzycie/".$zal['avg_usage']); 
            $selected_plan = Utils_CommonDataCommon::get_array("Kontrakty/sr_zuzycie");
            $selected_plan = $selected_plan[$zal['avg_usage']];
			$zal['price_pig'] = substr($zal['price_pig'], 0, -3);
            $zal['farmer'] = substr($zal['farmer'], 0, -3);
			$zal['med'] = substr($zal['med'], 0, -3);
			$limits['starter_grower'] = $zal['starter_to'];
			$limits['grower_finisher'] = $zal['grower_to'];
            $theme->assign("selected_plan",$selected_plan);
            $theme->assign("avg_usage",$avg_usage);			
			
		}else{
			$zal  = [];
			$def = Utils_CommonDataCommon::get_array("Kontrakty/zalozenia_domyslne");
			$zal['weight_pig_start'] = $def['domyslna_waga_wejsciowa'];
			$zal['weight_pig_end'] = $def['domyslna_waga_wyj'];
			$zal['price_starter'] = $def['cena_starter'];
			$zal['price_grower'] = $def['cena_grover'];
			$zal['price_finisher'] = $def['cena_finisher'];
			$zal['med'] = $def['lekarz'];
			$zal['lose'] = $def['ubytek'];
			$zal['farmer'] = $def['rolnik'];
		}
        $theme->assign("limits",$limits);
        $record['farmer'] = Utils_RecordBrowserCommon::get_record("company",$record['farmer'])['company_name'];
        $record['waga_przy_wstawieniu'] = substr($record['waga_przy_wstawieniu'], 0, -3);
        $theme->assign("amount",$amount);
        $theme->assign("tucz",$record);
        $theme->assign("zalozenie",$zal);
        $theme->display();
        Epesi::js('
        function calc(){
            //starter

            var inputs = jq("input[type=text]");
            for(var i =0;i<inputs.length;i++){
                var value = jq(inputs[i]).val();
                var isComm = value.toString().search(",");
                if(isComm > 0){
                    jq(inputs[i]).val(value.toString().replace(",","."));
                }
            }
	
			//starter
            var from = parseInt(jq("#st_to").val());
            var to = parseInt(jq("#weight_start").val());
            var ret1 = (from-to) * jq("#st_mp").val();
            ret1 = parseFloat(ret1).toFixed(4);
            jq("#feed_st").children("span").text(ret1);

            //grower
            var from = parseInt(jq("#gr_to").val());
            var to = parseInt(jq("#gr_from").val());
            var ret2 = (from-to) * jq("#gr_mp").val();
            ret2 = parseFloat(ret2).toFixed(4);
            jq("#feed_grow").children("span").text(ret2);

            //finisher 
            var from = parseInt(jq("#weight_end").val());
            var to = parseInt(jq("#fin_from").val());
            var ret3 = (from-to) * jq("#fin_mp").val();
            ret3 = parseFloat(ret3).toFixed(4);
            jq("#feed_fin").children("span").text(ret3);

            //suma
            var sum = 0;
            sum = parseFloat(ret1) + parseFloat(ret2) + parseFloat(ret3);
            sum = parseFloat(sum).toFixed(2);
            jq("#feed_sum").children("span").text(sum);

            //waga paszy
            var st_amount =  jq("#feed_st").children("span").text();
            var gr_amount =  jq("#feed_grow").children("span").text()
            var fin_amount = jq("#feed_fin").children("span").text();

            //cena paszy
            var st_price =  jq("#price_st").val();
            var gr_price =  jq("#price_gr").val();
            var fin_price = jq("#price_fin").val();

            var feed_price = (parseFloat(st_amount) * parseFloat(st_price)) +  (parseFloat(gr_amount) * parseFloat(gr_price)) +  
            (parseFloat(fin_amount) * parseFloat(fin_price)) ;
            feed_price = feed_price / (parseFloat(st_amount) + parseFloat(gr_amount) + parseFloat(fin_amount));
            jq("#price_feed").val(parseFloat(feed_price).toFixed(4).toString().replace(".",","));

            //koszt sztuki netto
            var farmer = parseFloat(jq("#farmer").val());
            var medi = parseFloat(jq("#med").val());
            var lose = parseFloat(jq("#lose").val());
            var pig_price = parseFloat(jq("#price_pig").val());
            var netto_price = (pig_price + (feed_price) / 2) / ( (100-lose)/100)  + farmer + medi + (feed_price / 2);
            netto_price = parseFloat(netto_price).toFixed(2).toString().replace(".",",");
            jq("#price_netto").text(netto_price);

            //min cena tucznika
            var wyj = (netto_price).toString().replace(",",".") / parseInt(jq("#weight_end").val());
            var wbc = wyj / 0.78;
            jq("#wbc").text(parseFloat(wbc).toFixed(2).toString().replace(".",",") );
            jq("#price_netto_per_one").text(parseFloat(wyj).toFixed(2).toString().replace(".",",") );

            //pasze zakontraktowane
            var p1 = jq("#st_kontr").text(Number(parseFloat(jq("#feed_st").children("span").text()) * parseFloat(jq("#szt").val())).toLocaleString());
            var p2 =jq("#gr_kontr").text(Number(parseFloat(jq("#feed_grow").children("span").text()) * parseFloat(jq("#szt").val())).toLocaleString());
            var p3 =jq("#fin_kontr").text(Number(parseFloat(jq("#feed_fin").children("span").text()) * parseFloat(jq("#szt").val())).toLocaleString());
            jq("#all_feed_kontr").text(Number(parseFloat(jq("#feed_sum").children("span").text()) * parseFloat(jq("#szt").val())).toLocaleString());
            
            var inputs = jq("input[type=text]");
            for(var i =0;i<inputs.length;i++){
                var value = jq(inputs[i]).val();
                var isComm = value.toString().search(".");
                if(isComm != -1){
                    jq(inputs[i]).val(value.toString().replace(".",","));
                }
            }
        }
            jq("input").on("input", function(){
               calc();
            });
            calc();
        ');

    }

    public function limits_list($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy limity pasz</li></ul>";
        print($help);
        custom::create_new_faktura();
        custom::set_header("TUCZ - ".$record['name_number']);
        $_SESSION['display_current_name_view'] = "Limity";
        custom::addButton("kontrakty_limity","Dodaj limity paszy","");
        $rbo = new RBO_RecordsetAccessor("kontrakty_limity");
        $limity = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array('date_fall' => "ASC"));
        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Upadki');
        $gb->set_table_columns(
            array(
                array('name'=>'Limity', 'width'=>30)
            )
        );
        foreach($limity as $p){
            $current = custom::get_current_amount($p['feed_type']);
            $limit = $p->get_val("amount", true);
            if(intval($current) < intval($limit)){
                $extra = "green";
            }else{$extra = 'red';}
            $gb->add_row(   $p->record_link($p->get_val('feed_type',true),false,'view') . " &nbsp; LIMIT: ".
            "<span style='color:$extra;'> ".$current."</span> / <span style='color:red;'>".$limit."</span>");
        
        }
        $this->display_module( $gb );
    }
    public function upadki_list($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy upadki podczas tuczu</li></ul>";
        print($help);
        custom::create_new_faktura();
        custom::set_header("TUCZ - ".$record['name_number']);
        $_SESSION['display_current_name_view'] = "Upadki";
        custom::addButton("kontrakty_upadki","Dodaj upadek","");
        $rbo = new RBO_RecordsetAccessor("kontrakty_upadki");
        $inne = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array('date_fall' => "ASC"));
        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Upadki');
        $gb->set_table_columns(
            array(
                array('name'=>'Data upadku', 'width'=>30),
                array('name'=>'Ilość padłych', 'width'=>30),
                array('name'=>'Waga padłych', 'width'=>30),
            )
        );
        foreach($inne as $p){
            $gb->add_row(   $p->get_val('date_fall'),
                            $p->get_val('amount fall'),
                            $p->get_val('weight_fall'));
        
        }
        $this->display_module( $gb );
    }
    public function pozycje($record){
    
    }
    public function raport_rolnik($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu mamy wygenerowany raport dla rolinka. Należy pamiętać ze nie będzie on dobrze wyliczony 
        jeżeli będzie brakowało danych</li></ul>";
        print($help);
        custom::create_new_faktura();
       // Base_ThemeCommon::install_default_theme($this->get_type());

        $theme = $this->init_module('Base/Theme');
        $raport = new Raporty($record);
        $details = $raport->get_results();
        $theme->assign("details", $details);
        $theme->display('raport_rolnik');
    }
    public function raport_szefowa($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu mamy wygenerowany raport dla szefowej. Należy pamiętać ze nie będzie on dobrze wyliczony 
        jeżeli będzie brakowało danych</li></ul>";
        print($help);
        custom::create_new_faktura();
       // Base_ThemeCommon::install_default_theme($this->get_type());
        $theme = $this->init_module('Base/Theme');
        $raport = new Raporty($record);
        $details = $raport->get_results();
        $extra = $raport->get_extra_for_boss();
        $theme->assign("extra", $extra);
        $theme->assign("details", $details);
        $theme->display('raport_szefowa');
    }

    public function body(){
		Base_ThemeCommon::install_default_theme($this->get_type());
        custom::create_new_faktura();
        Epesi::js('jq(".name").html("");
        jq(".name").html("<div> Tucze kontraktowe </div>");');
        $rs = new RBO_RecordsetAccessor('kontrakty');
        $rb = $rs->create_rb_module ( $this );
        $this->display_module ( $rb);

        Base_ActionBarCommon::add(
            'add',
            'Dodaj nową fakture', 
            Utils_RecordBrowserCommon::create_new_record_href('kontrakty_faktury',$def=array(),$id='none'),
                null,
                5
            );
    }
    public function settings(){
    }    

}
class custom{
    public static function createProgressBar($value){
        $bar = '
                <div style="text-align:center;margin-left:5%;margin-right:5%;background-color:#c8eaff;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: '.$value.'%" aria-valuenow="'.$value.'" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>';
        return $bar;
    }
    
    
    public static function addButton($table,$title_text,$_def){
        Base_ActionBarCommon::add(
            'add',
            $title_text, 
            Utils_RecordBrowserCommon::create_new_record_href($table,$def=array('select'=>$_def),$id='none'),
                null,
                5
        );
    }
    public static function create_new_faktura(){
        Base_ActionBarCommon::add(
            'add',
            'Dodaj nową fakture', 
            Utils_RecordBrowserCommon::create_new_record_href('kontrakty_faktury',$def=array(),$id='none'),
                null,
                8
        );  
    }
    public static function table_names($table_id){
        $table_name = "";
        switch ($table_id){
            case "W":
                $table_name = "kontrakty_faktury_dostawa_warchlaka";
                break;
            case "T":
                $table_name = "kontrakty_faktury_odbior_tucznika";
                break;
            case "P":
                $table_name = "kontrakty_faktury_dostawa_paszy";
                break;
            case "OTH":
                $table_name = "kontrakty_inne";
                break;
            case "Z":
                break;

        }
        return $table_name;
    }
    public static function table_fields($table_id){
        $fields = [];
        switch ($table_id){
            case 'W':
                array_push($fields, array('name' => 'amount' , 'type' => 'text', 'rule'=>'numeric' , 'msg' => "Dozwolone same cyfry"));
                array_push($fields, array('name' => 'weight_on_drop' , 'type' => 'text'));
                break;

            case 'P':
                $feeds = Utils_CommonDataCommon::get_array("/Faktury/pasze");
                array_push($fields, array('name' => 'feed_type' , 'type' => 'select','options'=> $feeds));
                break;

            case 'T':
                array_push($fields, array('name' => 'date_recived' , 'type' => 'date'));
                array_push($fields, array('name' => 'amount' , 'type' => 'text', 'rule'=>'numeric' , 'msg' => "Dozwolone same cyfry"));
                array_push($fields, array('name' => 'weight_alive_brutto' , 'type' => 'text'));
                array_push($fields, array('name' => 'meatiness' , 'type' => 'text'));
                break;
            case 'OTH':
                $opt = Utils_CommonDataCommon::get_array("Kontrakty/inne");
                array_push($fields, array('name' => 'other_type' , 'type' => 'select','options'=> $opt));

                break;
        }
        return $fields;
    }



    public static function get_current_amount($type){
        $feeds = Utils_RecordBrowserCommon::get_records('kontrakty_faktury_dostawa_paszy',
        array('feed_type' => $type , 'id_tuczu' =>$_SESSION['tucz_id']),array(),array());
        $ids = array();
        foreach($feeds as $feed){
            $ids[] = $feed['fakt_poz'];
        }
        $fvs = Utils_RecordBrowserCommon::get_records('kontrakty_faktury_pozycje',array('id' => $ids),array(),array());
        $ammount = 0;
        foreach($fvs as $fv){
            $ammount += $fv['amount'];
        }
        return $ammount;
    }
    /**
     * Does something interesting
     *
     * @param String $display_text   Text to display element
     * @param Array $fields Array of elements 'name' => 'value'
     * @param int $max_width Width showing box
     */ 
    public static function create_infobox($display_text, $fields, $max_width=300){
        $infobox = Utils_TooltipCommon::format_info_tooltip($fields);
        $infobox = Utils_TooltipCommon::create($display_text,$infobox,$help=true, $max_width);
        return $infobox;
    }
    public static function set_header($title){
        Epesi::js('jq(".name").html("");
        jq(".name").html("<div>'. $title .'</div>");');
    }
    public static function button($request,$title){
        $action = 'onclick="_chj(^'.$request.'^, ^^, ^^);"';
        $action = str_replace("^", "'", $action);
        $button = '
        <a href="javascript:void(0)" '.$action.'>
            <div class="panel_div_left epesi_big_button" style="max-width:1%;" helpid="ActionBar_back">
               
                    <span>'.$title.'</span>
                    
            </div>
        </a>';
        return print($button);
    }
    public static function openButtonsPanel(){
        return print('<div id="panel" >');
    }
    public static function closeButtonsPanel(){
        return print("</div>");
    }
}

class Raporty{

    private $basic = null;
    private $amount_inserted = 0;
    private $amount_recived = 0;
    private $death = 0;
    private $zalozenia = null;
    private $feed = null;
    private $feed_all_weight = 0;
    private $avg_use_feed = 0;
    private $rbo_fv_pos = null;
    private $avg_weight_pig = 0;
    private $avg_pig_grow = 0;
    private $avg_meatines = 0;
    private $avg_eff = 0;
    private $tucz_time = 0;
    private $feed_cost = 0;
    private $wet_cost = 0;
    private $tucz_cost = 0;
    private $diff = 0;
    private $warch_cost = 0;
    private $premia = 0;
    private $weight_st = null;
    private $weight_gr = null;
    private $weight_fin = null;
    private $price_feed = null;
    private $cost_death_one = 0;
    private $szt_netto = 0;
    private $avg_price_meat = 0;
    private $suma_potracen = 0;
    private $transport_cost = 0;
    private $transport_netto = 0;

    function __construct($basic_data){
        $this->basic = $basic_data;
        $this->rbo_fv_pos = new RBO_RecordsetAccessor("kontrakty_faktury_pozycje");
        $this->set_feed();
        $this->set_odebrane();
        $this->set_wstawione();
        $this->set_zalozenia();
        $this->set_other();
        $this->set_transporty();
        $this->avg_use_feed = round(($this->feed_all_weight / $this->amount_recived),3);
        $this->avg_pig_grow = (($this->avg_weight_pig *  $this->amount_recived) - ($this->zalozenia['weight_pig_start'] *  $this->amount_inserted )) / $this->amount_recived;
        $this->avg_use_feed_per_kg = $this->avg_use_feed / $this->avg_pig_grow;
        $this->death = $this->amount_inserted - $this->amount_recived;
        $this->warch_cost = $this->amount_inserted * $this->zalozenia['price_pig'];
        $this->all_feed_price_netto = 0;
        $this->diff = ($this->tucz_cost - $this->feed_cost - $this->warch_cost - $this->wet_cost);
        $this->premia = round((floatval(substr($this->zalozenia['farmer'], 0, -3)) * -1 + ($this->diff / $this->amount_recived)),2);
        $this->weight_st =  ($this->zalozenia['starter_to'] - $this->zalozenia['weight_pig_start']) * $this->zalozenia['average_use_st'];
        $this->weight_gr =  ($this->zalozenia['grower_to'] - $this->zalozenia['grower_from']) * $this->zalozenia['average_use_gr'];;
        $this->weight_fin = ($this->zalozenia['weight_pig_end'] - $this->zalozenia['finisher_from']) * $this->zalozenia['average_use_fin'];;
        $this->price_feed = 
        ($this->weight_st * $this->currency_to_float($this->zalozenia['price_starter'])) + 
        ($this->weight_gr * $this->currency_to_float($this->zalozenia['price_grower']))+ 
        ($this->weight_fin * $this->currency_to_float($this->zalozenia['price_finisher']));
        $this->szt_netto = round((($this->currency_to_float($this->zalozenia['price_pig'])
         + $this->price_feed / 2 ) / ((100 - $this->zalozenia['lose']) / 100) 
         + $this->currency_to_float($this->zalozenia['farmer'])+ 
         $this->currency_to_float($this->zalozenia['med']) + ($this->price_feed / 2)),2);
    }
    function get_results(){
        $details = array();
        $details['farmer_name'] =  Utils_RecordBrowserCommon::get_record("company",$this->basic['farmer'])['company_name'];
        $details['amount_pigs'] = $this->amount_inserted;
        $details['date_start'] = $this->basic['data_start'];
        $details['key'] = $this->basic['kolczyk'];
        $details['avg_eff'] = round($this->avg_eff,2);
        $details['warch_cost'] = $this->display_number($this->warch_cost);
        $details['wet_cost'] = $this->display_number($this->wet_cost);
        $details['diff'] = $this->display_number($this->diff);
        $details['avg_pig_grow'] = $this->display_number(round($this->avg_pig_grow,2));
        $details['feed_kg'] = $this->display_number($this->feed_all_weight);
        $details['pigs_death'] = $this->amount_inserted - $this->amount_recived;
        $details['tucz_cost'] = $this->display_number($this->tucz_cost);
        $details['avg_weight_pig'] = round($this->avg_weight_pig,2);
        $details['farmer_base'] = round(floatval(substr($this->zalozenia['farmer'], 0, -3)),2);
        $details['premia'] =$this->premia;
        $details['feed_cost'] = $this->display_number($this->feed_cost);
        $details['farmer_profit'] = round(floatval($this->zalozenia['farmer'] + $this->premia),2);
        $details['value_warchlak'] = $this->display_number($this->warch_cost);
        $details['avg_use_feed_per_kg'] = round(floatval($this->avg_use_feed_per_kg),2);
        $details['avg_meatens'] = $this->display_number($this->avg_meatines);
        $details['premia'] = $this->premia;
        $details['avg_price_feed'] =  round($this->feed_cost / $this->feed_all_weight,4);
        $details['tucz_time'] = round($this->tucz_time + 0.5);
        $details['dead_in_percent'] = round((($this->death / $this->amount_recived) * 100),2)." %";
        $details['zuPasza'] = $this->feed_all_weight && $this->amount_recived ? round(($this->feed_all_weight / $this->amount_recived),3). " kg" : "Brakuje danych";
        $details['zuPasza'] = $this->display_number($details['zuPasza']);
        $details['zuPaszaDeath'] = round((($this->feed_all_weight / $this->amount_recived) * ($this->amount_inserted - $this->amount_recived) * 0.33),2);
        $details['recived_pigs'] = $this->amount_recived ? $this->amount_recived . " szt" : "Brak odbiorów.";
        $details['start_weight'] = $this->zalozenia['weight_pig_start'] ? $this->zalozenia['weight_pig_start']. " kg" : "Nie ustawiono w założeniach" ;
        $details['sum_cost_death'] = round((floatval(substr($this->zalozenia['price_pig'],0,-3)) * $details['pigs_death'] ) + ($details['zuPaszaDeath'] * $details['avg_price_feed']),2);
        $details['cost_death_one'] = round(($details['sum_cost_death'] / $this->amount_inserted),2);
        $details['diff_2'] = round((($this->weight_st + $this->weight_gr  + $this->weight_fin)  - $details['zuPasza'] )  * $details['avg_price_feed'],2);
        $this->suma_potracen += $details['diff_2'];
        $this->cost_death_one = $details['cost_death_one'];
        $details['sum_cost_death'] = $this->display_number($details['sum_cost_death']);
        return $details;
    }
    function get_extra_for_boss(){
        $extra = array();
        $extra['death_cost'] = (
             $this->amount_inserted * 
             $this->currency_to_float($this->zalozenia['price_pig']) 
            + $this->price_feed * 0.5 * $this->amount_inserted ) * 0.03;
        $extra['planed_death_cost'] = round($extra['death_cost'] / $this->amount_inserted);
        $extra['diff_death_cost'] = round(($extra['planed_death_cost'] - $this->cost_death_one),2);
        $this->suma_potracen += $extra['diff_death_cost'];
        $extra['planned_avg_per_kg'] = round($this->szt_netto / $this->zalozenia['weight_pig_end'],2);
        $extra['avg_price_live_weight'] = round(($this->avg_price_meat * $this->avg_eff) /100,2);
        $extra['premium'] = round($extra['avg_price_live_weight'] - $extra['planned_avg_per_kg'],3);
        $extra['premium_2'] =   round($extra['premium'] * $this->avg_weight_pig,2); 
        $this->suma_potracen += $extra['premium_2'];
        $extra['diff_1'] = round(($this->avg_weight_pig - $this->zalozenia['weight_pig_end'] ) * $extra['avg_price_live_weight'],2);
        $this->suma_potracen += $extra['diff_1'];
        $extra['premium'] = $this->color($extra['premium']);
        $extra['premium_2'] = $this->color($extra['premium_2']);
        $extra['planed_death_cost'] = $this->display_number( $extra['planed_death_cost']);
        $extra['death_cost'] = $this->display_number( $extra['death_cost']);
        $extra['cost_per_one_pig_live'] = round($this->wet_cost / $this->amount_inserted,2);
        $extra['diff_3'] = round($this->currency_to_float($this->zalozenia['med'])  - $extra['cost_per_one_pig_live'],1);
        $this->suma_potracen += $extra['diff_3'];
        $extra['final'] = round($this->currency_to_float($this->zalozenia['farmer']) + $this->suma_potracen,2);
        $extra['potracenia'] = $this->suma_potracen;
        $extra['tucz_sell'] = $this->transport_netto;
        $extra['cost_transport'] = $this->transport_cost;

        return $extra;
    }
    function set_other(){
        $wet = $this->rbo_fv_pos->get_records(array('typ_faktury' => 'WET' , "id" => 
        tuczkontraktowyCommon::get_fv_ids("kontrakty_faktury_inne_faktury_tucz",$this->basic['id'])),array(),array());
        $cost = 0;
        foreach($wet as $w){
            $c = $w['price']; 
            $c = substr($c, 0, -3);
            $cost += $c;

        }
        $this->wet_cost = $cost;
    }
    function currency_to_float($val){

        $val = substr($val, 0, -3);
        $val = floatval($val);
        return $val;
    }
    function set_odebrane(){
        //odebrane tuczniki
        $received = $this->rbo_fv_pos->get_records(array("id" => 
        tuczkontraktowyCommon::get_fv_ids("kontrakty_faktury_odbior_tucznika",$this->basic['id'])),array(),array());
        $odbiory =  new RBO_RecordsetAccessor("kontrakty_faktury_odbior_tucznika");
        $received_amount = 0;
        $avg = 0;
        $avg_m = 0;
        $sum_weight_meat = 0;
        $sum_weight_brutto = 0;
        $tucz_time = null;
        $netto = 0;
        foreach($received as $r){
            $records = $odbiory->get_records(array('fakt_poz' => $r['id']),array(),array());
            $odbior = null;
            foreach($records as $rs){$odbior = $rs;break;}

            $received_amount += $r['amount'];
            $avg += $odbior['weight_brutto'];
            $avg_m += ($odbior['meatiness'] / 100) * $r['amount'];
            $days = strtotime($odbior['date_recived']) - strtotime($this->basic['data_start']);
            $days = floor($days/(60*60*24));
            $days = $days * $r['amount'];
            $tucz_time += $days;
            $netto += $odbior['price_netto'];
            $sum_weight_brutto += $odbior['weight_brutto'];
            $sum_weight_meat += $odbior['weight_meat'];

        }
        $tucz_time = $tucz_time / $received_amount;
        $avg_m = round((($avg_m / $received_amount) * 100),2);
        $this->avg_price_meat = $netto / $sum_weight_meat;
        $this->tucz_time = $tucz_time;
        $this->tucz_cost = $netto;
        $this->avg_eff = ($sum_weight_meat / $sum_weight_brutto) * 100;
        $this->avg_meatines = $avg_m;
        $this->avg_weight_pig = $avg /  $received_amount;
        $this->amount_recived = $received_amount; 
    }
    function display_number($number){
        $number = number_format($number, 2, '.', ' ');
        return $number;
    }
    function set_wstawione(){
        $amount = 0;
        $pigs = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_dostawa_warchlaka",array("id_tuczu" => $this->basic['id']));
        foreach($pigs as $pig){
            $amount += $pig['amount'];
        }
        $this->amount_inserted = $amount;
    }
    function color($val){
        if($val< 0){
            $val = "<span style='color:red;'> ".$val. " </span>";
        }else{
            $val= "<span style='color:green;'> ".$val. " </span>";
        }
        return $val;
    }
    function set_zalozenia(){
        $zalozenia = Utils_RecordBrowserCommon::get_records("kontrakty_zalozenia",array('id_tuczu' => $this->basic['id']));
        foreach($zalozenia as $z){$zalozenia = $z;break;}
        $this->zalozenia = $zalozenia;
    }
    function set_transporty(){
        $transporty = $this->rbo_fv_pos->get_records(array("id" => 
        tuczkontraktowyCommon::get_fv_ids("kontrakty_faktury_transporty",$this->basic['id'])),array(),array());
        $netto = 0;
        $cost = 0;
        foreach($transporty as $transport){
            $netto += $this->currency_to_float($transport['netto']);
            $cost += $this->currency_to_float($transport['transport_cost']);
        }
        $this->transport_netto = $netto;
        $this->transport_cost = $cost;
    }

    function set_feed(){
        $feeds = $this->rbo_fv_pos->get_records(array("id" => 
        tuczkontraktowyCommon::get_fv_ids("kontrakty_faktury_dostawa_paszy",$this->basic['id'])),array(),array());
        $all_weight_feed = 0;
        $all_feed_price_netto = 0;
        foreach($feeds as $f){
            $get_price = $f['price'];
            $get_price =  substr($get_price, 0, -3);
            $all_feed_price_netto += $get_price * $f['amount'];
            $all_weight_feed += $f['amount'];
        }

        $this->feed_cost = $all_feed_price_netto;
        $this->feed_all_weight = $all_weight_feed;
        $this->feed = $feeds;
    }
    function get_val($varible){
        return $this->$varible;
    }
}
?>
