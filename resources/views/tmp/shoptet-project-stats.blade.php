<style>
    table {
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #000000;
        padding: 2px 4px ;
    }

    .null {
        background-color: red;
        color: white;
    }

    .empty {
        background-color: rgba(158,158,158,0.18);
    }
</style>

<div>
    <form>
        <input type="date" value="{{$dateFrom}}" name="date_from" placeholder="YYYY-MM-DD">
        <input type="date" value="{{$dateTo}}" name="date_to" placeholder="YYYY-MM-DD">
        <input type="submit" name="form_submited" value="Submit">
    </form>
</div>





@if(isset($projects))
<div>
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

    <?php

        function checkColumnClass($value) {
            $className = "";

            if ((is_array($value) && count($value) == 0) || is_null($value)) {
                $className = "null";
            } else if ($value === 0) {
                $className = "empty";
            }

            return $className;
        }

    ?>
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
        <td class="<?php echo checkColumnClass($project->orders); ?>">
            {{$project->orders}}
            <?php $sumOrders += (int) $project->orders; ?>
        </td>
        <td class="<?php echo checkColumnClass($project->products); ?>">
            {{$project->products}}
            <?php $sumProducts += (int) $project->products; ?>
        </td>
        <td class="<?php echo checkColumnClass($project->productsVariant); ?>">
            {{$project->productsVariant}}
            <?php $sumProductsVariants += (int) $project->productsVariant; ?>
        </td>
        <td class="<?php echo checkColumnClass($project->revenue); ?>">
            @foreach ($project->revenue as $currency => $value)
                {{$currency}}: {{ number_format($value, 2, ",", ".")}} <br>
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
        <td class="<?php echo checkColumnClass($project->countriesInOrders); ?>">
            {{$project->countriesInOrders }}
            <?php $sumCountriesInOrders += (int) $project->countriesInOrders; ?>
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="4">
            Rows: {{ number_format(count($projects),0,",",".") }}
        </td>
        <td>
            Sum: {{ number_format($sumOrders,0,",",".") }}
        </td>
        <td>
            Sum: {{ number_format($sumProducts,0,",",".") }}
        </td>
        <td>
            Sum: {{ number_format($sumProductsVariants,0,",",".") }}
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
            Sum: {{ number_format($sumCountriesInOrders,0,",",".") }}
        </td>
    </tr>
</table>
</div>
@endif