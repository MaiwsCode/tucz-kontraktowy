
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
        <td>   </td>
        <td> <input type='text' id='szt' value='{$zalozenie.planned_amount}' class='input_value' /> </td>
        <td> {$tucz.kolczyk} </td>
    </tr>
</table>

<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <td> Waga wstawienia warchlaka  </td>
        <td>  
        {if $zalozenie.weight_pig_start}
            <input type='text' id='weight_start' class='input_value' value='{$zalozenie.weight_pig_start}' /> 
         {else}
            <input type='text' id='weight_start' class='input_value' value='0' /> 
         {/if}
         kg </td>
    </tr>
    <tr>
        <td> Oczekiwana waga wyjściowa  </td>
        <td> 
        {if $zalozenie.weight_pig_end}
            <input type='text' value='{$zalozenie.weight_pig_end}' id='weight_end' class='input_value' /> 
        {else}
             <input type='text' value='0' id='weight_end' class='input_value' /> 
        {/if}   
        kg </td>
    </tr>
    <tr>
        <td > Starter </td>
        <td> 
        {if $zalozenie.price_starter}
            <input type='text' class='input_value' id='price_st' value='{$zalozenie.price_starter|replace:'.':','}' /> 
        {else}
            <input type='text' class='input_value' id='price_st' value='0' />
        {/if}
        zł/kg </td>
    </tr>
    <tr>
        <td> Grower	</td>
        <td> 
        {if $zalozenie.price_grower}
            <input type='text' class='input_value' id='price_gr'  value='{$zalozenie.price_grower|replace:'.':','}' /> 
        {else}
            <input type='text' class='input_value' id='price_gr'  value='0' />        
        {/if}
        zł/kg </td>
    </tr>
    <tr>
        <td> Finisher </td>
        <td> 
        {if $zalozenie.price_finisher}
            <input type='text' class='input_value' id='price_fin'  value='{$zalozenie.price_finisher|replace:'.':','}' />
        {else}
            <input type='text' class='input_value' id='price_fin'  value='0' />
        {/if} 
         zł/kg </td>
    </tr>
    <tr>
        <td> Cena warchlaka </td>
        <td> 
        {if $zalozenie.price_pig}
            <input type='text' id='price_pig' value='{$zalozenie.price_pig|replace:'.':','}' class='input_value' /> 
        {else}
            <input type='text' id='price_pig' value='0' class='input_value' /> 
        {/if}
        zł/sztukę </td>
    </tr>
    <tr>
        <td> Średnia ważona cena paszy </td>
        <td> <input type='text' id='price_feed' value='0' class='input_value' /> zł/kg </td>
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
        {if $zalozenie.med}
            <input type='text' id='med' value='{$zalozenie.med|replace:'.':','}' class='input_value' /> 
        {else}
            <input type='text' id='med' value='10' class='input_value' /> 
        {/if}
        zł</td>
    </tr>
    <tr>
        <td> Ubytki </td>
        <td> 
        {if $zalozenie.lose}
            <input id='lose' type='text' value='{$zalozenie.lose}' class='input_value' /> 
        {else}
            <input id='lose' type='text' value='3' class='input_value' /> 
        {/if}
        %</td>
    </tr>
    <tr>
        <td> Rolnik </td>
        <td> 
        {if $zalozenie.farmer}
            <input type='text' id='farmer' value='{$zalozenie.farmer|replace:'.':','}' class='input_value' /> 
        {else}
            <input type='text' id='farmer' value='37' class='input_value' /> 
        {/if}
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
