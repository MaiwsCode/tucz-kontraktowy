<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Kostrzewski
 * Date: 29.03.2019
 * Time: 15:10
 */


class HtmlView
{
    public function __construct()
    {

    }

    /**
     *
     * @param Array $elements  tablica z listą do wyświetlenia klucz => wartośc
     * @param String $default  Domyślna wartość występująca w tablicy
     * @param String $on_change  Akcja która ma sie wykonac po wyborze któregoś z pola
     * @param Bool $with_button_submit  Czy ma posiadać obok przycisk do zatwierdzania operacji
     * @param String $btn_value Text dla przycisku
     *
     */
    public function selectListHref($elements, $default, $with_button_submit, $btn_value, $select_id, $href){
        if($with_button_submit){
            $select = "<select id='".$select_id."' onchange='update_btn_value(this)' style='width: 10%;border-radius: 13px 13px; padding-left: 10px;' >";
        }else {
            $select = "<select style='width: 10%;border-radius: 13px 13px; padding-left: 10px;' >";
        }
        $elements_list = "";
        foreach ($elements as $key => $value){
            if($default == $value){
                $elements_list .= "<option selected='selected' value='" . $key . "' > " . $value . " </option>";
            }
            else {
                $elements_list .= "<option value='" . $key . "' > " . $value . " </option>";
            }
        }
        $select .= $elements_list . " </select>";
        if($with_button_submit){
            $btn = "<a id='".$select_id."_btn' $href > $btn_value </a> ";
            $select .= $btn;
        }
        epesi::js('
            function update_btn_value(el){
                var element = jq(el);
                var value = (element.val());
                var btn = jq("#'.$select_id.'_btn");
                var btn_on_click = btn.attr("onclick");
                var txt = btn_on_click;
                var from = txt.indexOf("=");
                var txt2 = txt.substr(from,txt.length- from);
                var to = txt2.indexOf("\'");
                var txt3 = txt2.substr(1,to-1);
                btn_on_click  = btn_on_click.replace(txt3,value);
                btn.attr("onclick",btn_on_click);
            }
            
            jq(".farmer").on("change", function (){
                
            });

        ');

        return $select;
    }  
  }

  class Browser{
    /**
     *
     * @param Array $fields  tablica nazwa pola z db => (typ pola(text,datepicker) , przetlumaczona nazwa  )
     *
     */
    public $form;
    private $form_elements = array();
    public function __construct($_form,$fields){
        $this->form = $_form;
        foreach($fields as $field){
            if($field[0] == "select") {
                $this->form->addElement($field[0], $field[1], __($field[2]), $field[3]);
            }else{
                $this->form->addElement($field[0], $field[1], __($field[2]) , array('value'=> $field[3]));
            }
        }
    }


    public function display($cancel){
        print("<div> 
                <div style='text-align:left;padding-left:6px;'> <h3>Wyszukiwarka</h3></div> 
                <div class='browser_elements'>");
       $this->form->addElement("submit",'find', "Szukaj");
       $this->form->addElement('button','cancel_button','Resetuj wyszukiwarke',$cancel);
       $this->form->display_as_row();
       print("</div></div>");
    }

}
