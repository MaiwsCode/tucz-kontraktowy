<div class="bootstrap-iso" style="font-size: 14px;">
    <div class="container" style="padding-left: 6px; padding-right: 6px;">
        <div class="row">
            <div class="col-12">
                <input type='hidden' value='{$tucz}' id='tucz' />
                <div class="hide" id="example">
                                  
                            <div class="col-2">
                               <p> Większe niż lub równe </p>
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control changeR" data-operator='gte' style="height:unset;" value="">
                            </div>
                            <div class="col-2">
                               <p> Mniejsze niż lub równe </p>
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control changeL" data-operator='lte' style="height:unset;" value="">
                            </div>
                            <div class="col-2">
                             <p > Przelicznik </p>
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control limit changeMultipler" data-operator='multipler' style="height:unset;" value="">
                            </div>
                    </div>
                <div class='items'>
                {foreach from=$limits item=limit key=key} 
                    <div class="form-row mb-1 line" id='F{$limit.0.id}'>
                        {foreach from=$limit item=l key=k}  
                            <div class="col-2">
                               <p > {$l.textOperator} </p>
                            </div>
                            <div class="col-2">
                                <input id='{$l.prefix}{$l.id}' type="text" class="form-control" data-operator='{$l.operator}' style='height:unset;' value='{$l.value}' />
                            </div>
                            {if $k == 1 || ( $limit|@count eq 1 && $k == 0)}
                                {if  $limit|@count eq 1 && $k == 0}
                                    <div class="col-2"> <p> </p> </div>
                                    <div class="col-2"></div>
                                {/if}
                                <div class="col-2">
                                     <p > Przelicznik </p>
                                </div>
                                <div class="col-2">
                                    <input id='M{$l.id}' type="text" class="form-control" style='height:unset;' data-operator='multipler'  value='{$l.multipler}' />
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                {/foreach}
               </div>
                <div class="form-row mb-1">
                    <div class='col-6'>
                        <input type='button' class='btn btn-primary addLimit float-left' value='Dodaj limit' />
                    </div>
                    <div class='col-6'>
                        <input type='button' class='btn btn-success saveLimits float-right' value='Zapisz' />
                    </div>
                </div>
        </div>

    </div>
</div>

