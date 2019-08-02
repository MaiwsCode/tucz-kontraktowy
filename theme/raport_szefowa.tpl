<h1> Raport dla szefowej </h1>

<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">
   
    <tr>
        <td> Zakup warchlaka </td>
        <td colspan='2' >  - {$details.kosztyWarchlaka} zł </td>
    </tr>
    <tr>
        <td>  Koszty transportu  </td>
        <td colspan='2' > -{$details.transportedPrice} zł </td>
    </tr>    
    <tr>
        <td>  Koszty inne </td>
        <td colspan='2' > - {$details.kosztyInne} </td>
    </tr>
    <tr>
        <td>  Zysk rolnik </td>
        <td colspan='2' > -{$details.zyskRolnik} zł </td>
    </tr>
    <tr>
        <td>  Sprzedaz tucznika </td>
        <td colspan='2'  > {$details.tucznikPrice} </td>
    </tr>
    <tr>
        <td> Na sztukę  </td>
        <td colspan='2' class='status' > {$details.perOne} zł </td>
    </tr>

    <tr>

        <td> <h2> Suma </h2>  </td>
        {if $details.suma[0] == "-"}
            <td colspan='2' > <h2> <span style='color:red;'> {$details.suma} zł </span> </h2></td>
        {else}
            <td colspan='2' <h2> {$details.suma} zł </h2></td>
        {/if}
    </tr>
   
</table>
<table class="ttable ttable-hover ttable-bordered" style="margin-top:15px;margin-bottom:15px;user-select: text;width:60%;">

