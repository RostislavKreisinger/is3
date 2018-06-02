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
        </td>
        <td>
            {{$project->products}}
        </td>
        <td>
            {{$project->productsVariant}}
        </td>
        <td>
            @foreach ($project->revenue as $currency => $value)
                {{$currency}}: {{$value}} <br>
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
</table>