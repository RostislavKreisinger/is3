<style>
    table {
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #000000;
        padding: 2px 4px ;
    }
</style>
<table>
    <tr>
        <th>UID</th>
        <th>PID</th>
        <th>url</th>
        <th>email</th>
        <th>order count</th>
        <th>product count</th>
        <th>product variant count</th>
        <th>revenue</th>
        <th>created_at</th>
        <th>countries in orders</th>

    </tr>
    <?php $sumOrders = 0; ?>
    <?php $sumProducts = 0; ?>
    <?php $sumRevenue = []; ?>

    @foreach ($projects as $project)
    <tr>
        <td>
            <a href="https://app-support.monkeydata.com/crm/plugins/{{$project->user_id}}" target="_blank">{{$project->user_id}}</a>
        </td>
        <td>
            <a href="https://app-support.monkeydata.com/crm/plugins/{{$project->user_id}}" target="_blank">{{$project->id}}</a>
        </td>
        <td>
            <a href="{{$project->weburl}}" target="_blank">{{$project->weburl}}</a>
        </td>
        <td>
            {{$project->user_email}}
        </td>
        <td>
            {{$project->orders}}
            <?php $sumOrders += (int) $project->orders; ?>
        </td>
        <td>
            {{$project->products}}
            <?php $sumProducts += (int) $project->products; ?>
        </td>
        <td>
            {{$project->productsVariant}}
        </td>
        <td>
            @foreach ($project->revenue as $currency => $value)
                {{$currency}}: {{$value}} <br>
                <?php
                    if(!isset($sumRevenue[$currency])) {
                        $sumRevenue[$currency] = 0;
                    }
                    $sumRevenue[$currency] += (int) $value;
                ?>
            @endforeach
        </td>
        <td>
            {{$project->created_at}}
        </td>
        <td>
            {{$project->countriesInOrders }}
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="4">
            Rows: {{ count($projects) }}
        </td>
        <td>
            Sum: {{ $sumOrders }}
        </td>
        <td>
            Sum: {{ $sumProducts }}
        </td>
        <td>
            <?php
                foreach ($sumRevenue as $key => $value){
                    echo "Sum {$key}: {$value} <br>";
                }
            ?>
        </td>
        <td colspan="2">
        </td>
    </tr>
</table>