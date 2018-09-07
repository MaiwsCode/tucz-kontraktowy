<h1> Raport dla szefowej </h1>

<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">

    <tr>
        <td> Ilość sztuk odebranych </td>
        <td> {$details.recived_pigs} </td>
        <td></td>
    </tr>
    <tr>
        <td> ilość sztuk padłych </td>
        <td > {$details.pigs_death} szt </td>
        <td></td>
    </tr>
    <tr>	
        <td>zakładany czas upadku 1/3 </td>
        <td> 33,0% </td>
        <td></td>
    </tr>		
    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>
    <tr>
        <td>Łącznie waga paszy</td>
        <td> {$details.feed_kg} kg </td>
        <td></td>	
    </tr>
    <tr>
        <td>ilość paszy na świnię (żywą) </td>
        <td> {$details.zuPasza}	</td>
        <td></td>
    </tr>
    <tr>
        <td> ilość paszy na padłe sztuki </td> 
        <td> {$details.zuPaszaDeath} kg </td>
        <td></td>
    </tr>
    <tr>
        <td> średnie zużycie na świnię (żywą) </td> 
        <td> {$details.feed_kg} kg </td>
        <td> {$details.diff_2} zł </td>
    </tr>
    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>
    <tr>         
        <td> średnie zużycie paszy na kg przyrostu </td>
        <td> {$details.avg_use_feed_per_kg}	kg/kg </td>
        <td></td>	
    </tr>
    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>
    <tr>        
        <td> średni przyrot świni (zywej) </td> 
        <td> {$details.avg_pig_grow}	kg	</td>
        <td></td>
    </tr>
    <tr>
        <td> średna cena paszy </td> 
        <td> {$details.avg_price_feed} zł	</td>
        <td></td>
    </tr>
    <tr>
        <td> koszt padłych sztuk - łączny </td>	
        <td>  {$details.sum_cost_death} zł </td>
        <td></td>
    </tr>	
    <tr>
        <td> Zakładany koszt padłych sztuk </td>
        <td> {$extra.death_cost} zł 	</td>
        <td></td>
    </tr>
    <tr>	
        <td> koszt upadku do sztuki oddanej </td>
        <td> {$details.cost_death_one} zł </td>
        <td></td> 		
    </tr>
    <tr>
        <td> Zakładany koszt upadku do sztuki oddanej </td>	
        <td> {$extra.planed_death_cost} zł </td>
        <td> {$extra.diff_death_cost} zł </td>
    </tr>
    <tr>
        <td> średnia waga świni oddanej </td> 
        <td> {$details.avg_weight_pig} kg </td>
        <td> {$extra.diff_1} zł </td>
    </tr>
    <tr>
        <td> średnia wydajność </td>
        <td> {$details.avg_eff} % </td>
        <td></td>
    </tr>
    <tr>
        <td>Zakłądana średnia cena za kg świni </td>
        <td> {$extra.planned_avg_per_kg} zł / kg</td>
        </td> </td>
    </tr>
    <tr>
        <td> średnia cena za kg żywej wagi </td>
        <td> {$extra.avg_price_live_weight} zł /kg	</td>
        <td></td>
    </tr>
    <tr>
        <td> premia strata za nie uzyskanie zakładnaych parametrów na kg świni </td>
        <td> {$extra.premium} zł /kg </td>
        <td></td> 	
    </tr>
    <tr>
        <td> premia strata za nie uzyskanie zakładnaych parametrów (zł / szt) </td>
        <td> </td>
        <td> {$extra.premium_2} zł  </td>
    </tr>
    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>
    <tr>           
        <td> Wydatki weterynarz - suma </td>
        <td> {$details.wet_cost} zł </td>
        <td></td>	
    </tr>
    <tr>
        <td> wydatki na sztukę żywą </td>
        <td> {$extra.cost_per_one_pig_live} zł / szt </td>
        <td> {$extra.diff_3}7 </td>
    </tr>
    <tr>
        <td> średni czas tuczu </td>
        <td>	{$details.tucz_time} dni </td>
        <td></td>
    </tr>
    <tr>
        <td> SUMA premii / potrąceń: </td>
        <td></td>
        <td> {$extra.potracenia} zł </td>
    </tr>   
    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>         
    <tr>
        <td> Finalny wynik na sztuce dla ROLNIKA	</td>
        <td colspan='2'> {$extra.final}zł </td>
    </tr>
</table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <th> WYLICZENIA NETTO dla ATH </th>	
        <th> Zakup </th>
        <th> Sprzedaż </th>
        <th> </th>	
    </tr>
    <tr>
        <td> Wartość warchlaka </td>
        <td> ??? skad te dane zł </td>
        <td> {$details.warch_cost} zł </td>
        <td> --- zł </td>
    </tr>
    <tr>
        <td> Wartość paszy </td>
        <td> ??? zł </td>
        <td> {$details.feed_cost} zł </td>
        <td> --- zł </td>
    </tr>
    <tr>
        <td> Wartość tucznika </td>
        <td>{$details.tucz_cost} zł </td>
        <td> {$extra.tucz_sell} zł </td> 
        <td> ---- zł </td>
    </tr>
    <tr>
        <td> Koszty leczenia </td>
        <td> {$details.wet_cost} zł </td>
        <td> {$details.wet_cost} zł </td>
        <td> 0,00 zł </td>
    </tr>
    <tr>
        <td> Kredytowanie </td> 
        <td> ---- zł </td>
        <td></td>
        <td> ---- zł </td>
    </tr>
    <tr>
        <td> Koszt transportu tuczników	 </td>
        <td> {$extra.cost_transport} zł </td>
        <td> </td>
        <td> </td>
    </tr>
    <tr>
        <td colspan='3'> RÓŻNICA: </td>
        <td> ---- zł </td>
    </tr>


</table>