<div id='analis'>

    <table id='data-table'>
        <thead>
            <tr>
                <th   id='name' class='sort'>
                    Imie nazwisko    
                </th>
                <th  id='dateStart' class='sort'>
                    Data wstawienia 
                </th>
                <th   id='nrKey' class='sort'>
                    Nr kolczyka
                </th>
                <th  id='deliveredAmount' class='sort'>
                    Ilość dostarczona 
                </th>
                <th   id='recivedAmount' class='sort'>
                    Ilość zdana
                </th>
                <th   id='weightSmallPigs' class='sort'>
                    Waga warch. Duńska 
                </th>
                <th  id='year' class='sort'>
                    Rok wstawienia 
                </th>
                <th  id='falls' class='sort'>
                    Upadki 
                </th>
                <th  id='weightBigPig' class='sort'>
                    Waga tucznika 
                    </th>
                <th  id='feedDeliverer' class='sort'>
                    Firma paszowa 
                </th>
                <th  id='smallPigEats' class='sort'>
                    Zużycie od wagi duń. 
                </th>
                <th   id='brutto' class='sort'>
                    Wydaj. od Brutto 
                </th>
                <th  id='dateRecived' class='sort'>
                    Data odbioru 
                </th>
            </tr>
        </thead>


        {foreach from=$records item=record key=key name=name}
            <tr>
                <td>{$record.farmer}</td>
                <td>{$record.data_start}</td>
                <td>{$record.kolczyk}</td>
                <td>{$record.delivered}</td>
                <td>{$record.recived}</td>
                <td>{$record.weightStart}</td>
                <td>{$record.data_start|date_format:'%Y'}</td>
                <td>{$record.falls}</td>
                <td>{$record.pigWeight}</td>
                <td>{$record.feedCompany}</td>
                <td>{$record.feedConsum}</td>
                <td>{$record.brutto}</td>
                <td>{$record.dateRecived}</td>
            </tr>
        {/foreach}
    </table>
</div