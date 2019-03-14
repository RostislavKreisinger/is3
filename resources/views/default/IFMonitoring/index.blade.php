<style>
    .flow-graph{
        width: 100%;
    }
    .graph-row{
        position: relative;
        width: 100%;
    }

    .graph-row .average{
        border: darkorange solid 1px;
        border-right: darkorange solid 5px;
    }
    .flow-row{
        position: relative;
        border: black solid 1px;
    }
    .flow-row .flow-segment{
        float: left;
        height: 20px;
        width: 20px;
    }

    .flow-row .import-step{
        background-color: #f6d300;
    }

    .flow-row .etl-step{
        background-color: #00b40c;
    }

    .flow-row .calc-step{
        background-color: #A60D0D;
    }

    .flow-row .output-step{
        background-color: #3E89E4;
    }

    .flow-row  .flow-time-to-start{
        opacity: 0.3;
    }

    .flow-row .flow-runtime{

    }

    .flow-unique{
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        text-align: right;
    }
    .cleaner{
        clear: left;
    }

</style>

<h1>IF Monitoring</h1>

<div class="flow-graph">
@foreach ($graph as $row)
    <div class="graph-row">
        <div class="flow-unique">{{ $row->getUnique() }} - {{ $row->getFlowRuntime() }}</div>
        <div class="flow-row {{ $row->getUnique() }}" style="width: {{$row->getFlowRuntimePercent()}}%;">

            <div class="flow-segment flow-time-to-start import-step" style="width: {{$row->getImportTimeToRunPercent()}}%;"></div>
            <div class="flow-segment flow-runtime import-step" style="width: {{$row->getImportStepRuntimePercent()}}%;"></div>
            <div class="flow-segment flow-time-to-start etl-step" style="width: {{$row->getEtlTimeToRunPercent()}}%;"></div>
            <div class="flow-segment flow-runtime etl-step" style="width: {{$row->getEtlStepRuntimePercent()}}%;"></div>
            <div class="flow-segment flow-time-to-start calc-step" style="width: {{$row->getCalcTimeToRunPercent()}}%;"></div>
            <div class="flow-segment flow-runtime calc-step" style="width: {{$row->getCalcStepRuntimePercent()}}%;"></div>
            <div class="flow-segment flow-time-to-start output-step" style="width: {{$row->getOutputTimeToRunPercent()}}%;"></div>
            <div class="flow-segment flow-runtime output-step" style="width: {{$row->getOutputStepRuntimePercent()}}%;"></div>

            <div class="cleaner"></div>
        </div>
        <div class="cleaner"></div>
    </div>

@endforeach
</div>