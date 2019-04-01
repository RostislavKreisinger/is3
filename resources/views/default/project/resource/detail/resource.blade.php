@extends('layouts.app')

@section('page-title')
    <div class="row">
        <div class="col-sm-6">
            <h2 class="page-header">[{{ $resourceSetting->resource_id }}] {{ $resourceSetting->resourceName->name }}</h2>
        </div>
        <div class="col-sm-6" id="message-box">

        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-6">
            {view 'default.project.resource.template.button-panel'}
            <div class="table-responsive">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <td>Created at</td>
                        <td>
                            <span>{{ $resourceDetail->created_at }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Last import</td>
                        <td>
                            <span>{{ $resourceDetail->last_import_at }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Last successful import</td>
                        <td>
                            <span>{{ $resourceDetail->last_successful_import_at }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Next check date</td>
                        <td>
                            <span>{{ $resourceSetting->next_check_date }}</span>
                        </td>
                    </tr>
                    @if($resourceSetting->active === 3)
                        <tr class="alert-danger">
                            <td>Active</td>
                            <td>
                                <span>ERROR</span>
                            </td>
                        </tr>
                    @elseif($resourceSetting->active > 4)
                        <tr class="alert-warning">
                            <td>Active</td>
                            <td>
                                <span>DISCONNECTED</span>
                            </td>
                        </tr>
                    @else
                        <tr class="alert-info">
                            <td>Active</td>
                            <td>
                                <span>{{ $resourceSetting->active }}</span>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

    <div class="col-sm-6 col-md-6">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#detail" data-toggle="tab" aria-expanded="true">Connection detail</a>
            </li>
            <li>
                <a href="#errors" data-toggle="tab" aria-expanded="true">Errors</a>
            </li>
            <li>
                <a href="#export" data-toggle="tab" aria-expanded="true">Export</a>
            </li>
            <li>
                <a href="#flow-generator" data-toggle="tab" aria-expanded="true">Flow Generator</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active in" id="detail">
                {if Monkey\View\ViewFinder::existView("default.project.resource.detail.eshop.$eshopType->code")}
                    {view "default.project.resource.detail.eshop.$eshopType->code"}
                {else}
                    {view "default.project.resource.detail.eshop.default"}
                {/if}
            </div>
            <div class="tab-pane in" id="errors">
                {view 'default.project.resource.template.error-panel'}
            </div>
            <div class="tab-pane" id="export">
                <textarea id="rsexport" title="SQL of this resource setting" readonly class="form-control"
                          style="resize: none; user-select: all;" rows="5"
                          onclick="$(this).select()">{n $rsexport }</textarea>
            </div>
            <div class="tab-pane" id="flow-generator">
                {if $user->can('project.resource.button.repair.generate')}
                    <form method="post" class="well form-inline" id="flow-generator-form">
                        {{ csrf_field() }}
                        <label for="flow-generator-date-from" title="Date from">
                            <input type="date" name="date-from" id="flow-generator-date-from" class="form-control">
                        </label>
                        <label for="flow-generator-date-to" title="Date to">
                            <input type="date" name="date-to" id="flow-generator-date-to" class="form-control">
                        </label>
                        <label for="flow-generator-split" title="Split range by selected amount of days">
                            <select name="split" id="flow-generator-split" class="form-control">
                                <option>1</option>
                                <option>2</option>
                                <option>7</option>
                                <option>14</option>
                                <option>30</option>
                            </select>
                        </label>
                        <input type="submit" value="Generate" class="btn btn-default">
                    </form>
                {else}
                    <div class="col-lg-12 alert-warning">
                        You have insufficient permissions to generate flows!
                    </div>
                {/if}
            </div>
        </div>
    </div>

    <div class="row">
        {ifset $eshopType}
            {if $eshopType->import_version == 2}
                {view 'default.project.resource.template.pools-panel'}
            {/if}
        {/ifset}
    </div>
</div>

    @if(Auth::user()->can('project.resource.import_flow.list'))
        @if($eshopType->import_version == 3)
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <h4>Import Flow</h4>
                    @include('default.project.resource.template.import-flow-statuses-panel')
                </div>
            </div>
        @endif
    @endif



    <script>
    window.onbeforeunload = function(){
        $.ajax({
            url: '{action Button\Resource\Other\ShiftNextCheckDateButtonController::class, ['project_id' => $resource->getProject_id(), 'resource_id' => $resource->id, 'next_check_date' => 'now' ]}'
        });
    };
    $(function () {
        var currentLocation = location.href;

        if (!currentLocation.endsWith('/')) {
            currentLocation += '/';
        }

        var dateTo = new Date();
        var dateFrom = new Date();
        dateFrom.setFullYear(dateFrom.getFullYear() - 2);
        $('#flow-generator-date-from').val(dateFrom.toISOString().split('T')[0]);
        $('#flow-generator-date-to').val(dateTo.toISOString().split('T')[0]);
        $('#flow-generator-split').val({$historyInterval});

        $('#flow-generator-form').attr('action', currentLocation + 'pool/generate-flows')
            .submit(function (event) {
                event.preventDefault();

                $.post({
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response['message']) {
                            $('#message-box').html($('<div>')
                                .addClass('col-lg-12')
                                .addClass('alert-' + response['type'])
                                .text(response['message']));
                        }
                    },
                    url: currentLocation + 'pool/generate-flows'
                });
            });
    });
</script>
@endsection

@section('left-menu')
    @include('default.views.menu.leftmenu')
@endsection