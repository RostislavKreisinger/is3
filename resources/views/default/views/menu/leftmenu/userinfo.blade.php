@if(isset($project))
    <li class="sidebar-search">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                UID
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                <strong>
                    <a href="{{ url("/user/{$project->user_id}") }}"> {{ $project->user_id }}</a>
                </strong>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                P_ID
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                <strong>
                    <a href="{{ url("/project/{$project->id}") }}">{{ $project->id }}</a>
                </strong>
            </div>
        </div>
    </li>
@endif