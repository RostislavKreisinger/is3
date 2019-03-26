@include('default.views.menu.leftmenu.userinfo')
<ul class="nav nav-pills nav-stacked" id="side-menu">
    @foreach($menu as $menuItem)
        <li class="{{ $menuItem->getClass() }} {{ $menuItem->getOpened() ? 'active' : '' }}">
            <a href="{{ $menuItem->getLink() }}" title="{{ $menuItem->getTitle() }}"  data-toggle="tooltip" data-placement="right">
                <span>{{ $menuItem->getName() }}</span>
            </a>
            @if($menuItem->hasSubList())
                <ul class="nav nav-second-level collapse in">
                    @foreach($menuItem->getList() as $subMenuItem)
                        <li class="{{ $subMenuItem->getClass() }}">
                            <a {!! $subMenuItem->getLink() ? 'href="' . $subMenuItem->getLink() . '"' : '' !!} title="{{ $subMenuItem->getTitle() }}" data-toggle="tooltip" data-placement="right">
                                {{ $subMenuItem->getName() }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>