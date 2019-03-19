<style>

.pie-cover{
    position: relative;
    display: inline-block;
}

.pie{
    border-radius: 100%;
    border:1px solid var(--boc);
    height: calc(var(--size, 200) * 1px);
    position: relative;
    overflow: hidden;
    width: calc(var(--size, 200)* 1px);
    margin: 5px;
}
.pie__segment{
    --a: calc(var(--over50,0)* -100%);
    --b: calc((1 + var(--over50,0)) * 100%);
    --degrees: calc((var(--offset, 0) / 100) * 360);
    height: 100%;


    clip-path: polygon(var(--a) var(--a),var(--b) var(--a),var(--b) var(--b), var(--a) var(--b));
    -webkit-clip-path: polygon(var(--a) var(--a),var(--b) var(--a),var(--b) var(--b), var(--a) var(--b));

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



</style>

@foreach ($graph as $row)
    @php
    $tmp = $colorSet[$loop->index % 2];
    $boc = ($row->isAverage()?"orange":"black");
    $avg = ($row->isAverage()?"Average ":"");
    @endphp
    <div class="pie-cover">
        <div class="pie-label">{{$avg}}{{$row->getFlowRuntime()}}s</div>

        <div class="pie" style="--size: {{$row->getGraphSize()}};--boc: {{$boc}};">


            <div class="pie__segment" data-label="{{$row->getImportTimeToRun()}}" style="--offset: {{$row->getImportTimeToStartOffset()}}; --value: {{$row->getImportTimeToStartValue()}}; --bg: {{$tmp[0]}};--over50: {{$row->isBiggerThen180($row->getImportTimeToStartValue())}};"></div>
            <div class="pie__segment" data-label="{{$row->getImportStepRuntime()}}" style="--offset: {{$row->getImportRuntimeOffset()}}; --value: {{$row->getImportRuntimeValue()}}; --bg: {{$tmp[1]}};--over50: {{$row->isBiggerThen180($row->getImportRuntimeValue())}};"></div>

            <div class="pie__segment" data-label="{{$row->getEtlTimeToRun()}}" style="--offset: {{$row->getEtlTimeToStartOffset()}}; --value: {{$row->getEtlTimeToStartValue()}}; --bg: {{$tmp[2]}};--over50: {{$row->isBiggerThen180($row->getEtlTimeToStartValue())}};"></div>
            <div class="pie__segment" data-label="{{$row->getEtlStepRuntime()}}" style="--offset: {{$row->getEtlRuntimeOffset()}}; --value: {{$row->getEtlRuntimeValue()}}; --bg: {{$tmp[3]}};--over50: {{$row->isBiggerThen180($row->getEtlRuntimeValue())}};"></div>

            <div class="pie__segment" data-label="{{$row->getCalcTimeToRun()}}" style="--offset: {{$row->getCalcTimeToStartOffset()}}; --value: {{$row->getCalcTimeToStartValue()}}; --bg: {{$tmp[4]}};--over50: {{$row->isBiggerThen180($row->getCalcTimeToStartValue())}};"></div>
            <div class="pie__segment" data-label="{{$row->getCalcStepRuntime()}}" style="--offset: {{$row->getCalcRuntimeOffset()}}; --value: {{$row->getCalcRuntimeValue()}}; --bg: {{$tmp[5]}};--over50: {{$row->isBiggerThen180($row->getCalcRuntimeValue())}};"></div>

            <div class="pie__segment" data-label="{{$row->getOutputTimeToRun()}}" style="--offset: {{$row->getOutputTimeToStartOffset()}}; --value: {{$row->getOutputTimeToStartValue()}}; --bg: {{$tmp[6]}};--over50: {{$row->isBiggerThen180($row->getOutputTimeToStartValue())}};"></div>
            <div class="pie__segment" data-label="{{$row->getOutputStepRuntime()}}" style="--offset: {{$row->getOutputRuntimeOffset()}}; --value: {{$row->getOutputRuntimeValue()}}; --bg: {{$tmp[7]}};--over50: {{$row->isBiggerThen180($row->getOutputRuntimeValue())}};"></div>
        </div>
    </div>
@endforeach

<div class="pie-legend">
    <div style="--bcg:{{$colorSet[0][0]}};">Import Time to Start</div>
    <div style="--bcg:{{$colorSet[0][1]}};">Import run time</div>
    <div style="--bcg:{{$colorSet[0][2]}};">Etl Time to Start</div>
    <div style="--bcg:{{$colorSet[0][3]}};">Etl run time</div>
    <div style="--bcg:{{$colorSet[0][4]}};">Calc Time to Start</div>
    <div style="--bcg:{{$colorSet[0][5]}};">Calc run time</div>
    <div style="--bcg:{{$colorSet[0][6]}};">Output Time to Start</div>
    <div style="--bcg:{{$colorSet[0][7]}};">Output run time</div>
</div>






