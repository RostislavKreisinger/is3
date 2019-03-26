<ol class="breadcrumb">
    @foreach($breadcrumbs as $breadcrumb)
        @if($loop->last)
            <li class="active">{{ $breadcrumb->getBtf() }}</li>
        @else
            <li>
                <a href="{{ $breadcrumb->getUrl() }}">{{ $breadcrumb->getBtf() }}</a>
            </li>
        @endif
    @endforeach
</ol>