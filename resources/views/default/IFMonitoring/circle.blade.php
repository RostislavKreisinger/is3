<style>

.pie{
    background: #639;
    border-radius: 100%;
    height: calc(var(--size, 200)* 1px);
    position: relative;
    overflow: hidden;
    width: calc(var(--size, 200)* 1px);
}
.pie__segment{
    height: 100%;
    overflow: hidden;
    position: absolute;
    transform: translate(0,-50%) rotate(90deg) rotate(calc(var(--offset, 0) * 1deg));
    transform-origin: 50% 100%;
    width: 100%;
}

.pie__segment:before{
    background: var(--bg,red);
    content: '';
    height: 100%;
    position: absolute;
    transform: translate(0,100%) rotate(calc(var(--value, 45) * 1deg));
    transform-origin: 50% 0;
    width: 100%;
}



</style>

@foreach ($graph as $row)
<div class="pie" style="--size: 500;">
    <div class="pie__segment" style="--offset: {{$row->getImportTimeToStartOffset()}}; --value: {{$row->getImportTimeToStartValue()}}; --bg: red;"></div>
    <div class="pie__segment" style="--offset: {{$row->getImportRuntimeOffset()}}; --value: {{$row->getImportRuntimeValue()}}; --bg: blue;"></div>

    <div class="pie__segment" style="--offset: {{$row->getEtlTimeToStartOffset()}}; --value: {{$row->getEtlTimeToStartValue()}}; --bg: red;"></div>
    <div class="pie__segment" style="--offset: {{$row->getEtlRuntimeOffset()}}; --value: {{$row->getEtlRuntimeValue()}}; --bg: blue;"></div>

    <div class="pie__segment" style="--offset: {{$row->getCalcTimeToStartOffset()}}; --value: {{$row->getCalcTimeToStartValue()}}; --bg: red;"></div>
    <div class="pie__segment" style="--offset: {{$row->getCalcRuntimeOffset()}}; --value: {{$row->getCalcRuntimeValue()}}; --bg: blue;"></div>

    <div class="pie__segment" style="--offset: {{$row->getOutputTimeToStartOffset()}}; --value: {{$row->getOutputTimeToStartValue()}}; --bg: red;"></div>
    <div class="pie__segment" style="--offset: {{$row->getOutputRuntimeOffset()}}; --value: {{$row->getOutputRuntimeValue()}}; --bg: blue;"></div>

</div>
@endforeach