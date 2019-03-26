@extends('layouts.app')

@section('page-title')
    <div class="row">
        <div class="col-sm-12">
            <h1 class="page-header">Project</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <div class="table-responsive">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <td>Project ID</td>
                        <td>{{ $project->id }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{ $project->name }}</td>
                    </tr>
                    <tr>
                        <td>Project type</td>
                        <td>{{ Tr::translate($project->projectType->btf_name) . " ({$project->projectType->id})" }}</td>
                    </tr>
                    <tr>
                        <td>Currency</td>
                        <td>{{ $project->currency->code }}</td>
                    </tr>

                    <tr>
                        <td>Timezone</td>
                        <td>{{ $project->timezone->name }}</td>
                    </tr>

                    <tr>
                        <td>Created at</td>
                        <td>{{ $project->created_at }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('left-menu')
    @include('default.views.menu.leftmenu')
@endsection