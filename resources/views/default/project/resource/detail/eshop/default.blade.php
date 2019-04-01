<h4>{{ $eshopType->name }} ({{ $eshopType->id }})</h4>
<div class="table-responsive">
    <table class="table table-hover">
        <tbody>
        @foreach($connectionDetail->getAttributes() as )
            <tr n:foreach="$connectionDetail as $key => $value">
                <th>{$key}</th>
                <td>{$value}</td>
            </tr>
        </tbody>
    </table>
</div>