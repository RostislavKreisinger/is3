@extends('layouts.app')

@section('page-title')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Import support</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default">
                <a class="panel-body text-center show" href="{{url('/homepage/importv2')}}">Import V2</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <a class="panel-body text-center show" href="{{url('/homepage/import-flow')}}">Import Flow</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <a class="panel-body text-center show" href="{{url('/homepage/import-flow-stats')}}">Import Flow Stats</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <a class="panel-body text-center show" href="{{url('/order-alert')}}">Order Alert</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <a class="panel-body text-center show" href="{{url('/homepage/if-control-pool')}}">IF Control Pool</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <a class="panel-body text-center show" href="{{url('/homepage/resources')}}">Resources</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <a class="panel-body text-center show" href="{{url('/homepage/large-flow')}}">Large flows</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <a class="panel-body text-center show" href="{{url('/homepage/broken-flow')}}">Large flows</a>
            </div>
        </div>
    </div>
@endsection

@section('left-menu')
    @include('default.views.menu.leftmenu')
@endsection