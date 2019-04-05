<h4>[{{ $eshopType->id }}] {{ $eshopType->name }}</h4>
<div class="table-responsive">
    <table class="table table-hover">
        <tbody>
        @foreach(get_object_vars($resourceDetail) as $key => $value)
            @if(!empty($value))
                <tr>
                    <th>{{ $key }}</th>
                    <td>{{ $value }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>