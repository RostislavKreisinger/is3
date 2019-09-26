
<style>
    .cylinder {
        position: relative;
        overflow: hidden;
        margin: 0 auto -45px;
        width: calc(var(--size, 0) * 1px);
        height: 65px;
        border-radius: calc(var(--size, 0) / 2 * 1px) / 25px;
        background-color: rgba(160, 160, 160, 1);
        z-index: var(--z,9999);
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .cylinder:before {
        position: absolute;
        left: 0;
        top: 0;
        width: calc(var(--size, 0) * 1px);
        height: 50px;
        border-radius: calc(var(--size, 0) / 2 * 1px) / 25px;
        background-color: rgba(160, 160, 160, 0.1);
        content: '';
        border: 1px solid black;
    }

    .cylinder:after {
        position: absolute;
        left: 0;
        bottom: 0;
        width: calc(var(--size, 0) * 1px);
        height: 50px;
        border-radius: calc(var(--size, 0) / 2 * 1px) / 25px;
        background-color: rgba(160, 160, 160, 0.1);
        content: '';
    }

    .average{
        background-color: orange;
    }

    .cylinder:hover{
        background-color: red;
    }


</style>

<div class="cylinder" style="--size: 300;--z:5;"></div>
<div class="cylinder" style="--size: 350;--z:4;"></div>
<div class="cylinder average" style="--size: 400;--z:3;"></div>
<div class="cylinder" style="--size: 450;--z:2;"></div>
<div class="cylinder" style="--size: 500;--z:1;"></div>
