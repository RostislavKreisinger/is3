<script type="application/javascript">
    var tmp = "average";
    function mouseOver(unique) {
        document.getElementById(unique).style.display = "block";
        if(unique != tmp){
            document.getElementById(tmp).style.display = "none";
        }

    }

    function mouseOut(unique) {
        document.getElementById(unique).style.display = "none";
        if(unique != tmp) {
            document.getElementById(tmp).style.display = "block";
        }
    }

    function mouseClick(unique) {
        document.getElementById(unique).style.display = "block";

        if(unique != tmp) {
            document.getElementById(tmp).style.display = "none";
        }
        tmp = unique;
    }
</script>

<style>

.pie-cover{
    position: relative;
    display: none;
}

#average{
    display: block;
}

.pie{
    border-radius: 100%;
    border:1px solid var(--boc);
    height: calc(var(--size, 200) * 1px);
    position: relative;
    overflow: hidden;
    width: calc(var(--size, 200)* 1px);
    margin: 10px auto;
}
.pie__segment{
    --a: calc(var(--over50,0)* -100%);
    --b: calc((1 + var(--over50,0)) * 100%);
    --degrees: calc((var(--offset, 0) / 100) * 360);
    height: 100%;
    clip-path: polygon(var(--a) var(--a),var(--b) var(--a),var(--b) var(--b), var(--a) var(--b));
    position: absolute;
    transform: translate(0,-50%) rotate(90deg) rotate( calc(var(--offset) * 1deg ) );
    transform-origin: 50% 100%;
    width: 100%;
}

.pie__segment:before, .pie__segment:after {
    background: var(--bg, red);
    content: '';
    height: 100%;
    position: absolute;
    width: 100%;
}
.pie__segment:before{
    --degrees: calc((var(--value, 0)/ 100) * 360);
    transform: translate(0,100%) rotate(calc(var(--value) * 1deg));
    transform-origin: 50% 0;
    content: attr(data-label);
    font-size: small;
}
.pie__segment:after {
    opacity: var(--over50,0);
}
.pie-label{
    text-align: center;
}

.pie-legend{
    border: 1px solid black;
}
.pie-legend div{
    padding: 2px;
    background: var(--bcg);
}
.pie-legend div div {
    background: linear-gradient(to right, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0) 50%, rgba(0, 0, 0, 0.5) 100%);
}

.cylinder {
    position: relative;
    overflow: hidden;
    margin: calc(var(--margin) * -1px) auto 0;
    width: calc(var(--size, 0) * 1px);
    height: 65px;
    border-radius: calc(var(--size, 0) / 2 * 1px) / 25px;
    z-index: var(--z,9999);
    border-bottom: 1px solid black;
    border-left: 1px solid black;
    border-right: 1px solid black;
    background-color: var(--color);
}

.cylinder div{
    background: linear-gradient(to right, rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 50%,rgba(0,0,0,0.5) 100%);
    width: 100%;
    height: 100%;
}

.cylinder:before {
    position: absolute;
    left: 0;
    top: 0;
    width: calc(var(--size, 0) * 1px);
    height: 50px;
    border-radius: calc(var(--size, 0) / 2 * 1px) / 25px;
    content: '';
    border: 1px solid black;
    background-color: var(--color);
}

.cylinder:after {
    position: absolute;
    left: 0;
    bottom: 0;
    width: calc(var(--size, 0) * 1px);
    height: 50px;
    border-radius: calc(var(--size, 0) / 2 * 1px) / 25px;
    content: '';
}

.average{
    border:1px solid orange;
}

.cylinder:hover, .cylinder:hover:before{
    background-color: red;
}

</style>







<div style="float: left;width: 532px;padding: 50px;">

    @php
    $oldSize = 0;
    @endphp
@foreach ($graph as $row)
    @php

        $margin = ($oldSize/($row->getGraphSize()/100))>98 ? 55 : 45;
        $oldSize = $row->getGraphSize();

        $z = count($graph) - $loop->index;
        $avg = ($row->isAverage()?"average":"");
        $color = $colorSet[0][$row->getActualActivePart()];
        $color = ($row->isAverage()?"FFFC00":$color);
    @endphp

    <div class="cylinder {{$avg}}" style="--size: {{$row->getGraphSize()}};--z: {{$z}};--margin: {{$margin}};--color: {{$color}};"
         onmouseover="mouseOver('{{$row->getUnique()}}')"
         onmouseout="mouseOut('{{$row->getUnique()}}')"
         onclick="mouseClick('{{$row->getUnique()}}')">


        <div>

        </div>
    </div>

@endforeach
</div>







