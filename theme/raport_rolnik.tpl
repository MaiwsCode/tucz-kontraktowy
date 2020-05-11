<h1> ROZLICZENIE TUCZU </h1>

<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
    <tr>
        <th style='font-size:16px;'> Imię i Nazwisko </th>
        <th style='font-size:16px;'> Data wstawienia </th>
        <th style='font-size:16px;'> Ilość wstawiona </th>
        <th style='font-size:16px;'> Kolczyk </th>
    </tr>
    <tr>
        <td> {$details.farmer_name} </td>
        <td> {$details.dateStart} </td>
        <td> {$details.zakladanaIlosc} </td>
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
        <td> Średni przyrost dobowy </td>
        <td> {$details.srPrzyrostDobowy} kg/dzień </td>
    </tr>
    <tr>
        <td> Koszt leczenia na sztukę pełnowartościową	</td>
        <td> {$details.inne} zł/szt </td>
    </tr>
    <tr>
        <td> Średnie zużycie paszy	</td>
        <td> {$details.srZuzyciePaszy} kg  </td>
    </tr>
</table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">

    <tr>
        <th colspan='' style='font-size:16px;'>Składnik cenowy</th>
        <th colspan='' style='font-size:16px;'>Sztuki</th>
        <th colspan='' style='font-size:16px;'>Cena jednostkowa</th>
        <th colspan='' style='font-size:16px;'>Wartość</th>
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
        {if $details.weterynariaWartosc[0] == "-"}
            <td> <span style='color:red;'> {$details.weterynariaWartosc} zł </span> </td>
        {else}
            <td> {$details.weterynariaWartosc} zł </td>
        {/if}
    </tr>
    <tr>
        <td> Upadki </td>
        <td> {$details.pelnowartosciowe} szt </td>
        <td> {$details.upadki}  % </td>
        {if $details.upadkiWartosc[0] == "-"}
            <td> <span style='color:red;'> {$details.upadkiWartosc} zł </span> </td>
        {else}
            <td> {$details.upadkiWartosc} zł </td>
        {/if}
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
        {if $details.suboptimalWartosc[0] == "-"}
            <td><span style='color:red;'> {$details.suboptimalWartosc} zł </span></td>
        {else}
            <td> {$details.suboptimalWartosc} zł</td>
        {/if}
    </tr>

    <tr>
        <td> Kara wagi krytyczne +- </td>
        <td> {$details.badweight} szt</td>
        <td> -10 zł</td>
        {if $details.badweightWartosc[0] == "-"}
            <td> <span style='color:red;'> {$details.badweightWartosc} zł </span></td>
        {else}
            <td> {$details.badweightWartosc} zł</td>
        {/if}
    </tr>
    <tr>
        <td> Kara za brak przeważeń  </td>
        <td> {$details.pelnowartosciowe} szt</td>
        <td> -10 zł</td>
        {if $details.karaWagi[0] == "-"}
            <td> <span style='color:red;'> {$details.karaWagi} zł </span></td>
        {else}
            <td> {$details.karaWagi} zł</td>
        {/if}
    </tr>
    {if $details.loans != '0,00'}
    <tr>
        <td colspan='3'> Pożyczki  </td>
        <td colspan="1"><span style='color:red;'> -{$details.loans} zł</span>   </td>
    </tr>
    {/if}
    {if $details.advances != '0,00'}
    <tr>
        <td colspan='3'>  Zaliczki  </td>
        <td colspan="1"><span style='color:red;'> -{$details.advances} zł </span>   </td>
    </tr>
    {/if}


        {if $details.nf }
    <tr>
        <td colspan='3'> Pasze (NF) </td>
        <td colspan='1'> {$details.nfPrice} zł </td>
    </tr>
    {else}
    <tr>
        <td colspan='3'> Pasze (Premia)  </td>
        <td colspan='1'> {$details.nfPrice} zł  </td>
    </tr>
    {/if}
     {if $details.szefowaInne != '0,00' }
    <tr>
        <td colspan='3'><h2>  Bonus </h2> </td>
        <td colspan='1'> <h2> {$details.szefowaInne} zł </h2> </td>
    </tr>
    {/if}
    <tr>
        <td colspan='3'> <h2> Zysk do sztuki: </h2> </td>
        <td colspan="1"><h2> {$details.sumaperone} zł/szt  </h2> </td>
    </tr>
    <tr>
        <td colspan='3'> <h2> Do wypłaty: </h2> </td>
        <td colspan="1"><h2> {$details.suma} zł </h2> </td>
    </tr>


    </table>
{if $advances}
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
<tr>
<th colspan='5'><h3>Zaliczki</h3></th>
</tr>
    <tr>
        <th>Opis</th>
        <th>Kwota</th>
        <th>Termin przekazania pieniędzy</th>
        <th>Uwagi</th>
        <th>Rozlicz </th>
    </tr>
    {foreach from=$advances item=advance key=key name=name}
        <tr>
            <td>{$advance.note}</td>
            <td>{$advance.value}</td>
            <td>{$advance.payment_date}</td>
            <td>{$advance.comments}</td>
            <td>
            {if $advance.href}
                <input style='width:15px;height:15px;' type='checkbox' {$advance.href} />
            {else}
                Rozliczono 
            {/if}
            
            </td>

        </tr>
    {/foreach}
    
</table>
{/if}
{if $loans}
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
<tr>
<th colspan='5'><h3>Pożyczki</h3></th>
</tr>
    <tr>
        <th>Opis</th>
        <th>Kwota</th>
        <th>Termin do spłaty</th>
        <th>Uwagi</th>
        <th>Rozlicz </th>
    </tr>
    {foreach from=$loans item=loan key=key name=name}
        <tr>
            <td> RATA {$loan.numberOf}  <br> {$loan.link}           </td>
            <td> {$loan.value}              </td>
            <td> {$loan.payment_deadline}   </td>
            <td> {$loan.comments}           </td>
            <td>
            {if $loan.href}
                <input style='width:15px;height:15px;' type='checkbox' {$loan.href} />
                {else}
                Rozliczono 
            {/if}
            </td>

        </tr>
    {/foreach}
    
</table>
{/if}