
{literal}
<style>
.header{
    font-size:18px;
}

.footer{
    font-size:15px;
}
.cell{
    text-align:center;
    font-size:14px;
}
#raport{
    border-collapse: collapse;
    width: 100%;
    background-color:#f0ebeb;
}

#raport td, #raport th {
  border: 1px solid #ddd;
  padding: 8px;
}
#raport tr:nth-child(even){background-color: #f2f2f2;}

#raport tr:hover {background-color: #ddd;}
</style>
{/literal}
<div style='float:left;margin-bottom:25px;'>

     <a class='button'  {$hrefMonthView}> Miesiąc </a> 
     <a class='button' {$hrefTypeView}> {$typeNameChange} </a>
     <a class='button' {$prevWeek}> << </a>
     <a class='button' {$currentWeek}> * </a>
     <a class='button' {$nextWeek}> >> </a>
</div>
<div style='clear:both;'></div>
<h2 style='margin-bottom:25px;'> {$typeName} </h2>
<table cellspacing='0' style='width:100%;font-size:14px;' id='raport'>
    <tr>
        <th class='header' colspan='2'>
            <h3>SUMA:</h3>
            <span style='font-size:13px;'> {$dosOdeb} / Zakładane </span>
        </th>
        {foreach from=$sums item=sum key=key name=name}
            <th class='footer'>
                {$sum.sum} / {$sum.zal}
            </th>
        {/foreach}
    </tr>
    <tr>
        <th class='header'> Rolnik</th>
        <th class='header'> Dzień tuczu</th>
        {foreach from=$weeks item=week key=key name=name}
            {if $week.current}
                <th class='header' style='color:red;'> Tydzień {$key} </th>
            {else}
                <th class='header'> Tydzień {$key} </th>
            {/if}
        {/foreach}
    </tr>
    {foreach from=$contracts item=contract key=key name=name}
        <tr>
            <td class='cell'>{$contract.farmer.name}</td>
            {if $contract.farmer.time == -1}
                <td class='cell'> Za 1 dzień</td>
            {elseif $contract.farmer.time < -1}
                <td class='cell'> Za   {math equation="x*-1" x=$contract.farmer.time}  dni </td>
            {else}
                <td class='cell'>{$contract.farmer.time}</td>
            {/if}
            {foreach from=$contract.weeks item=i key=k name=n}
                 <td class='cell'>{$i}</td>
            {/foreach}


        </tr>
    {/foreach}
</table>