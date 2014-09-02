<?php

namespace App\Controllers\Dinner;

use App\Controllers\BaseController;

class DinnerController extends BaseController
{

    public $layout = 'dinner';

    public function dinner()
    {
        return $this->make('dinner');
    }

}