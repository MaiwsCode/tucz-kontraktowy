<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
use Silverslice\DocxTemplate\Template;
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
        $allWeight = 0;
        $allPrice = 0;
        $allAmount = 0;
        foreach($warchlaki as $p){	
				$r = $info->get_record($p['fakt_poz']);	
				$ub = "";
				$we = "";
				if($p['weight_on_drop']){
					$we = $p['weight_on_drop'];
					$ub = ($r['amount'] - $p['weight_on_drop'] ) / $p['amount'];
				}
			    $link = $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false);

                $link = $info->get_record($p['fakt_poz'])->record_link(($r->description ?: "Brakuje opisu" )." ",false);

                if($ub < 0){
                    $ub = "+ ".$ub;
                }else{
                    $ub = "-".$ub;
                }
                $allWeight += custom::change_spearator($r['amount'],",",".");
                $allAmount += $p['amount'];
                $allPrice += custom::change_spearator($r['price'],",",".");
                $gb->add_row( $link,
							  $r['amount']." kg",
							  $r->get_val('price'),
							  $p->get_val('amount'),
                    str_replace(".",",",round(($r['amount'] / $p['amount']),2))." kg/szt" ,
                    str_replace(".",",",round(($r['price'] / $p['amount']),2)). " zł/szt" ,
							  $we,
                    str_replace(".",",",$ub));
            
        }
        $allWeight = number_format($allWeight,2,","," ");
        $allPrice = number_format($allPrice,2,","," ");
        $bold = "<span style='font-size:14px;font-weight:bold;'> ";
        $boldEnd = "</span>";
        $gb->add_row(
          "",
          "$bold Łączna waga: ".custom::change_spearator($allWeight,".",",")." kg $boldEnd",
          "$bold Łączna cena: ".custom::change_spearator($allPrice,".",",")." zł $boldEnd",
          "$bold Suma sztuk: ".$allAmount." $boldEnd",
          "","","",""
        );

        $this->display_module( $gb );
    }

    public function pasze_list($record){

        if($_REQUEST['limits'] == true){

            $yes ='<span class="hide"><a class="epesi_big_button" '.$this->create_href ( array ('confirmed' => 'true')).'>Tak</a></span>';
            $no = '<span class="hide"><a class="epesi_big_button" '.$this->create_href ( array ("confirmed"=> "false")).'>Nie</a></span>';
            print("<div id=\"myModal\" class=\"modal\">               
                <!-- Modal content -->
                <div class=\"modal-content\">
                 <span class=\"close\">&times;</span>
                 <h1>Czy na pewno zmienić istniejące limity paszy ?</h1><br>
                <p>".$yes." ".$no."</p></div></div>");

            epesi::js('// Get the modal
                        var modal = jq("#myModal");
                        modal.css("display","block");
                        var btn = jq("#myBtn");
                        
                        var span = jq(".close")[0];
                        var hide = jq(".hide");
  
                        btn.onclick = function() {
                          modal.css("display", "block");
                        }
                        jq(hide).bind("click",function() {
                          modal.css("display",  "none");
                        });
                        span.onclick = function() {
                          modal.css("display",  "none");
                        }

                        window.onclick = function(event) {
                          if (event.target == modal) {
                            modal.css("display", "none");
                          }
            }');
        }
        if($_REQUEST['confirmed'] == 'true'){

            $limit_starter = 0;
            $limit_grower = 0;
            $limit_finisher = 0;

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

            $limits = Utils_CommonDataCommon::get_array("Kontrakty/limity_tuczu_na_paszy");
            if($zal != "None"){
                $avg_usage = Utils_CommonDataCommon::get_array("Kontrakty/sr_zuzycie/".$zal['avg_usage']);
                $selected_plan = Utils_CommonDataCommon::get_array("Kontrakty/sr_zuzycie");
                $selected_plan = $selected_plan[$zal['avg_usage']];
                $zal['price_pig'] = substr($zal['price_pig'], 0, -3);
                $limits['starter_grower'] = $zal['starter_to'];
                $limits['grower_finisher'] = $zal['grower_to'];
            }
            //starter
            $from = $limits['starter_grower'];
            $to = custom::change_spearator($zal['weight_pig_start'],",",".");
            $weight1 = ($from-$to) * custom::change_spearator($avg_usage['starter'],',','.');
            $weight1 = $weight1 * $zal['planned_amount'];

            //grower
            $from = $limits['grower_finisher'];
            $to = $limits['starter_grower'];
            $weight2 = ($from-$to) * custom::change_spearator($avg_usage['grower'],',','.');
            $weight2 = $weight2 * $zal['planned_amount'];

            //finisher
            $from = custom::change_spearator($zal['weight_pig_end'],",",".");
            $to = $limits['grower_finisher'];
            $weight3 = ($from-$to) * custom::change_spearator($avg_usage['finisher'],',','.');
            $weight3 = $weight3 * $zal['planned_amount'];

            $rbo_limits = new RBO_RecordsetAccessor('kontrakty_limity');
            $limits = $rbo_limits->get_records( array('id_tuczu' => $record['id']),array(),array());
            foreach($limits as $limit){
                $limit->delete();
            }
            $rbo_limits->new_record(array('id_tuczu'=> $record['id'], 'feed_type' => 'starter', 'amount' => $weight1));
            $rbo_limits->new_record(array('id_tuczu'=> $record['id'], 'feed_type' => 'grower', 'amount' => $weight2));
            $rbo_limits->new_record(array('id_tuczu'=> $record['id'], 'feed_type' => 'finisher', 'amount' => $weight3));


        }

        //Base_ThemeCommon::install_default_theme($this->get_type());
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy dostawe pasz</li></ul>";
        print($help);
        custom::set_header("TUCZ - ".$record['name_number']);
        custom::create_new_faktura();

        //$_SESSION['display_current_name_view'] = "Limity";
        custom::addButton("kontrakty_limity","Dodaj limity paszy","");

        $downladHref = 'href="modules/tuczkontraktowy/word.php?'.http_build_query(array('recordID'=> $record['id'] , 'cid'=>CID)).'"';
        print('<a class="epesi_big_button" '.$this->create_callback_href(array($this,'limits_list'),array($record)).'>Wyświetl limity pasz</a><br><br>');

        Base_ActionBarCommon::add(
            'report',
            "Wygeneruj harmonogram dostaw",
            $downladHref,
            null,
            4
        );
        Base_ActionBarCommon::add(
            'save',
            "Ustaw limity z założeń",
            $this->create_href ( array ('limits' => 'true')),
            null,
            4
        );
        $exist_types = [];
        $exist_amount = [];
        $price_avg = [];
        $feed_count  = [];
        $cena = [];
        $waga = [];
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
                array('name'=>'Cena netto', 'width'=>45),
                array('name'=>'Cena jednostkowa', 'width'=>45)
            )
        );
        $sumaKg = 0;
        $cena = 0;
        foreach($pasze as $p){
            $feed = $p['feed_type'];
            $exist_types[$feed] =  $feed;
            $feed_count[$feed] += 1;
            $extra = $info->get_record($p['fakt_poz']);
            $exist_amount[$feed] += $extra['amount'];
            $sumaKg += $extra['amount'];
            $price = substr($extra['price'], 0, -3);
            $price = custom::change_spearator($price,',','.');
            $price_avg_weight[$feed] +=  floatval($price);
            $cena += floatval($price);
            $price_avg[$feed] += floatval(str_replace(",",".",substr($extra['price'], 0, -3)));

            $gb->add_row(
                $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false)." - ".$p['feed_type'],
                $extra['amount']." kg",
                $extra->get_val('price'),
                custom::change_spearator( round(($price / $extra->amount),4),'.',',')." zł/kg"
            );
        }
        $bold = "<span style='font-size:14px;font-weight:bold;'> ";
        $boldEnd = "</span>";
        $avg = $cena / $sumaKg;
        $avg = round($avg,4);
        $sumaKg = number_format($sumaKg,2,","," ");
        $cena = number_format($cena,2,","," ");

        $gb->add_row(
            "",
            "$bold Łącznie kg: ".$sumaKg." kg $boldEnd",
            "$bold Łączna cena: ".$cena." zł $boldEnd",
            "$bold Średnia cena za kg: ".custom::change_spearator($avg,".",",")." zł/kg $boldEnd"
        );
        $this->display_module( $gb );

        foreach ($exist_types as $type){
            $rbo_limits = new RBO_RecordsetAccessor("kontrakty_limity");
            $limits = $rbo_limits->get_records(array('id_tuczu' => $record['id'], 'feed_type' => $type),array(),array());

            $price_avg_weight[$type] = $price_avg_weight[$type] / $exist_amount[$type];
            $price_avg_weight[$type] = round($price_avg_weight[$type],4);
            $price_avg_weight[$type] = custom::change_spearator($price_avg_weight[$type],'.','.');

            $price_avg[$type] = $price_avg[$type] / $feed_count[$type];
            $price_avg[$type] = round($price_avg[$type],4);
            $price_avg[$type] = custom::change_spearator($price_avg[$type],'.','.');

            //dane pod tabela
            print("<table style='background: rgb(255,255,255);
                    background: linear-gradient(252deg, rgba(255,255,255,1) 0%, rgba(244,244,244,0.5606617647058824) 0%,
                    rgba(255,255,255,0.7511379551820728) 100%);text-align:center;' class='ttable'>
                            <tr>
                                <td>".ucfirst($type)."</td>
                                <td> Średnia cena: ". custom::change_spearator($price_avg[$type],".",",") ." zł </td>
                                <td> Średnia cena ważona: ". number_format($price_avg_weight[$type],4,',',' '  )." zł/kg </td>
                            </tr>");
            foreach ($limits as $l){
                print("<tr><td colspan='3'>".custom::createProgressBar(($exist_amount[$l['feed_type']]*100) / $l['amount'] )."</td></tr>");
                print("<tr><td></td><td><h3>Pobrano ".number_format($exist_amount[$l['feed_type']],0,","," ").
                    " z ".number_format($l['amount'],0,',',' ')." kg limitu paszy</h3></td><td></td></tr>");
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
        custom::addButton("kontrakty_faktury_pozycje","Dodaj transport","TR");
        $_SESSION['adding_type'] = 'transport';

        $rbo = new RBO_RecordsetAccessor("kontrakty_faktury_transporty");
        $info = new RBO_RecordsetAccessor("kontrakty_faktury_pozycje");
        $trans = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array());
        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Pasze');
        $gb->set_table_columns(
            array(
                array('name'=>'Transporty', 'width'=>20),
                array('name'=>'Data transportu', 'width'=>20),
                array('name'=>'Ilość sztuk', 'width'=>20),
                array('name'=>'Cena netto', 'width'=>20),
                array('name'=>'Odbiorca', 'width'=>20),
            )
        );
        foreach($trans as $p){
            $r = $info->get_record($p['fakt_poz']);
            $link = $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false);
            $link = $info->get_record($p['fakt_poz'])->record_link(($r->description ?: "Brakuje opisu" )." ",false);

            $reciver = new RBO_RecordsetAccessor('company');
            $rec = $reciver->get_record($p['company']);

            $gb->add_row(
                $link,
                $p->date,
                $p->amount,
                $p->netto." zł",
                $rec->company_name
            );
        
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
        $odbior = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array('date_recived'=>"ASC"));
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
                array('name'=>'Odbiory', 'width'=>100/6),
                array('name'=>'Data odbioru', 'width'=>100/6),
                array('name'=>'Ilość sztuk', 'width'=>100/6),
                array('name'=>'Waga żywa brutto', 'width'=>100/6),
                array("name" => "WBC" , "width" => 100/6),
                array('name'=>'Mięsność', 'width'=>100/6),
            )
        );

        $miesnosc = 0;
        foreach($odbior as $p){
            $extra = $info->get_record($p['fakt_poz']);
            $sum_weight_on_fakt += $extra['amount'];
            $recived += $p['amount'];
            $sum_alive_brutto += $p['weight_alive_brutto'];
            $gb->add_row( $info->get_record($p['fakt_poz'])->create_default_linked_label(false,  false),
                    $p->get_val("date_recived"),
                    $p['amount']." szt.",
                    $p['weight_alive_brutto']." kg",
                    $extra['amount']." kg",
                    $p['meatiness']."%"
                );
            $miesnosc += custom::change_spearator($p['meatiness'],",",".");
        }
        $miesnosc = $miesnosc / count($odbior);
        $miesnosc = custom::change_spearator($miesnosc,".",",");
        $this->display_module( $gb );

        $difference = $pigs_amount - $falls_amount - $recived;
        $f1 = str_replace(".",",",round(($sum_alive_brutto / $recived),4));
        $f2 = str_replace(".",",",round(($sum_weight_on_fakt / $sum_alive_brutto),2)). "%";
        print("<table style='background: rgb(255,255,255);
                    background: linear-gradient(252deg, rgba(255,255,255,1) 0%, rgba(244,244,244,0.5606617647058824) 0%,
                    rgba(255,255,255,0.7511379551820728) 100%);text-align:center;width:60%;' class='ttable'>");
        print("<tr><td>Suma oddanych</td><td> Pozostaje na stanie </td> <td> Średnia waga oddanej sztuki </td><td> Średnia wydajność </td><td> Średnia mięsność </tr>");
        print("<tr><td> $recived </td><td> $difference </td> <td> ".$f1." </td><td> ". $f2 ." </td> <td>".$miesnosc." %</td></tr>");
        print("</table>");


    }

    public function inne_list($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy inne faktury jak np. Weterynarz </li></ul>";
        print($help);
        custom::set_header("TUCZ - ".$record['name_number']);
        custom::create_new_faktura();
        $_SESSION['display_current_name_view'] = "Inne faktury";
        custom::addButton("kontrakty_faktury_pozycje","Dodaj szczegóły","OTH");
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
                array('name'=>'Cena jednostkowa', 'width'=>25),
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
            $types[$p['other_type']]['price'] += (floatval(str_replace(",",".",substr($extra['price'], 0, -3))));
            $desc = "";
            if($p->description){$desc = $p->description;} else{ $desc =  "Brakuje opisu";};
            $gb->add_row( $info->get_record($p['fakt_poz'])->record_link($extra->description ?: "Brakuje opisu",  false),
                $p->get_val('other_type'),
                number_format($extra->price / $extra->amount,2,","," ")." zł" ,
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
                $per_one = round($per_one,4);
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
        if($record['typ_faktury'] == "W" || $record['typ_faktury'] == "T" || $record['typ_faktury'] == "P"
            || $record['typ_faktury'] == "OTH" || $record['typ_faktury'] == "TR" ) {
            $switched = false;
            $type = $record['typ_faktury'];
            if($type != $_SESSION['oldType'] && $_SESSION['oldType'] != ""){
                $switched = true;
                $rbo_ = new RBO_RecordsetAccessor(custom::table_names($_SESSION['oldType']));
                $_records = $rbo_->get_records(array('fakt_poz' => $record['id'], 'id_tuczu' => $_SESSION['tucz_id']),array(),array());
                foreach($_records as $rkd){
                    $rkd->delete();
                }
                tuczkontraktowyCommon::on_add_details($record,'added');
            }
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
                if ($values['date']) {
                    $values['date'] = $values['date']['Y'] . "-" . $values['date']['M'] . "-" . $values['date']['d'];
                }

     
                if ($record_exist == true && $switched == false) {
                    $updated_fields = array();
                    $values['id_tuczu'] = $_SESSION['tucz_id'];
                    $values['fakt_poz'] = $record['id'];
                    foreach ($fields as $field) {
                        $updated_fields[$field['name']] = $values[$field['name']];
                    }
                    Utils_RecordBrowserCommon::update_record(
                        custom::table_names($record['typ_faktury']), $r['id'], $updated_fields

                    );
                }
                else {
                    //add new
                    foreach ($fields as $field) {
                        $r[$field['name']] = $values[$field['name']];
                    }
                    $r->id_tuczu = $_SESSION['tucz_id'];
                    $r->fakt_poz = $record['id'];
                    $r->save();
                }
            }
        }
        $_SESSION['oldType'] = 0;
    }

    public function przewaga_view($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu dodajemy kontrolne przeważania</li>
                  <li> Można edytować już istniejące przeważenia wybierając odpowiednią date i wpisując w poszczególne pola nowe wagi następnie 
                  zatwierdzić to przyciskiem zapisz  </li>
        </ul>";
        print($help);
        if(isset($_REQUEST['delete_record'])){
            Utils_RecordBrowserCommon::delete_record('kontrakty_wazenie',$_REQUEST['delete_record']);
        }
        if($_REQUEST['action'] == 'show'){
            $_SESSION['wazenie'] = null;
            $_SESSION['action'] = null;
            unset($_SESSION['wazenie']);
            unset($_SESSION['action']);
        }

        custom::create_new_faktura();
        custom::set_header("TUCZ - ".$record['name_number']);
            $rbo = new RBO_RecordsetAccessor("kontrakty_wazenie");
        $daty =  DB::GetAll("SELECT DISTINCT f_date_weight FROM kontrakty_wazenie_data_1 WHERE (f_id_tuczu = " .$record['id']. " AND active = 1)  ORDER BY f_date_weight");
            $gb = &$this->init_module('Utils/GenericBrowser', null, 'Ważenie');
            $gb->set_table_columns(
                array(
                    array('name'=> 'Ważenia', 'width'=>20),
                    array('name'=> 'Ile dni później', 'width'=>20),
                    array('name'=> 'Średni przyrost', 'width'=>20),
                    array('name'=> 'Przyrost na dzień', 'width'=>20),
                )
            );
            for($i=0;$i<count($daty);$i++){
                $waga_before = $rbo->get_records(array('id_tuczu' => $record['id'] ,'date_weight' => $daty[$i-1][0] ),array(),array('pig_number'=> "ASC"));
                $waga = $rbo->get_records(array('id_tuczu' => $record['id'] ,'date_weight' => $daty[$i][0] ),array(),array('pig_number'=> "ASC"));
                $avg_grow = 0;
                foreach ($waga as $pig){
                    foreach ($waga_before as $before){
                        if($before->pig_number == $pig->pig_number){
                            $diffrence = custom::change_spearator($pig->weight, "," , "." ) - custom::change_spearator($before->weight, "," , "." );
                            $avg_grow  +=  $diffrence;
                            break;
                        }
                    }
                }
                $avg_grow = $avg_grow / count($waga);
                if($i<1){
                    $gb->add_row(   
                    "<a style='margin-left:10px;' ". 
                        $this->create_href ( array ('action' => 'view_record' , 
                                                    'wazenie' => $daty[$i][0])).">". $daty[$i][0] ."</a>",
                                                     '---' ,
                                                    '---', 
                                                    '----'
                    );
                }else{
                    $now = $daty[$i][0];
                    $before = $daty[($i-1)][0];
                    $days = strtotime($now) - strtotime($before);
                    $days= floor($days/(60*60*24));
                    $avg_grow = round($avg_grow,4);
                    $perDay = $avg_grow / $days;
                    $perDay = round($perDay,4);
                    $gb->add_row(   
                        "<a style='margin-left:10px;' ". 
                            $this->create_href ( array ( 'action' => 'view_record', 'wazenie' => $daty[$i][0])) .">". $daty[$i][0] ."</a>",
                             ($days ),
                        custom::change_spearator($avg_grow,'.',',')." kg",
                        custom::change_spearator($perDay,'.',',')." kg"

                        );
                }
            }
            $gb->add_row(   
                "<a  style='margin-left:10px;'". $this->create_href ( array ('action' => 'view_all')) ."> Zestawienie wszystkich przeważeń </a>", '',"",''
            );
            $this->display_module( $gb );
        if($_REQUEST['action'] == 'view_record' || $_SESSION['wazenie'] != null){
            $_SESSION['action'] = 'view_record';
            if(!isset($_SESSION['wazenie'])) {
                $_SESSION['wazenie'] = $_REQUEST['wazenie'];
            }else{
                $_REQUEST['wazenie'] = $_SESSION['wazenie'];
            }
            custom::openButtonsPanel();     
            custom::button("action=show","Wstecz");
            custom::closeButtonsPanel();
            $rbo = new RBO_RecordsetAccessor("kontrakty_wazenie");
            $waga = $rbo->get_records(array('id_tuczu' => $record['id'] ,'date_weight' => $_REQUEST['wazenie'] ),array(),array('pig_number'=> "ASC"));
            $gb = &$this->init_module('Utils/GenericBrowser', null, 'Ważenie');
            $gb->set_table_columns(
                array(
                    array('name=' => '', 'width' => 5),
                    array('name'=>'Numer Świni', 'width'=>25),
                    array('name'=>'Data ważenia', 'width'=>25),
                    array('name'=>'Waga', 'width'=>25),
                )
            );
            $allWeight = 0;
            $del_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/Calendar/delete.png' alt='Usuń' />";
            $edit_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/Calendar/edit.png' alt='Edytuj' />";
            $pigCount = count($waga);
            foreach($waga as $p){
                $buttons = "";
                $del = $del = $this->create_href(array("delete_record" => $p->id));
                $del = "<a ".$del.">".$del_btn."</a>";
                $edit = $p->record_link($edit_btn,false,'edit');
                $buttons = $edit ." ". $del;
                $gb->add_row(
                    $buttons,
                    $p->get_val('pig_number'),
                    $p->get_val('date_weight'),
                    "<span style='margin-left:5px;' >".$p->record_link($p['weight'])."</span>");
                $allWeight += custom::change_spearator($p['weight'],",",".");
            }
            $avg = ($allWeight/$pigCount);
            $avg = round($avg,4);
            $gb->add_row(  
                "","","", "Waga średnia: " . custom::change_spearator(($avg),".",",")." kg"
              );
            $gb->add_row(  
               "","","", "Łączna waga: " . custom::change_spearator($allWeight,".",",")." kg"
             );

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
            $fields[0] = array('name'=> 'Kolczyk ', 'width' =>$width );
            for($i=0;$i<count($daty);$i++){
                $fields[] = array('name'=> $daty[$i][0] , 'width' =>$width );
            }
            $rbo = new RBO_RecordsetAccessor("kontrakty_wazenie");
            $waga = $rbo->get_records(array('id_tuczu' => $record['id'] ),array(),array('pig_number'=> "DESC"));
            $last = null;
            foreach ($waga as $w){
                $last = $w['pig_number'];
                break;
            }
            $waga = $rbo->get_records(array('id_tuczu' => $record['id'] ),array(),array('pig_number'=> "ASC"));
            $first = null;
            foreach ($waga as $w){
                $first = $w['pig_number'];
                break;
            }
            $gb = &$this->init_module('Utils/GenericBrowser', null, 'Ważenie');
            $gb->set_table_columns(
               $fields
            );
            for($i = $first;$i<= $last;$i++){
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
        print("<div style='width:80%;'>");
        print("<input type='text' style='width:30%;border-radius:6px 6px;' id='min' placeholder='Numer pierwszego kolczyka' /> <br>");
        print("<input type='text' style='width:30%;border-radius:6px 6px;' id='max' placeholder='Numer ostatniego kolczyka' /> <br>");
        print("</div>");
        $basic_info = $this->create_href( array('min_range_pig_numbers'=>'0', 'max_range_pig_numbers'=>'0', 'next_step' => 'true') );
        print("<br><a class='epesi_big_button' id='next_page' $basic_info >Dalej</a><br>");

        if($_REQUEST['next_step']){
            $_SESSION['min'] = $_REQUEST['min_range_pig_numbers'];
            $_SESSION['max'] = $_REQUEST['max_range_pig_numbers'];
        }
        $min = $_SESSION['min'];
        $max = $_SESSION['max'];
        $form = $this->init_module('Libs/QuickForm');
        eval_js("
       document.onkeydown = function (e) {
            if (e.which == 13) {
             e.preventDefault();
             var inputs = document.getElementsByClassName('pigCell');
                for (var i = 0; i < inputs.length; i++) {
                    if (document.activeElement == inputs[i]) {
                        inputs[i+1].focus();
                        break;   
                    }
                }
            }
        };
        ");
        if($min) {
            $form->addElement('date', 'data', 'Data');
            $form->setDefaults(array('data' => date("d-m-Y")));
            for ($i = $min; $i <= $max; $i++) {
                $form->addElement('text', 'pig_' . $i, 'Kolczyk ' . $i . " <span style='text-align:right;right:0;'></span>", array("class"=>"pigCell"));
            }
            $form->addElement('submit', 'submit', 'Dodaj');
            print("<h3> Dodaj przeważenia </h3>");
            $form->display_as_column();
        }
        print("<BR><BR><BR>");
        if ($form->validate()) {
            $array_fields = $form->exportValues();
            print_r($array_fields);
            $date = $array_fields['data']['Y'] . "-" . $array_fields['data']['M'] . "-" . $array_fields['data']['d'];
            for ($i = $min; $i <= $max; $i++) {
                if (strlen($array_fields['pig_' . $i]) > 0) {
                    $id = $i;
                    $tucz_id = $record['id'];
                    $rec = Utils_RecordBrowserCommon::get_records("kontrakty_wazenie",
                        array('pig_number' => $id, 'date_weight' => $date, 'id_tuczu' => $tucz_id));
                    if (count($rec) > 0) {
                        foreach ($rec as $r) {
                            $rec = $r;
                            break;
                        }
                    } else {
                        $rec = null;
                    }
                    if ($rec == null) {
                        Utils_RecordBrowserCommon::new_record("kontrakty_wazenie",
                            array('id_tuczu' => $tucz_id, 'pig_number' => $id,
                                'date_weight' => $date, 'weight' => $array_fields['pig_' . $i]));
                    } else {
                        Utils_RecordBrowserCommon::update_record("kontrakty_wazenie", $rec['id'],
                            array('weight' => $array_fields['pig_' . $i]));
                    }
                }
            }
        location();
        }
        Epesi::js('
            jq("#max").on("input", function(){
               var get_str = jq("#next_page").attr("onclick");
               var finding_str  = get_str.split("&")[1];
               var index_of_num = finding_str.indexOf("=");
               var number = finding_str.substr(index_of_num+1,finding_str.length);
               var append = "max_range_pig_numbers=" + jq("#max").val();
               get_str = get_str.replace("max_range_pig_numbers="+number,append);
               jq("#next_page").attr("onclick",get_str);
               
            });
            jq("#min").on("input", function(){
               var get_str = jq("#next_page").attr("onclick");
               var finding_str = get_str.split("&")[0];
               var index_of_num = finding_str.indexOf("=");
               var number = finding_str.substr(index_of_num+1,finding_str.length);
               var append = "min_range_pig_numbers=" + jq("#min").val();
               get_str = get_str.replace("min_range_pig_numbers="+number,append);
               jq("#next_page").attr("onclick",get_str);  
            });
       ');
        
    }

    public function plan($record){
        $form = & $this->init_module('Libs/QuickForm');
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> Aby ustawić wartości domyślne dla pól należy wprowadzić wartości klikając u góry przycisk 'Edycja założeń' </li></ul>";
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
                __('Edycja założeń'),
                Utils_RecordBrowserCommon::create_record_href('kontrakty_zalozenia',$id=$_id,'edit'),
                null,
                $x
            );
        }else{
            Base_ActionBarCommon::add(
                'add',
                __('Edycja założeń'),
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

        //form
        $form->addElement('text', 'szt','', array('class' => "input_value",
                                                'id' => 'szt',
                                                'value' => $zal['planned_amount'] ?: 0 ));

        $form->addElement('text','weight_start','',array('class' => "input_value",
                                                         'id'=> 'weight_start' ,
                                                         'value' => $zal['weight_pig_start'] ?: 0 ));

        $form->addElement('text','weight_end','',array('class' => "input_value",
                                                        'id'=> 'weight_end' ,
                                                        'value' => $zal['weight_pig_end'] ?: 0 ));
        $form->addRule('szt', 'Tylko liczby całkowite', 'numeric');

        $form->addElement('text','price_st','',array('class' => "input_value",
                                                     'id'=> 'price_st' ,
                                                     'value' => $zal['price_starter'] ?: 0 ));

        $form->addElement('text','price_gr','',array('class' => "input_value",
                                                     'id'=> 'price_gr' ,
                                                     'value' => $zal['price_grower'] ?: 0 ));

        $form->addElement('text','price_fin','',array('class' => "input_value",
                                                      'id'=> 'price_fin' ,
                                                      'value' => $zal['price_finisher'] ?: 0 ));

        $form->addElement('text','price_pig','',array('class' => "input_value",
                                                      'id'=> 'price_pig' ,
                                                      'value' => $zal['price_pig'] ?: 0 ));

        $form->addElement('text','price_feed','',array('class' => "input_value",
                                                       'id'=> 'price_feed' ,
                                                       'value' => 0 ));

        $form->addElement('text','med','',array('class' => "input_value",
                                                'id'=> 'med' ,
                                                'value' => $zal['med'] ?: '10,0' ));

        $form->addElement('text','lose','',array('class' => "input_value",
                                                 'id'=> 'lose' ,
                                                 'value' => $zal['lose'] ?: '3' ));

        $form->addElement('text','farmer','',array('class' => "input_value",
                                                   'id'=> 'farmer' ,
                                                   'value' => $zal['farmer'] ?: '10,0' ));

        $form->addElement('submit', 'save', 'Zapisz zmiany założeń', array('class' => 'epesi_big_button' , 'style' => "position:fixed;left:0;bottom:35%;") );
        $form->toHtml();

        $form->assign_theme('my_form', $theme);

        $theme->assign("zalozenie",$zal);
        $theme->display();
        if ($form->validate_with_message("Zmiany zapisane", "Musisz podać odpowidni typ danych w polach")) {
            $array_fields = $form->exportValues();
            $zal['planned_amount'] = intval($array_fields['szt']);
            $zal['weight_pig_start'] = ($array_fields['weight_start']);
            $zal['weight_pig_end'] = ($array_fields['weight_end']);
            $zal['price_starter'] = $array_fields['price_st'];
            $zal['price_grower'] = $array_fields['price_gr'];
            $zal['price_finisher'] = $array_fields['price_fin'];
            $zal['price_pig'] = $array_fields['price_pig']."__2";
            $zal['med'] = $array_fields['med']."__2";
            $zal['lose'] = custom::change_spearator($array_fields['lose'],',','.');
            $zal['farmer'] = $array_fields['farmer']."__2";
            $zal['id_tuczu'] = $record['id'];

            if(Utils_RecordBrowserCommon::get_records("kontrakty_zalozenia", array('id_tuczu' => $record['id']))){
                Utils_RecordBrowserCommon::update_record("kontrakty_zalozenia", $_id, $zal);
            }else {
                Utils_RecordBrowserCommon::new_record("kontrakty_zalozenia", $zal);
            }

        }

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
            var to = parseFloat(jq("#weight_start").val().toString().replace(",","."));
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
            var from = parseFloat(jq("#weight_end").val().toString().replace(",","."));
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
            var feed_price_not_avg = feed_price;
            feed_price = feed_price / (parseFloat(st_amount) + parseFloat(gr_amount) + parseFloat(fin_amount));
            jq("#price_feed").val(parseFloat(feed_price).toFixed(4).toString().replace(".",","));

            //koszt sztuki netto
            var farmer = parseFloat(jq("#farmer").val());
            var medi = parseFloat(jq("#med").val());
            var lose = parseFloat(jq("#lose").val());
            var pig_price = parseFloat(jq("#price_pig").val());
            var netto_price = (pig_price + (feed_price_not_avg) / 2) / ( (100-lose)/100)  + farmer + medi + (feed_price_not_avg / 2);
            netto_price = parseFloat(netto_price).toFixed(2).toString().replace(".",",");
            jq("#price_netto").text(netto_price);

            //min cena tucznika
            var wyj = (netto_price).toString().replace(",",".") / parseFloat(jq("#weight_end").val().toString().replace(",","."));
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
        //print($help);
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
    public function upadekDelete($id){
        Utils_RecordBrowserCommon::delete_record("kontrakty_upadki",$id);
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
        $del_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/Calendar/delete.png' alt='Usuń' />";
        $edit_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/Calendar/edit.png' alt='Edytuj' />";
        $gb->set_table_columns(
            array(
                array('name'=> '' , 'width'=>5 ),
                array('name'=>'Data upadku', 'width'=>20),
                array('name'=>'Ilość padłych', 'width'=>20),
                array('name'=>'Waga padłych', 'width'=>20),
                array('name'=>'Notatki', 'width'=>20),
            )
        );
        $sumDown = 0;
        $sumKg = 0;
        foreach($inne as $p){
            $btns = $p->record_link($edit_btn,false,'edit');
            $del = "<a " . $this->create_confirm_callback_href("Na pewno usunąć?",
            array($this, "upadekDelete"), array($p->id)) . ">" . $del_btn . "</a>";
            $btns .= $del;
            $gb->add_row(   $btns,
                            $p->get_val('date_fall'),
                            $p->get_val('amount fall'),
                            $p->get_val('weight_fall'),
                            $p->get_val("note")
                        
            );

            $sumKg += custom::change_spearator($p['weight_fall'],",",".");
            $sumDown += $p['amount_fall'];
            $sumaKg = $sumaKg/$sumDown;
        }
        $bold = "<span style='font-size:14px;font-weight:bold;'> ";
        $boldEnd = "</span>";
        $gb->add_row(
            "",
            "",
            "$bold Padło: ".$sumDown. " szt. $boldEnd",
            "$bold Średnia waga padłych: ".custom::change_spearator($sumKg,".",","). " kg $boldEnd",
            ""
        );
        $this->display_module( $gb );
    }

    public function pozycje($record){}

    public function faktury_list($record){

        $help = "<ul style='text-align:left;'>";
        $help .= "<li> Zestawienie wszystkich faktur dla tuczu</li></ul>";
        print($help);
        custom::set_header("TUCZ - ".$record['name_number']);
        custom::create_new_faktura();

        $gb = &$this->init_module('Utils/GenericBrowser', null, 'Pasze');
        $gb->set_table_columns(
            array(
                array('name'=>'Opis', 'width'=>20 ),
                array('name'=>'Kwota', 'width'=>20),
                array('name'=>'Dostawca', 'width'=>20),
                array('name'=>'Nr faktury', 'width'=>20),
                array('name'=>'Data', 'width'=>20)
            )
        );
        $tables_names = ['kontrakty_faktury_dostawa_warchlaka', 'kontrakty_faktury_dostawa_paszy',
            'kontrakty_inne' , 'kontrakty_faktury_odbior_tucznika' , 'kontrakty_faktury_transporty'];
        $ids_list = [];
        for($i = 0;$i<count($tables_names);$i++){
            $rbo  = new RBO_RecordsetAccessor($tables_names[$i]);
            $ids = $rbo->get_records(array('id_tuczu' => $record['id']),array(),array());
            foreach($ids as $id){
                $ids_list[] = $id->fakt_poz;
            }
        }

        $fvs_poz  = new RBO_RecordsetAccessor("kontrakty_faktury_pozycje");
        $fvs_poz_list = $fvs_poz->get_records(array('id'=>$ids_list),array(),array());
        $ids_list = [];
        $price = [];
        foreach($fvs_poz_list as $fv_id){
            $ids_list[] = $fv_id->faktura;
            $price[$fv_id->faktura] += $fv_id->price;
        }

        $fvs  = new RBO_RecordsetAccessor("kontrakty_faktury");
        $fvs_list = $fvs->get_records(array('id'=>$ids_list),array(),array("Typ faktury"=> "DESC"));

        foreach ($fvs_list as $id) {
            $gb->add_row(
                '',
                number_format($price[$id->id],2,',',' ' ). " zł",
                $id->record_link($id->get_val('company')),
                $id->create_default_linked_label(false,false),
                $id->record_link($id->get_val('date'))
            );

        }
        /*
        $tabbed_browser = & $this->init_module('Utils/TabbedBrowser');
        $tabbed_browser->start_tab( 'Zakup' );
        print 'Lista faktur zakupowych';
        $tabbed_browser->end_tab();
        $tabbed_browser->start_tab( 'Transport' );
        print 'Lista faktur transportowych';
        $tabbed_browser->end_tab();
        $tabbed_browser->start_tab( 'Sprzedaż' );

        print 'Lista faktur sprzedażowych';
        $tabbed_browser->end_tab();

        $this->display_module( $tabbed_browser );*/
        $this->display_module( $gb );

    }
    function time2string($time) {
        $d = floor($time/86400);
        $_d = ($d < 10 ? '0' : '').$d;
        $time_str = $_d;
        return $time_str;
    }


    public function raport_rolnik($record){
        $help = "<ul style='text-align:left;'>";
        $help .= "<li> W tym miejscu mamy wygenerowany raport dla rolinka. Należy pamiętać ze nie będzie on dobrze wyliczony 
        jeżeli będzie brakowało danych</li></ul>";
        print($help);
        custom::create_new_faktura();
        custom::set_header("TUCZ - ".$record['name_number']);
        Base_ThemeCommon::install_default_theme($this->get_type());

        $theme = $this->init_module('Base/Theme');
        $zalozenia = Utils_RecordBrowserCommon::get_records("kontrakty_zalozenia", array("id_tuczu" => $record['id']),array(),array());
        foreach ($zalozenia as $zalozenie){$zalozenia = $zalozenie;break;}
        $dostawy = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_dostawa_warchlaka" , array("id_tuczu" => $record['id']),array(),array());
        $odbiory = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_odbior_tucznika", array("id_tuczu" => $record['id']),array(),array());
        $pasze = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_dostawa_paszy", array("id_tuczu" => $record['id']),array(),array());
        $upadki = Utils_RecordBrowserCommon::get_records("kontrakty_upadki", array("id_tuczu" => $record['id']),array(),array());
        $inne = Utils_RecordBrowserCommon::get_records("kontrakty_inne", array("id_tuczu" => $record['id']),array(),array());
        $details = array();
        $details['key'] = $record['kolczyk'];
        $details['date_start'] = $record['date_start'];
        $rolnik = Utils_RecordBrowserCommon::get_record("company",$record['farmer']);
        $details['farmer_name'] = $rolnik['company_name'];
        $details['farmer_name'] = preg_replace('/TN/', '', $details['farmer_name']);
        $details['farmer_name'] = preg_replace('/[0-9]/', '', $details['farmer_name']);
        $details['suma'] = 0;
        $details['bazowaCena'] = substr($zalozenia['farmer'],0,-3);
        $details['weterynariaCena'] = substr($zalozenia['med'],0,-3);
        $details['iloscPadlych'] = 0;
        foreach ($upadki as $upadek){
            $details['iloscPadlych'] += $upadek['amount_fall'];

        }
        $details['paszeKg'] = 0;
        $details['paszePrice'] = 0;

        $cenaPaszy = custom::change_spearator($zalozenia["price_starter"],",",".");
        
        $details['dateStart']  = $record['data_start'];
        foreach ($dostawy as $dostawa){
            $details['sumaWarchlakow'] += $dostawa['amount'];
            $fvs = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_pozycje" , array("id" => $dostawa['fakt_poz']),array(),array());
            foreach($fvs as $fv){
                $details['wagaWarchlakow'] += custom::change_spearator($fv['amount'],",",".");
            }
        }

        $optymalne = 0;
        $zlaWaga = 0;
        $kary = 0;

        foreach ($odbiory as $odbior){
            $details['sumaTucznikow'] += $odbior['amount'];
            $details['konfiskaty'] += $odbior['konfiskaty'];
            $details['dateEnd'] += strtotime($odbior['date_recived']);
            $details['wagaZywaTucznikow'] += $odbior['weight_alive_brutto'];
            $fvs = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_pozycje" , array("id" => $odbior['fakt_poz']),array(),array());
            foreach($fvs as $fv){
                $details['wagaTucznikow'] += custom::change_spearator($fv['amount'],",",".");
                $optymalne += $odbior['premiowane'];
                $kary += $odbior['suboptimal'];
                $zlaWaga += $odbior['badweight'];
            }
        }

        $details['dateEnd'] = $details['dateEnd'] / count($odbiory);
        $details['dateEnd'] = round($details['dateEnd'],0);
        $details['czasTuczu'] = $this->time2string($details['dateEnd'] -  strtotime($record['data_start']));

        foreach ($pasze as $pasza){

            $fvs = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_pozycje" , array("id" => $pasza['fakt_poz']),array(),array());
            foreach($fvs as $fv){
                $details['sumaPaszy'] += custom::change_spearator($fv['amount'],",",".");
            }
        }

        $details['inne'] = 0;
        foreach ($inne as $inny){
            $fvs = Utils_RecordBrowserCommon::get_records("kontrakty_faktury_pozycje" , array("id" => $inny['fakt_poz']),array(),array());
            foreach($fvs as $fv){
                $details['inne'] += custom::change_spearator($fv['amount'],",",".") * substr($fv['price'],0,-3) ;
            }
        }
        
        if( $details['inne'] != 0){
        $details['inne'] =  $details['inne'] / $details['pelnowartosciowe'];
        $details['inne'] = number_format($details['inne'], 2,',',' '). " zł/szt";
        }else{ $details['inne'] = $details['inne'] . " zł/szt" ;}
        $details['srZuzyciePaszy'] = $details['sumaPaszy'] /  ( $details['wagaZywaTucznikow'] - $details['wagaWarchlakow']);
        $details['srZuzyciePaszy'] = round( $details['srZuzyciePaszy'],2);
        $srZuzycie =  $details['srZuzyciePaszy'];
        $details['srZuzyciePaszy'] = custom::change_spearator($details['srZuzyciePaszy'],".",",");

        $details['srWagaTucznika'] =  $details['wagaZywaTucznikow'] / $details['sumaTucznikow'];
        $details['srWagaTucznika'] =   round( $details['srWagaTucznika'],2);
        $details['srWagaTucznika'] =  custom::change_spearator($details['srWagaTucznika'],".",",");

        $details['srWagaWarchlaka'] =  $details['wagaWarchlakow'] / $details['sumaWarchlakow'];
        $details['srWagaWarchlaka'] =   round( $details['srWagaWarchlaka'],2);
        $details['srWagaWarchlaka'] =  custom::change_spearator($details['srWagaWarchlaka'],".",",");

        $details['pelnowartosciowe'] = $details['sumaTucznikow'] - $details['konfiskaty'];
        $details['upadki'] = ($details['iloscPadlych'] + $details['konfiskaty']) / $details['sumaWarchlakow'];
        $details['upadki'] = round( $details['upadki'] ,2);
        $details['upadkiWartosc'] = $details['upadki'] * $details['pelnowartosciowe'];
        $details['upadkiWartosc'] = round( $details['upadkiWartosc'] ,2);
        $details['suma'] += $details['upadkiWartosc'];
        $details['upadkiWartosc'] = number_format($details['upadkiWartosc'], 2,',',' ');

        $details['bazowaWartosc'] = custom::change_spearator($details['bazowaCena'],",",".") * $details['pelnowartosciowe']  ;
        $details['weterynariaWartosc'] = custom::change_spearator($details['weterynariaCena'],",",".") * $details['pelnowartosciowe']  ;
        $details['suma'] += $details['bazowaWartosc'];
        $details['bazowaWartosc'] = number_format($details['bazowaWartosc'], 2,',',' ');
        $details['suma'] += $details['weterynariaWartosc'];
        $details['weterynariaWartosc'] = number_format($details['weterynariaWartosc'], 2,',',' ');
        $details['upadki']  = custom::change_spearator( $details['upadki'] ,".",",");

        $details['premiowane'] = $optymalne;
        $details['suboptimal'] = $kary; 
        $details['badweight'] = $zlaWaga;

        $details['premiowaneWartosc'] = $optymalne * 2;
        $details['suboptimalWartosc'] = $kary * -5; 
        $details['badweightWartosc'] = $zlaWaga * -10;

        $details['suma'] += $details['premiowaneWartosc'];
        $details['suma'] += $details['suboptimalWartosc'];
        $details['suma'] += $details['badweightWartosc'];

        $details['premiowaneWartosc'] = number_format($details['premiowaneWartosc'], 2,',',' ');
        $details['suboptimalWartosc'] = number_format($details['suboptimalWartosc'], 2,',',' ');
        $details['badweightWartosc'] = number_format($details['badweightWartosc'], 2,',',' ');
        

        if($zalozenia['deliverer'] == $record['farmer'] || $zalozenia['deliverer'] == $rolnik['parent_company'] ){
            $waga = $details['wagaZywaTucznikow'] ;
            $details['nfPrice'] = 0;
            $waga = $waga / $details['pelnowartosciowe'];
            $details['nf'] = true;
            if($waga >= 126.1){
                $wspolczynnik = 2.90;
            }
            else if($waga >= 122.1 && $waga <= 126){
                $wspolczynnik = 2.85;
            }
            else if ($waga >= 118.1 && $waga <= 122){
                $wspolczynnik = 2.80;
            }
            else if($waga >= 115.1 && $waga <= 118){
                $wspolczynnik = 2.75;
            }
            else if($waga >= 110.1 && $waga <= 115){
                $wspolczynnik = 2.70;
            }
            $details['nfPrice'] = (custom::change_spearator($details['wagaZywaTucznikow'],",",".") - custom::change_spearator($details['wagaWarchlakow'],",",".") 
            * $wspolczynnik) * custom::change_spearator($cenaPaszy,",",".");
            $details['nfPrice'] = round($details['nfPrice'],2);
            $details['suma'] += $details['nfPrice'];
            $details['nfPrice'] = number_format($details['nfPrice'], 2,',',' ');
            $details['nfPrice'] = custom::change_spearator($details['nfPrice'] ,".",",")." zł";

        }else{
            $premia = 0;
            $details['nfPrice'] = 0;
            $waga = $details['wagaZywaTucznikow'] ;
            $waga = $waga / $details['pelnowartosciowe'];
            $wspolczynnik = 0;
            $details['nf'] = true;
            if($waga >= 126.1){
                $wspolczynnik = 2.90;
            }
            else if($waga >= 122.1 && $waga <= 126){
                $wspolczynnik = 2.85;
            }
            else if ($waga >= 118.1 && $waga <= 122){
                $wspolczynnik = 2.80;
            }
            else if($waga >= 115.1 && $waga <= 118){
                $wspolczynnik = 2.75;
            }
            else if($waga >= 110.1 && $waga <= 115){
                $wspolczynnik = 2.70;
            }
            $details['nf'] = false;
            $avg = $wspolczynnik - $srZuzycie;
            if($avg > 0){
                $avg = $avg / 0.05;
                $avg = $avg + 0.05;
                if(!is_int($avg)){
                    $avg = floor($avg);
                }
                $premia = $avg * 3.0;
            }
            else{
                $avg = $avg / -0.03;
                $avg = $avg + 0.03;
                if(!is_int($avg)){
                    $avg = floor($avg);
                }
                $premia = $avg * -6.0;
            }
            $details['nfPrice'] = $details['pelnowartosciowe'] * $premia;
        }
        $details['sumaperone'] = $details['suma'] / $details['pelnowartosciowe'];
        $details['sumaperone'] = number_format($details['sumaperone'], 2,',',' '). " zł/szt";
        $details['suma'] = number_format($details['suma'], 2,',',' '). " zł";
        $theme->assign("details", $details);
        $theme->display('raport_rolnik');
    }

    public function raport_szefowa($record){
        custom::set_header("TUCZ - ".$record['name_number']);
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
    public function deleteTucz($id){
        Utils_RecordBrowserCommon::delete_record("kontrakty", $id);
    }

    public function actionBtns($record){
        $viewImg =   "<img src='data/Base_Theme/templates/default/Utils/GenericBrowser/view.png' />";
        $editImg =   "<img src='data/Base_Theme/templates/default/Utils/GenericBrowser/edit.png' />";
        $deleteImg = "<img src='data/Base_Theme/templates/default/Utils/GenericBrowser/delete.png' />";
        $infoImg =   "<img src='data/Base_Theme/templates/default/Utils/GenericBrowser/info.png' />";


        $view =  $record->record_link($viewImg, false, $action = 'view'  );
        $edit =  $record->record_link($editImg , false,  $action = 'edit');
        $delete =  "<a " . $this->create_confirm_callback_href("Na pewno usunąć ten tucz?",
        array($this, "deleteTucz"), array($record->id)) . ">" . $deleteImg . "</a>";
        $info = $record->get_html_record_info();

         return $view." ".$edit." ".$delete." ";   

    }


    public function body(){
        $fcallback = array('tuczkontraktowyCommon','fv_format');
        $form = $this->init_module('Libs/QuickForm'); 
        $form->addElement('autoselect', 'faktura', __('Szukaj tuczy przez nr faktury'), array(),
            array(array('tuczkontraktowyCommon','autoselect_fv'), array($crits, $fcallback)), $fcallback);
        $form->addElement("submit","submit","Szukaj");
        print('<table class="letters-search nonselectable" border="0" cellpadding="0" cellspacing="0">');
            print("<tbody><tr><td style='margin-right:25px; float:right;'>");
                $form->display();
            print("</td></tr></tbody>");
        print("</table>");
        if($form->validate()){
            $values = $form->exportValues();
            Base_ThemeCommon::install_default_theme($this->get_type());
            custom::create_new_faktura();
            Epesi::js('jq(".name").html("");
            jq(".name").html("<div> Tucze kontraktowe </div>");');
            $gb = &$this->init_module('Utils/GenericBrowser', null,"FVS");
            $gb->set_table_columns(
                array(
                    array('name'=>'', 'width'=>5),				
                    array('name'=>'Data wstawienia', 'width'=>12),
                    array('name'=>'Rolnik', 'width'=>12),
                    array('name'=>'Notatka', 'width'=>12),
                    array('name'=>'Kolczyk', 'width'=>12),
                    array('name'=>'Nazwa/Numer', 'width'=>12),
                    array('name'=>'Status', 'width'=>12),
                )
            );

            $kontrakty = new RBO_RecordsetAccessor('kontrakty');
            $faktury = new RBO_RecordsetAccessor('kontrakty_faktury_pozycje');
            if($values['faktura'] != ""){
                $recordsFaktury = $faktury->get_records(array("faktura"=>$values['faktura']),array(),array());
                $tucze = array();
                foreach($recordsFaktury as $r){
                    $tucz = new RBO_RecordsetAccessor(custom::table_names($r['typ_faktury']));
                    $lista = $tucz->get_records(array("fakt_poz"=> $r['id']),array(),array());
                    foreach($lista as $l){
                        $tucze[] = $l['id_tuczu'];
                    }
                }
                $lista = $kontrakty->get_records(array("id"=>$tucze),array(),array());
            }else{
                $lista = $kontrakty->get_records(array(),array(),array());
            }
            $view_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/GenericBrowser/view.png' alt='Podgląd' />";
            $edit_btn = "<img border='0' src='data/Base_Theme/templates/default/Utils/Calendar/edit.png' alt='Edytuj' />";
            foreach($lista as $l){
                $gb->add_row(
                    $l->record_link($view_btn,$nolink = false,$action = 'view')." ".$l->record_link($edit_btn,$nolink = false,$action = 'edit'),
                    $l['data_start'],
                    $l->get_val("farmer"),
                    $l['note'],
                    $l['kolczyk'],
                    $l['name_number'],
                    $l->get_val("status")
                );
            }
            $this->display_module($gb);
            Base_ActionBarCommon::add(
                'add',
                'Dodaj nową fakture', 
                Utils_RecordBrowserCommon::create_new_record_href('kontrakty_faktury',$def=array(),$id='none'),
                    null,
                    5
            );
        }
        else{
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
    }
    public function settings(){}    
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
    /**
     *
     * @param String $value   value to edit
     * @param String $from char to find and replace -> $to
     * @param String $to value will be returned with this separator
     */
    public static function change_spearator($value,$from,$to){
        $value = str_replace($from,$to,$value);
        return $value;
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
        if($table_id == "W"){
            $table_name = "kontrakty_faktury_dostawa_warchlaka";
        }
        else if($table_id == "T"){
            $table_name = "kontrakty_faktury_odbior_tucznika";
        }
        else if($table_id == "P"){
            $table_name = "kontrakty_faktury_dostawa_paszy";
        }
        else if($table_id == "OTH"){
            $table_name = "kontrakty_inne";
        }
        else if($table_id == "TR"){
            $table_name = "kontrakty_faktury_transporty";
        }
        else if($table_id == "Z"){
        }
        return $table_name;
    }
    public static function table_fields($table_id){
        $fields = [];
        if($table_id == 'W'){
            array_push($fields, array('name' => 'amount' , 'type' => 'text', 'rule'=>'numeric' , 'msg' => "Dozwolone same cyfry"));
            array_push($fields, array('name' => 'weight_on_drop' , 'type' => 'text'));
            }
        if($table_id == 'P') {
            $feeds = Utils_CommonDataCommon::get_array("/Faktury/pasze");
            array_push($fields, array('name' => 'feed_type', 'type' => 'select', 'options' => $feeds));
        }

        if($table_id == 'T') {
            array_push($fields, array('name' => 'date_recived', 'type' => 'date'));
            array_push($fields, array('name' => 'amount', 'type' => 'text', 'rule' => 'numeric', 'msg' => "Dozwolone same cyfry"));
            array_push($fields, array('name' => 'weight_alive_brutto', 'type' => 'text'));
            array_push($fields, array('name' => 'meatiness', 'type' => 'text'));
            array_push($fields, array('name' => 'konfiskaty', 'type' => 'text'));
            array_push($fields, array('name' => 'premiowane', 'type' => 'text'));
            array_push($fields, array('name' => 'suboptimal', 'type' => 'text'));
            array_push($fields, array('name' => 'badweight', 'type' => 'text'));
        }
        if($table_id == 'TR') {
            $opt = Utils_RecordBrowserCommon::get_records('company', array('group' => 'ubojnia'), array('company_name'), array());
            $companies = array();
            foreach ($opt as $option) {
                $companies[$option['id']] = $option['company_name'];
            }
            array_push($fields, array('name' => 'date', 'type' => 'date'));
            array_push($fields, array('name' => 'amount', 'type' => 'text', 'rule' => 'numeric', 'msg' => "Dozwolone same cyfry"));
            array_push($fields, array('name' => 'netto', 'type' => 'text'));
            array_push($fields, array('name' => 'company', 'type' => 'select', 'options' => $companies));
        }
        if($table_id == 'OTH') {
            $opt = Utils_CommonDataCommon::get_array("Kontrakty/inne");
            array_push($fields, array('name' => 'other_type', 'type' => 'select', 'options' => $opt));
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
        $this->weight_gr =  ($this->zalozenia['grower_to'] - $this->zalozenia['grower_from']) * $this->zalozenia['average_use_gr'];
        $this->weight_fin = ($this->zalozenia['weight_pig_end'] - $this->zalozenia['finisher_from']) * $this->zalozenia['average_use_fin'];
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