<div style="width: 1000px;position: fixed;left:600px;">
@foreach ($graph as $row)
    @php
    $tmp = $colorSet[$loop->index % 2];
    $boc = ($row->isAverage()?"orange":"black");
    $flowName = ($row->isAverage()?"Average":$row->getUnique());
    @endphp
    <div class="pie-cover" id="{{$row->getUnique()}}">
        <div class="pie-label">PID: {{ $row->getProjectId() }} "{{$flowName}}": {{$row->formatSeconds($row->getFlowRuntime())}}</div>

        <div class="pie" style="--size: 300;--boc: {{$boc}};">


            <div class="pie__segment" data-label="{{$row->formatSeconds($row->getImportTimeToRun())}}" style="--offset: {{$row->getImportTimeToStartOffset()}}; --value: {{$row->getImportTimeToStartValue()}}; --bg: {{$tmp[0]}};--over50: {{$row->isBiggerThan180($row->getImportTimeToStartValue())}};"></div>
            <div class="pie__segment" data-label="{{$row->formatSeconds($row->getImportStepRuntime())}}" style="--offset: {{$row->getImportRuntimeOffset()}}; --value: {{$row->getImportRuntimeValue()}}; --bg: {{$tmp[1]}};--over50: {{$row->isBiggerThan180($row->getImportRuntimeValue())}};"></div>

            <div class="pie__segment" data-label="{{$row->formatSeconds($row->getEtlTimeToRun())}}" style="--offset: {{$row->getEtlTimeToStartOffset()}}; --value: {{$row->getEtlTimeToStartValue()}}; --bg: {{$tmp[2]}};--over50: {{$row->isBiggerThan180($row->getEtlTimeToStartValue())}};"></div>
            <div class="pie__segment" data-label="{{$row->formatSeconds($row->getEtlStepRuntime())}}" style="--offset: {{$row->getEtlRuntimeOffset()}}; --value: {{$row->getEtlRuntimeValue()}}; --bg: {{$tmp[3]}};--over50: {{$row->isBiggerThan180($row->getEtlRuntimeValue())}};"></div>

            <div class="pie__segment" data-label="{{$row->formatSeconds($row->getCalcTimeToRun())}}" style="--offset: {{$row->getCalcTimeToStartOffset()}}; --value: {{$row->getCalcTimeToStartValue()}}; --bg: {{$tmp[4]}};--over50: {{$row->isBiggerThan180($row->getCalcTimeToStartValue())}};"></div>
            <div class="pie__segment" data-label="{{$row->formatSeconds($row->getCalcStepRuntime())}}" style="--offset: {{$row->getCalcRuntimeOffset()}}; --value: {{$row->getCalcRuntimeValue()}}; --bg: {{$tmp[5]}};--over50: {{$row->isBiggerThan180($row->getCalcRuntimeValue())}};"></div>

            <div class="pie__segment" data-label="{{$row->formatSeconds($row->getOutputTimeToRun())}}" style="--offset: {{$row->getOutputTimeToStartOffset()}}; --value: {{$row->getOutputTimeToStartValue()}}; --bg: {{$tmp[6]}};--over50: {{$row->isBiggerThan180($row->getOutputTimeToStartValue())}};"></div>
            <div class="pie__segment" data-label="{{$row->formatSeconds($row->getOutputStepRuntime())}}" style="--offset: {{$row->getOutputRuntimeOffset()}}; --value: {{$row->getOutputRuntimeValue()}}; --bg: {{$tmp[7]}};--over50: {{$row->isBiggerThan180($row->getOutputRuntimeValue())}};"></div>
        </div>
    </div>
@endforeach

    <div class="pie-legend">
        <div style="--bcg:#FFFC00;"><div>Average: {{$averageRow->formatSeconds($averageRow->getFlowRuntime())}}</div></div>
        <div style="--bcg:{{$colorSet[0][0]}};"><div>Import Time to Start: {{$averageRow->formatSeconds($averageRow->getImportTimeToRun())}}</div></div>
        <div style="--bcg:{{$colorSet[0][1]}};"><div>Import run time: {{$averageRow->formatSeconds($averageRow->getImportStepRuntime())}}</div></div>
        <div style="--bcg:{{$colorSet[0][2]}};"><div>Etl Time to Start: {{$averageRow->formatSeconds($averageRow->getEtlTimeToRun())}}</div></div>
        <div style="--bcg:{{$colorSet[0][3]}};"><div>Etl run time: {{$averageRow->formatSeconds($averageRow->getEtlStepRuntime())}}</div></div>
        <div style="--bcg:{{$colorSet[0][4]}};"><div>Calc Time to Start: {{$averageRow->formatSeconds($averageRow->getCalcTimeToRun())}}</div></div>
        <div style="--bcg:{{$colorSet[0][5]}};"><div>Calc run time: {{$averageRow->formatSeconds($averageRow->getCalcStepRuntime())}}</div></div>
        <div style="--bcg:{{$colorSet[0][6]}};"><div>Output Time to Start: {{$averageRow->formatSeconds($averageRow->getOutputTimeToRun())}}</div></div>
        <div style="--bcg:{{$colorSet[0][7]}};"><div>Output run time: {{$averageRow->formatSeconds($averageRow->getOutputStepRuntime())}}</div></div>
        <div style="--bcg:white;"><div>Flow count: {{$flowsCount}}</div></div>
    </div>

    <div>
        <a href="https://import-support.monkeydata.cloud/homepage/import-flow-stats">Import flow stats</a>
    </div>


</div>






