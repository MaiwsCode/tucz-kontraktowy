<div id='analis'>

    <table>
        <tr>
            <th  id='name' class='sort'>
                Imie nazwisko <br>      
                <span>   
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' /> 
                </span> 
            </th>
            <th id='dateStart' class='sort'>
                Data wstawienia <br>
                <span id='dateStart' class='sort'>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
            </th>
            <th  id='nrKey' class='sort'>
                Nr kolczyka <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
            </th>
            <th id='status' class='sort'>
                Status <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
            </th>
            <th id='deliveredAmount' class='sort'>
                Ilość dostarczona <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
            </th>
            <th  id='recivedAmount' class='sort'>
                Ilość zdana <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
            </th>
            <th  id='weightSmallPigs' class='sort'>
                Waga warch. Duńska <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
            </th>
            <th id='year' class='sort'>
                Rok wstawienia <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
            </th>
            <th id='falls' class='sort'>
                Upadki <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
            </th>
            <th id='weightBigPig' class='sort'>
                Waga tucznika <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                </span>
                </th>
            <th id='feedDeliverer' class='sort'>
                Firma paszowa <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' /> 
                </span>
            </th>
            <th id='smallPigEats' class='sort'>
                Zużycie od wagi duń. <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' /> 
                </span>
            </th>
            <th  id='brutto' class='sort'>
                Wydaj. od Brutto <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' /> 
                </span>
            </th>
            <th id='dateRecived' class='sort'>
                Data odbioru <br>
                <span>
                    <img src='modules/tuczkontraktowy/theme/sort.png' height='20' width='20' />
                    </span>
            </th>
        </tr>



        {foreach from=$records item=record key=key name=name}
            <tr>
                <td>  {$record.farmer}</td>
                <td>  {$record.data_start}  </td>
                <td>  {$record.kolczyk}  </td>
                <td>  {$record.status} </td>
                <td>  {$record.delivered}  </td>
                <td>  {$record.recived} </td>
                <td>  {$record.weightStart}  </td>
                <td>  {$record.data_start|date_format:'%Y'}  </td>
                <td>  {$record.falls}  </td>
                <td>  {$record.pigWeight}  </td>
                <td>  {$record.feedCompany}  </td>
                <td>  {$record.feedConsum}  </td>
                <td>  {$record.brutto}  </td>
                <td>  {$record.dateRecived} </td>
            </tr>
        {/foreach}
    </table>
</div