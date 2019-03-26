@isset($project)
    <div class="row">
        <div class="col-sm-4">
            UID
        </div>
        <div class="col-sm-8">
            <strong>
                <a href="{{ url("/user/{$project->user_id}") }}">{{ $project->user_id }}</a>
            </strong>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            PID
        </div>
        <div class="col-sm-8">
            <strong>
                <a href="{{ url("/project/{$project->id}") }}">{{ $project->id }}</a>
            </strong>
        </div>
    </div>
@endisset