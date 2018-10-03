<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Monkey\Laravel\v5_4\Illuminate\Routing\AController;

class Controller extends AController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

}
