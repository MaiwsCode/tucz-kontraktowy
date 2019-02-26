{$my_form_open}

<div style='position:relative;'>
    <div style='position:absolute;top:15%;'>
       
    </div>
</div>
<br>
<div style='text-align:center;min-width:100%;max-width:100%;'>

<h1 style='text-align:center;'>ZAŁOŻENIA</h1>
</div>
<br>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <th> Imię i Nazwisko </th>
        <th> Data wstawienia </th>
        <th> Nr gosp. </th>
        <th> Planowana ilość </th>
        <th> Kolczyk </th>
    </tr>
    <tr>				
        <td> {$tucz.farmer}	 </td>
        <td> {$tucz.data_start}</td>
        <td> </td>
        <td> {$my_form_data.szt.html}  </td>
        <td> {$tucz.kolczyk} </td>
    </tr>
</table>

<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <td> Waga wstawienia warchlaka  </td>
        <td>
            {$my_form_data.weight_start.html}

            kg </td>
    </tr>
    <tr>
        <td> Oczekiwana waga wyjściowa  </td>
        <td>
            {$my_form_data.weight_end.html}

            kg </td>
    </tr>
    <tr>
        <td > Starter </td>
        <td>
            {$my_form_data.price_st.html}
        zł/kg </td>
    </tr>
    <tr>
        <td> Grower	</td>
        <td>
            {$my_form_data.price_gr.html}
        zł/kg </td>
    </tr>
    <tr>
        <td> Finisher </td>
        <td>
            {$my_form_data.price_fin.html}
         zł/kg </td>
    </tr>
    <tr>
        <td> Cena warchlaka </td>
        <td>
            {$my_form_data.price_pig.html}
        zł/sztukę </td>
    </tr>
    <tr>
        <td> Średnia ważona cena paszy </td>
        <td> {$my_form_data.price_feed.html} zł/kg </td>
    </tr>
</table>

<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;display:none;">
<tr><th colspan='3'>Pasza</th></tr>
    <tr>
        <th>
            Zużycie na kg przyrostu
        </th>
        <th colspan='2'>
            Waga
        </th>
    </tr>
    <tbody>
        <tr>
            <td> Starter 
            {if $avg_usage.starter}
                <input type='text' class='input_value' id='st_mp' value='{$avg_usage.starter}' />
            {else}
                <input type='text' class='input_value' id='st_mp' value='0' />
            {/if}
            </td>	
            <td > Wst. </td>
            <td> 
                <input id='st_to' type='text' class='input_value' value='{$limits.starter_grower}' />  
            </td>
        </tr>
        <tr>
            <td> Grower 
            {if $avg_usage.grower}
                <input type='text' class='input_value' id='gr_mp' value='{$avg_usage.grower}' />
            {else}
                <input type='text' class='input_value' id='gr_mp' value='0' />
            {/if}
             </td>
            <td> 
                <input id='gr_from' type='hidden' class='input_value' value='{$limits.starter_grower}' /> 
             </td>
            <td>
                <input id='gr_to' type='text' class='input_value' value='{$limits.grower_finisher}' />   
            </td>
        </tr>
        <tr>
            <td> Finisher 
            {if $avg_usage.grower}
                <input type='text' class='input_value' id='fin_mp' value='{$avg_usage.finisher}' />
            {else}
                <input type='text' class='input_value' id='fin_mp' value='0' />
            {/if}
            </td>
            <td>  
                <input id='fin_from' type='hidden' class='input_value' value='{$limits.grower_finisher}' /> 

            </td>	
            <td> Wyj </td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <th> Wyliczenia </th>
        </tr>
        <tr>
            <td> Starter </td>
            <td id='feed_st'> <span class='val'> --- </span> kg </td>
        </tr>
        <tr>
            <td> Grower	</td>
            <td id='feed_grow'> <span class='val'> --- </span> kg </td>
        </tr>
        <tr>
            <td> Finisher </td>
            <td id='feed_fin'> <span class='val'> ---  </span> kg </td>
        </tr>
        <tr>
            <td> Razem </td>
            <td id='feed_sum'> <span class='val'> </span> kg </td>
        </tr>
    </tbody>
</table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
 <tr>
        <th colspan='2'>
            Wybrane zakładane średnie zużycie: 
        </th>
        <th >
        {if $selected_plan}
            {$selected_plan}
        {else}
            Nie wybrano - przejdz do zakładki edycji aby wybrać
        {/if}
        </th>
    </tr>
</table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <td> Lekarstwa </td>
        <td>
            {$my_form_data.med.html}
        zł</td>
    </tr>
    <tr>
        <td> Ubytki </td>
        <td>
            {$my_form_data.lose.html}
        %</td>
    </tr>
    <tr>
        <td> Rolnik </td>
        <td>
            {$my_form_data.farmer.html}
            zł </td>
    </tr>
    <tr>
        <td> Koszt sztuki NETTO </td>
        <td> <span id='price_netto'> 0</span> zł </td>
    </tr>
    <tr>
        <td> Cena tucznika ŻYWA </td>
        <td> <span id='price_netto_per_one'>0</span> zł </td>
    </tr>
    <tr>
     <td>  Cena tucznika WBC </td>
       <td> <span id='wbc'> </span> zł </td>
    </tr>

</table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <th> Ilość paszy zakontraktowanej </th> 
        <th id='all_feed_kontr'> </th>
    </tr>
    <tr>
        <td>Starter</td>
        <td id='st_kontr' > </td>
    </tr>
    <tr>
        <td>Grower</td>
        <td id='gr_kontr' > </td>
    </tr>
    <tr>
        <td>Finisher</td>
        <td id='fin_kontr'> </td>
    </tr> 
</table>
{$my_form_data.save.html}

{$my_form_close}
