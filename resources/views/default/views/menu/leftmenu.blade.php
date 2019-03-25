<ul class="nav" id="side-menu">
    @include('default.views.menu.leftmenu.userinfo')
    @foreach($menu as $menuItem)
        <li class="{{ $menuItem->getOpened() ? 'active' : '' }} {{ $menuItem->getClass() }}">
            <a href="{{ $menuItem->getLink() }}" title="{{ $menuItem->getTitle() }}"  data-toggle="tooltip" data-placement="right">
                {{ $menuItem->getName() }}
                <span class="fa arrow"></span>
            </a>
            {{--<ul n:if="$menuItem->hasSubList()" class="nav nav-second-level collapse">--}}
            {{--<li n:foreach="$menuItem->getList() as $subMenuItem" class="{$subMenuItem->getClass()}">--}}
            {{--<a {if $subMenuItem->getLink()} href="{$subMenuItem->getLink()}" {/if} title="{$subMenuItem->getTitle()}" data-toggle="tooltip" data-placement="right">--}}
            {{--{$subMenuItem->getName()}--}}
            {{--</a>--}}
            {{--</li>--}}

            {{--</ul>--}}
        </li>
    @endforeach
</ul>