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
        <td> {$details.dateStart} </td>
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
        <td> {$details.czasTuczu} dni </td>
    </tr>
    <tr>
        <td> Koszt leczenia na sztukę pełnowartościową	</td>
        <td> {$details.inne} </td>
    </tr>
    <tr>
        <td> Średnie zużycie paszy	</td>
        <td> {$details.srZuzyciePaszy} kg  </td>
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
        <td> {$details.pelnowartosciowe}  szt </td>
        <td> {$details.bazowaCena} zł/szt </td>
        <td> {$details.bazowaWartosc} zł </td>
    </tr>
    <tr>
        <td> Weterynaria </td>
        <td> {$details.pelnowartosciowe} szt </td>
        <td> {$details.weterynariaCena} zł/szt </td>
        <td> {$details.weterynariaWartosc} zł </td>
    </tr>
    <tr>
        <td> Upadki </td>
        <td> {$details.pelnowartosciowe} szt </td>
        <td> {$details.upadki}  zł </td>
        <td> {$details.upadkiWartosc} zł </td>
    </tr>
    <tr>
        <td> Premia wagi optymalnej </td>
        <td>  {$details.premiowane}  szt</td>
        <td> 2 zł</td>
        <td> {$details.premiowaneWartosc} zł</td>
    </tr>
    <tr>
        <td> Kara wagi słabe</td>
        <td>  {$details.suboptimal}  szt </td>
        <td> -5 zł</td>
        <td> {$details.suboptimalWartosc} zł</td>
    </tr>

    <tr>
        <td> Kara wagi krytyczne +- </td>
        <td> {$details.badweight} szt</td>
        <td> -10 zł</td>
        <td> {$details.badweightWartosc} zł</td>
    </tr>

</table>
    <table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <th colspan="3"> Pasza </th>

    </tr>
        {if $details.nf }
    <tr>
        <td> Nowa formuła - NF </td>
        <td colspan='2'> {$details.nfPrice} </td>
    </tr>
    {else}
    <tr>
        <td> Formuła Pełna - PF  </td>
        <td colspan='2'> {$details.nfPrice}  </td>
    </tr>
    {/if}
    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>
    <tr>
        <th> SUMA: </th>
        <td colspan="2"> {$details.suma}  </td>
    </tr>
    <tr>
        <th> Za sztukę: </th>
        <td colspan="2"> {$details.sumaperone} </td>
    </tr>
    </table>

