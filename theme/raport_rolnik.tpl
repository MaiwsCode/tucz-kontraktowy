<h1> ROZLICZENIE TUCZU </h1>

<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <th> Imię i Nazwisko </th>
        <th> Data wstawienia </th>
        <th> Ilość wstawiona </th>
        <th> Kolczyk </th>
    </tr>
    <tr>
        <td> {$details.farmer_name} </td>
        <td> {$details.date_start} </td>
        <td> {$details.amount_pigs} </td>
        <td> {$details.key} </td>
    </tr>
    </table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <th>WYLICZENIA PARAMETRÓW TUCZU </th>
        <th> na sztukę</th>

    </tr>
    <tr>
        <td> Waga wstawienia warchlaka </td>
        <td> {$details.start_weight} </td>
    </tr>
    <tr>
        <td> Waga oddanego tucznika </td>
        <td> {$details.avg_weight_pig } kg </td>
    </tr>
    <tr>
        <td>Ilość zużytej paszy	</td> 
        <td> {$details.zuPasza} </td>
    </tr>
    <tr>
        <td>Ilość odebranego tucznika	</td> 
        <td> {$details.recived_pigs} </td>
    </tr>

    </tr>
    <tr style='background:#F0F0F0;'> <td colspan='2'></td></tr>
    <tr><th colspan='2'>WYNIKI TUCZU</th></tr>
    <tr>
        <td> Średnie zużycie paszy</td>
        <td> {$details.avg_use_feed_per_kg} </td>
    </tr>
    <tr>
        <td> Upadki w % </td>
        <td> {$details.dead_in_percent} </td>
    </tr>
    <tr>
        <td> Średnia mięsność	</td>
        <td> {$details.avg_meatens} % </td>
    </tr>
    <tr>
        <td> Średnia wydajność </td>
        <td> {$details.avg_eff } % </td>
    </tr>
    <tr>
        <td> Czas tuczu </td>
        <td> {$details.tucz_time } </td>
    </tr>
    <tr style='background:#F0F0F0;'> <td colspan='2'></td></tr>
    <tr><th colspan='2'>     WYLICZENIA NETTO TUCZU W PLN </th></tr>
    <tr>
        <td> Wartość warchlaka </td>
        <td> {$details.warch_cost} zł 	</td>
    </tr>
    <tr>
        <td> Wartość paszy </td>
        <td> {$details.feed_cost} zł 	</td>
    </tr>
    <tr>
        <td> Wartość tucznika </td>
        <td> {$details.tucz_cost} zł 	</td>
    </tr>
    <tr>
        <td> Koszty leczenia </td>
        <td> {$details.wet_cost} zł </td>
    </tr>
    <tr>
        <td>RÓŻNICA: </td>
        <td> {$details.diff}zł  </td>	
    </tr>			
    <tr style='background:#F0F0F0;'> <td colspan='2'></td></tr>
     <tr><th colspan='2'> ROLNIK </th></tr>   
	<tr>
        <td>Podstawa </td>
        <td> {$details.farmer_base } zł </td>
    </tr>
    <tr>
        <td> Premia/ Potrącenia </td>
        <td>  {$details.premia } zł </td>
    </tr>
    <tr>
        <td> RAZEM: </td>
        <td>  {$details.farmer_profit } zł </td>
    </tr>
    </table>

