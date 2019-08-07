
<table cellspacing='0' border="1"  cellpadding="3" >
    <tr>
        <th bgcolor="#d1d1d1"> <h3> Imię i Nazwisko </h3> </th>
        <th bgcolor="#d1d1d1"> <h3> Data wstawienia </h3> </th>
        <th bgcolor="#d1d1d1"> <h3> Ilość wstawiona </h3> </th>
        <th bgcolor="#d1d1d1"> <h3> Kolczyk </h3>         </th>
    </tr>
    <tr>
        <td><span ><font size="12" >  {$details.farmer_name}  </font> </span></td>
        <td ><span ><font size="12" >   {$details.dateStart}  </font> </span></td>
        <td ><span ><font size="12">   {$details.zakladanaIlosc} </font> </span> </td>
        <td ><span ><font size="12">   {$details.key} </font> </span></td>
    </tr>
    <tr><td colspan="4"></td></tr>
    <tr>
        <td  bgcolor="#e6e6e6"   colspan="2"> <span ><font size="12">Waga średnia wstawienia warchlaka </font> </span> </td>
        <td > <span ><font size="12">   {$details.srWagaWarchlaka}</font> </span>  </td>
    </tr>
    <tr>
        <td  bgcolor="#e6e6e6"  colspan="2"> <span ><font size="12">Ilość tuczników odebranych  </font> </span> </td>
        <td > <span ><font size="12" >  {$details.sumaTucznikow} </font> </span>  </td>
    </tr>
    <tr>
        <td bgcolor="#e6e6e6" colspan="2"> <span ><font size="12">Waga średnia żywa oddanego tucznika </font> </span>  </td>
        <td > <span ><font size="12">{$details.srWagaTucznika} </font> </span> </td>
    </tr>
    <tr>
        <td bgcolor="#e6e6e6" colspan="2"> <span ><font size="12">Czas tuczu</font> </span> 	</td>
        <td > <span ><font size="12"> {$details.czasTuczu} dni </font> </span> </td>
    </tr>
        <tr>
        <td bgcolor="#e6e6e6"   colspan="2"> <span ><font size="12">Średni przyrost dobowy </font> </span>  </td>
        <td > <span ><font size="12">{$details.srPrzyrostDobowy} kg/dzień </font> </span> </td>
    </tr>
    <tr>
        <td bgcolor="#e6e6e6" colspan="2"> <span ><font size="12">Koszt leczenia na sztukę pełnowartościową </font> </span> 	</td>
        <td > <span > <font size="12"> {$details.inne} zł/szt</font> </span>  </td>
    </tr>
    <tr>
        <td bgcolor="#e6e6e6" colspan="2"> <span ><font size="12">Średnie zużycie paszy	</font> </span> </td>
        <td > <span ><font size="12">{$details.srZuzyciePaszy} kg  </font> </span> </td>
    </tr>
    <tr>
        <th bgcolor="#d1d1d1"><h3> Składnik cenowy </h3></th>
        <th bgcolor="#d1d1d1"><h3> Sztuki </h3></th>
        <th bgcolor="#d1d1d1"><h3> Cena jednostkowa </h3></th>
        <th bgcolor="#d1d1d1"><h3> Wartość </h3></th>
    </tr>
    <tr>
        <td  bgcolor="#e6e6e6" > <span ><font size="12">Stawka bazowa </font> </span> </td>
        <td> <span ><font size="12">{$details.pelnowartosciowe}  szt</font> </span>  </td>
        <td> <span ><font size="12">{$details.bazowaCena} zł/szt </font> </span> </td>
        <td> <span ><font size="12">{$details.bazowaWartosc} zł </font> </span> </td>
    </tr>
    <tr>
        <td bgcolor="#e6e6e6"  > <span ><font size="12">Weterynaria </font> </span> </td>
        <td><span ><font size="12"> {$details.pelnowartosciowe} szt </font> </span> </td>
        <td> <span ><font size="12">{$details.weterynariaCena} zł/szt</font> </span>  </td>
        {if $details.weterynariaWartosc[0] == "-"}
            <td ><span><font color="red" size="12"> {$details.weterynariaWartosc} zł </font> </span></td>
        {else}
            <td><span ><font size="12"> {$details.weterynariaWartosc} zł </font> </span> </td>
        {/if}
    </tr>
    <tr>
        <td bgcolor="#e6e6e6" ><span ><font size="12"> Upadki </font> </span> </td>
        <td ><span ><font size="12"> {$details.pelnowartosciowe} szt </font> </span> </td>
        <td> <span ><font size="12">{$details.upadki}  % </font> </span> </td>
        {if $details.upadkiWartosc[0] == "-"}
            <td> <span><font color="red" size="12"> {$details.upadkiWartosc} zł </font></span> </td>
        {else}
            <td><span ><font size="12"> {$details.upadkiWartosc} zł </font> </span> </td>
        {/if}
    </tr>
    <tr>
        <td bgcolor="#e6e6e6"> <span ><font size="12">Premia wagi optymalnej </font> </span> </td>
        <td> <span ><font size="12"> {$details.premiowane}  szt </font> </span> </td>
        <td> <span ><font size="12">2 zł  </font> </span> </td>
        <td><span ><font size="12"> {$details.premiowaneWartosc} zł </font> </span> </td>
    </tr>
    <tr>
        <td bgcolor="#e6e6e6"><span ><font size="12"> Kara wagi słabe </font> </span> </td>
        <td><span ><font size="12">  {$details.suboptimal}  szt </font> </span>  </td>
        <td><span ><font size="12"> -5 zł  </font> </span> </td>
        {if $details.suboptimalWartosc[0] == "-"}
            <td> <span><font color="red" size="12"> {$details.suboptimalWartosc} zł </font> </span> </td>
        {else}
            <td> <span ><font size="12">{$details.suboptimalWartosc} zł </font> </span> </td>
        {/if}
    </tr>

    <tr>
        <td bgcolor="#e6e6e6"><span ><font size="12"> Kara wagi krytyczne +- </font> </span>  </td>
        <td><span ><font size="12"> {$details.badweight} szt </font> </span> </td>
        <td><span ><font size="12"> -10 zł  </font> </span> </td>
        {if $details.badweightWartosc[0] == "-"}
            <td> <span><font color="red" size="12"> {$details.badweightWartosc} zł </font> </span></td>
        {else}
            <td><span ><font size="12"> {$details.badweightWartosc} zł </font> </span> </td>
        {/if}
    </tr>
    <tr>
        <td bgcolor="#e6e6e6"><span ><font size="12"> Kara za brak przeważeń  </font> </span> </td>
        <td><span ><font size="12"> {$details.pelnowartosciowe} szt </font> </span> </td>
        <td><span ><font size="12"> -10 zł </font> </span> </td>
        {if $details.karaWagi[0] == "-"}
            <td><span><font color='red' size="12"> {$details.karaWagi} zł </font> </span> </td>
        {else}
            <td><span ><font size="12"> {$details.karaWagi} zł </font> </span> </td>
        {/if}
    </tr>

        {if $details.nf }
    <tr>
        <td  bgcolor="#e6e6e6"><span ><font size="12"> Pasze (NF) </font> </span> </td>
        <td ></td><td ></td>
        <td  > <span ><font size="12">{$details.nfPrice} zł </font> </span> </td>
    </tr>
    {else}
    <tr>
        <td   bgcolor="#e6e6e6"><span ><font size="12" > Pasze (Premia)  </font> </span> </td>
        <td ></td><td ></td>
        <td> <span ><font size="12" > {$details.nfPrice} zł  </font> </span> </td>
    </tr>
    {/if}
    <tr bgcolor="#d1d1d1">
        <td  >  <h3> Zysk do sztuki: </h3> </td>
        <td ></td><td ></td>
        <td  ><h3> {$details.sumaperone} zł/szt  </h3> </td>
    </tr>
    <tr bgcolor="#d1d1d1">
        <td  > <h3> Do wypłaty: </h3> </td>
        <td ></td><td ></td>
        <td  ><h3> {$details.suma} zł </h3> </td>
    </tr>


    </table>

