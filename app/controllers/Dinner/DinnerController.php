<?php

namespace App\Controllers\Dinner;

use App\Controllers\BaseController;
use MenuItem;

class DinnerController extends BaseController
{

    public $layout = 'dinner';

    public function getDinner()
    {

	    // Получаем текущие время
	    $hour = (int) date('H');

		// Заказ обеда возможен с 8 до 16 ,
	    // Если $hour не подпадает в этот интервал говорим что заказ невозможен
	    if($hour < 8 || $hour > 16){
		    return $this->make('dinner' , array('end_dinner' => 'true'));
	    }


	    // Формируем данные для заказа
	    $menu = MenuItem::all();
        return $this->make('dinner' , array('menu' => $menu,));
    }

}