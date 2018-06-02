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
    <?php $sumProductsVariants = 0; ?>
    <?php $sumRevenue = []; ?>
    <?php $sumCountriesInOrders = 0; ?>

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
            <?php $sumProductsVariants += (int) $project->products; ?>
        </td>
        <td>
            @foreach ($project->revenue as $currency => $value)
                {{$currency}}: {{$value}} <br>
                <?php
                    if(!isset($sumRevenue[$currency])) {
                        $sumRevenue[$currency] = 0;
                    }
                    $sumRevenue[$currency] += (float) $value;
                ?>
            @endforeach
        </td>
        <td>
            {{$project->created_at}}
        </td>
        <td>
            {{$project->countriesInOrders }}
            <?php $sumCountriesInOrders += (int) $project->countriesInOrders; ?>
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="4">
            Rows: {{ number_format(count($projects),2,",",".") }}
        </td>
        <td>
            Sum: {{ number_format($sumOrders,2,",",".") }}
        </td>
        <td>
            Sum: {{ number_format($sumProducts,2,",",".") }}
        </td>
        <td>
            Sum: {{ number_format($sumProductsVariants,2,",",".") }}
        </td>
        <td>
            <?php
            foreach ($sumRevenue as $key => $value) {
                echo "{$key}: " . number_format($value, 2, ",", ".") . " <br>";
            }
            ?>
        </td>
        <td>
        </td>
        <td>
            Sum: {{ number_format($sumCountriesInOrders,2,",",".") }}
        </td>
    </tr>
</table>