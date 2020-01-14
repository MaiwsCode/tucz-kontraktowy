 <div id='analis'>
 <h2> FAKTURY ZAKUPOWE </h2>
    <table class='data-table'>
        <thead>
            <tr>
                <th id='nr' class='sort'>
                    Numer Faktury    
                </th>
                <th  id='company' class='sort'> Dostawca </th>
                <th id='asda'> </th> 
                <th> Suma </th> 
            </tr>
        </thead>
        <tbody>
            <col width="10%">
            <col width="10%">
            <col width="70%">
            <col width="10%">
            <!-- FOREACH  -->
            {foreach from=$faktury_zakupowe item=faktura key=key name=name}          
                <tr>  
                    <td>{$faktura.fv}</td>
                    <td>{$faktura.company}</td>
                    <td>
                        <table class='data-table' style='width:100%;border-collapse: collapse;'> 
                            <thead>
                                <th>
                                    Typ faktury
                                </th>
                                <th   id='type' class='sort'>
                                    Kwoty
                                </th>
                            </thead>
                            <tbody>
                            {assign var=arr value=$faktura.childs}
                            {foreach from=$arr item=fv key=key name=name}
                                <tr>
                                    <td>{$fv.typ_faktury}</td>
                                    <td>{$fv.price}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </td> 
                    <td>{$faktura.sumPrice|number_format:2:",":" "}</td>
                </tr>
        {/foreach}
    <!-- ENDFOREACH -->
        </tbody>
    </table>
           <div style='width:100%;text-align:right;margin-bottom:3%;border-bottom:2px solid black;'> 
             <h3 style='margin-right:5%;'>{$zakupyPrice} zł</h3></div>

     <h2> FAKTURY TRANSPORTOWE </h2>
    <table class='data-table'>
        <thead>
            <tr>
                <th id='nr' class='sort'>
                    Numer Faktury    
                </th>
                <th  id='company' class='sort'> Dostawca </th>
                <th id='asda'> </th> 
                <th> Suma </th> 
            </tr>
        </thead>
        <tbody>
            <col width="10%">
            <col width="10%">
            <col width="70%">
            <col width="10%">
            <!-- FOREACH  -->
            {foreach from=$faktury_transportowe item=faktura key=key name=name}          
                <tr>  
                    <td>{$faktura.fv}</td>
                    <td>{$faktura.company}</td>
                    <td>
                        <table class='data-table' style='width:100%;border-collapse: collapse;'> 
                            <thead>
                                <th>
                                    Typ faktury
                                </th>
                                <th   id='type' class='sort'>
                                    Kwoty
                                </th>
                            </thead>
                            <tbody>
                            {assign var=arr value=$faktura.childs}
                            {foreach from=$arr item=fv key=key name=name}
                                <tr>
                                    <td>{$fv.typ_faktury}</td>
                                    <td>{$fv.price}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </td> 
                    <td>{$faktura.sumPrice|number_format:2:",":" "}</td>
                </tr>
        {/foreach}
    <!-- ENDFOREACH -->
        </tbody>
    </table>
    <div style='width:100%;text-align:right;margin-bottom:3%;border-bottom:2px solid black;'> 
        <h3 style='margin-right:5%;'>{$transportyPrice} zł</h3></div>


     <h2> FAKTURY SPRZEDAŻOWE </h2>
    <table class='data-table'>
        <thead>
            <tr>
                <th id='nr' class='sort'>
                    Numer Faktury    
                </th>
                <th  id='company' class='sort'> Dostawca </th>
                <th id='asda'> </th> 
                <th> Suma </th> 
            </tr>
        </thead>
        <tbody>
            <col width="10%">
            <col width="10%">
            <col width="70%">
            <col width="10%">
            <!-- FOREACH  -->
            {foreach from=$faktury_sprzedazowe item=faktura key=key name=name}          
                <tr>  
                    <td>{$faktura.fv}</td>
                    <td>{$faktura.company}</td>
                    <td>
                        <table class='data-table' style='width:100%;border-collapse: collapse;'> 
                            <thead>
                                <th>
                                    Typ faktury
                                </th>
                                <th   id='type' class='sort'>
                                    Kwoty
                                </th>
                            </thead>
                            <tbody>
                            {assign var=arr value=$faktura.childs}
                            {foreach from=$arr item=fv key=key name=name}
                                <tr>
                                    <td>{$fv.typ_faktury}</td>
                                    <td>{$fv.price}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </td> 
                    <td>{$faktura.sumPrice|number_format:2:",":" "}</td>
                </tr>
        {/foreach}
    <!-- ENDFOREACH -->
        </tbody>
    </table>

    <div style='width:100%;text-align:right;margin-bottom:3%;border-bottom:2px solid black;'> 
        <h3 style='margin-right:5%;'>{$sprzedazPrice} zł</h3></div>


    <h2> FAKTURY INNE </h2>
    <table class='data-table'>
        <thead>
            <tr>
                <th id='nr' class='sort'>
                    Numer Faktury    
                </th>
                <th  id='company' class='sort'> Dostawca </th>
                <th id='asda'> </th> 
                <th> Suma </th> 
            </tr>
        </thead>
        <tbody>
            <col width="10%">
            <col width="10%">
            <col width="70%">
            <col width="10%">
            <!-- FOREACH  -->
            {foreach from=$faktury_inne item=faktura key=key name=name}          
                <tr>  
                    <td>{$faktura.fv}</td>
                    <td>{$faktura.company}</td>
                    <td>
                        <table class='data-table' style='width:100%;border-collapse: collapse;'> 
                            <thead>
                                <th>
                                    Typ faktury
                                </th>
                                <th   id='type' class='sort'>
                                    Kwoty
                                </th>
                            </thead>
                            <tbody>
                            {assign var=arr value=$faktura.childs}
                            {foreach from=$arr item=fv key=key name=name}
                                <tr>
                                    <td>{$fv.typ_faktury}</td>
                                    <td>{$fv.price}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </td> 
                    <td>{$faktura.sumPrice|number_format:2:",":" "}</td>
                </tr>
        {/foreach}
    <!-- ENDFOREACH -->
        </tbody>
    </table>
    <div style='width:100%;text-align:right;margin-bottom:3%;border-bottom:2px solid black;'> 
        <h3 style='margin-right:5%;'>{$innePrice} zł</h3></div>
</div>