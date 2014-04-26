<?php

return array(

	'maxSum'   => 5000,
	'minSum'   => 10,
	'user_id'  => Auth::user()->getAuthIdentifier(),
	'lifetime' => 3, //Срок действия заказа в днях

);