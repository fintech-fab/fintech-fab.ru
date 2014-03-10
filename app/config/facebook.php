<?php
// app/config/facebook.php

// Facebook app Config 


return array(
	'appId'         => '',
	'secret'        => '',
	'redirect_uri'  => 'http://localhost/fb-auth',
	'response_type' => 'code',
	'scope'         => 'email,user_birthday',
	'url'           => 'https://www.facebook.com/dialog/oauth',
);
