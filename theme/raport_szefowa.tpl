<h1> Raport dla szefowej </h1>

<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
   
    <tr>
        <td> Zakup warchlaka </td>
        <td>  - {$details.kosztyWarchlaka} zł </td>
        <td> {$details.cenaZaWarchlaka} zł/szt  </td>
    </tr>
    <tr>
        <td>  Koszty transportu  </td>
        <td colspan='2' > -{$details.transportedPrice} zł </td>
    </tr>
    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>
    {if $details.nf}
    <tr>
        <td>  Pasza NF  </td>
        <td  colspan='2'> <span style='color:#96720f;'> -{$details.nfPrice} (Jest to wliczone w zysk rolinka) </span> </td>
    </tr>
    {else}
    <tr>
        <td>  Pasze </td>
        <td colspan='2' > - {$details.paszaPrice} </td>
    </tr>
    {/if}
    
    <tr>
        <td>  Sprzedaz tucznika </td>
        <td > {$details.tucznikPrice} </td>
        <td > {$details.tucznikWBC} </td>
    </tr>

    <tr>
        <td>  Koszty inne </td>
        <td colspan='2' > - {$details.kosztyInne} </td>
    </tr>
    <tr>
        <td>  Zysk rolnik </td>
        <td colspan='2' > -{$details.zyskRolnik} zł </td>
    </tr>

    <tr style='background:#F0F0F0;'> <td colspan='3'></td></tr>
    <tr>
        <td> Na sztukę  </td>
        <td colspan='2' class='status' > {$details.perOne} zł </td>
    </tr>
    <tr>

        <td> Suma  </td>
        {if $details.suma[0] == "-"}
            <td> <span style='color:red;'> {$details.suma} zł </span></td>
        {else}
            <td> {$details.suma} zł</td>
        {/if}
    </tr>
   
</table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">

