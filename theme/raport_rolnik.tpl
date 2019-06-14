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
        <td> {$details.sumaWarchlakow} </td>
        <td> {$details.key} </td>
    </tr>
    </table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">

    <tr>
        <td> Waga średnia wstawienia warchlaka </td>
        <td>  {$details.srWagaWarchlaka} </td>
    </tr>
    <tr>
        <td> Ilość tuczników odebranych </td>
        <td> {$details.sumaTucznikow}  </td>
    </tr>
    <tr>
        <td> Waga średnia żywa oddanego tucznika</td>
        <td> {$details.srWagaTucznika} </td>
    </tr>
    <tr>
        <td> Czas tuczu	</td>
        <td> {$details.czasTuczu}  </td>
    </tr>
    <tr>
        <td> Koszt leczenia na sztukę pełnowartościową	</td>
        <td> </td>
    </tr>
    <tr>
        <td> Średnie zużycie paszy	</td>
        <td> {$details.srZuzyciePaszy}  </td>
    </tr>
</table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">

    <tr>
        <th colspan=''>Składnik cenowy</th>
        <th colspan=''>Sztuki</th>
        <th colspan=''>Cena jednostkowa</th>
        <th colspan=''>Wartość</th>
    </tr>
    <tr>
        <td> Stawka bazowa </td>
        <td> {$details.pelnowartosciowe}  </td>
        <td>  </td>
        <td>  </td>
    </tr>
    <tr>
        <td> Weterynaria </td>
        <td> {$details.pelnowartosciowe} </td>
        <td>  </td>
        <td>  </td>
    </tr>
    <tr>
        <td> Upadki </td>
        <td> {$details.pelnowartosciowe}  </td>
        <td> {$details.upadki}   </td>
        <td>  </td>
    </tr>
    <tr>
        <td> Premia wagi optymalnej </td>
        <td>  </td>
        <td> 2 </td>
        <td>  </td>
    </tr>
    <tr>
        <td> Kara wagi słabe</td>
        <td>  </td>
        <td> -5 </td>
        <td>  </td>
    </tr>

    <tr>
        <td> Kara wagi krytyczne + </td>
        <td>  </td>
        <td> -10 </td>
        <td>  </td>
    </tr>
    <tr>
        <td> Kara wagi krytyczne - </td>
        <td>  </td>
        <td> -10 </td>
        <td>  </td>
    </tr>
</table>
    <table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <th colspan="3"> Pasza </th>

    </tr>
        {if $details.nf }
    <tr>
        <td> Nowa formuła - NF </td>
        <td>  </td>
        <td>  </td>
    </tr>
    {else}
    <tr>
        <td> Formuła Pełna - PF  </td>
        <td>  </td>
        <td>  </td>
    </tr>
    {/if}
    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>
    <tr>
        <th> SUMA: </th>
        <td colspan="2">  </td>
    </tr>
    <tr>
        <th> Za sztukę: </th>
        <td colspan="2">  </td>
    </tr>
    </table>

