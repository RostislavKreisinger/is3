@if (Auth::guest())
    <li><a href="{{ url('auth/login') }}">Login</a></li>
@else
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="{{ url('/admin') }}">
                    <i class="fa fa-gear fa-fw"></i> Admin
                </a>
            </li>
            <li>
                <a href="{{ url('/plugin/import/supervisor') }}">
                    <i class="fa fa-gear fa-fw"></i> Import supervisor
                </a>
            </li>
            <li>
                <a href="{{ url('/database/database-selector') }}">
                    <i class="fa fa-gear fa-fw"></i> Database select
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="{{ url('/project-list/eshops') }}">
                    <i class="fa fa-gear fa-fw"></i> Projects by eshop type
                </a>
            </li>
            <li>
                <a href="{{ url('/project-list/resources') }}">
                    <i class="fa fa-gear fa-fw"></i> Projects by resource
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="{{ url('auth/logout') }}">
                    <i class="fa fa-btn fa-sign-out"></i>Logout
                </a>
            </li>
        </ul>
    </li>
@endif