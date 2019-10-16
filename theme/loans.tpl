
<div id='createMsg' class='customButton' style='float:left;margin:10px;'>
    <a {$newLoan} > <div> Dodaj pożyczkę </div> 
    <div > </div> </a>
</div>
<br>
    <ul class="responsive-table">
        <li class="table-header">
            <div class="col col-3">Termin spłaty</div>
            <div class="col col-3">Kwota pożyczona</div>
            <div class="col col-3">Pozostało do spłaty</div>
            <div class="col col-3">Status</div>
            <div class="col col-3"></div>
        </li>
        {foreach from=$loans item=loan key=key name=name}
            <li class="table-row">
                <div class="col-3" data-label="">{$loan.payment_deadline}</div>
                <div class="col-3" data-label=""><b>{$loan.value} </b></div>
                <div class="col-3" data-label=""><b>{$loan.remained} zł </b></div>
                <div class="col-3" data-label="">{$loan.status}</div>
                <div class="col-3" data-label="">
                    <a {$loan.newChild} ><img {$add} height='25' width='25' /> </a> <span class='expand' id='loan_{$loan.id}'>  <img {$arrowDown} height='25' width='25' />  </span>
                </div>
                <div class="break loan_{$loan.id}" style='margin-bottom:25px;'></div>
                <div class="col-3 loan_{$loan.id}" style='display:none;'  data-label=""><b>Data spłaty</b></div>
                <div class="col-3 loan_{$loan.id}" style='display:none;' data-label=""><b>Kwota</b></div>
                <div class="col-3 loan_{$loan.id}"  style='display:none;' data-label=""><b>Nr raty</b></div>
                <div class="col-3 loan_{$loan.id}" style='display:none;' data-label=""><b>Status</b></div>
                <div class="col-3 loan_{$loan.id}" style='display:none;' data-label="">  </div>
                <div class="break loan_{$loan.id}" style='margin-bottom:25px;'></div>
                {foreach from=$loan.parts item=part key=key name=name}
                    <div class="col-3 loan_{$loan.id} hidden" data-label="">{$part.payment_deadline}</div>
                    <div class="col-3 loan_{$loan.id} hidden" data-label="">{$part.value}</div>
                    <div class="col-3 loan_{$loan.id} hidden" data-label="">Rata {$smarty.foreach.name.iteration} </div>
                    <div class="col-3 loan_{$loan.id} hidden" data-label="">{$part.status}</div>
                    <div class="col-3 loan_{$loan.id} hidden" data-label=""> {$part.check} {$part.edit} {$part.del}  </div>
                {/foreach}
            </li>
        {/foreach}
    </ul>
 