
{literal}
<style>
.header{
    font-size:18px;
}
.cell{
    text-align:center;
    font-size:14px;
}
</style>
{/literal}
<div style='float:left;margin-bottom:25px;'>

    <a class='button'  href="#"> Miesiąc </a> 
     <a class='button' href="#"> Odbiory </a>
</div>
<div style='clear:both;'></div>
<h2 style='margin-bottom:25px;'> DOSTAWY </h2>
<table cellspacing='0' style='width:100%;font-size:14px;'>
    <tr>
        <th class='header'> Rolnik</th>
        <th class='header'> Dzień tuczu</th>
        {foreach from=$weeks item=week key=key name=name}
            {if $week.current}
                {assign var=week1 value=$key-4}
                {assign var=week2 value=$key-3}
                {assign var=week3 value=$key-2}
                {assign var=week4 value=$key-1}
                {assign var=week5 value=$key}
                {assign var=week6 value=$key+1}
                {assign var=week7 value=$key+2}
                <th class='header' style='color:red;'> Tydzień {$key} </th>
            {else}
                <th class='header'> Tydzień {$key} </th>
            {/if}
        {/foreach}
    </tr>
    {foreach from=$contracts item=contract key=key name=name}
        <tr>
            <td class='cell'>{$contract.farmer.name}</td>
            <td class='cell'>{$contract.farmer.time}</td>
            <td class='cell'>{$contract.$week1}</td>
            <td class='cell'>{$contract.$week2}</td>
            <td class='cell'>{$contract.$week3}</td>
            <td class='cell'>{$contract.$week4}</td>
            <td class='cell'>{$contract.$week5}</td>
            <td class='cell'>{$contract.$week6}</td>
            <td class='cell'>{$contract.$week7}</td>
        </tr>
    {/foreach}
    <tr>


    </tr>

    <tr>
        <th class='header' colspan='2'>
            <h3>SUMA:</h3>
        </th>
        {foreach from=$sums item=sum key=key name=name}
            <th class='header'>
                {$sum}
            </th>
        {/foreach}
    </tr>
</table>