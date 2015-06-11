<?php
namespace FintechFab\Widgets;


use Auth;
use FintechFab\Models\User;
use Route;
use URL;

class LinksInMenu
{
	public static function echoAuthMode()
	{
		if (Auth::check()) {
			return LinksInMenu::linkForUserProfile();
		}
		$link_registration = '<li class="' . LinksInMenu::isActive(URL::route('registration')) . '">
								<a href="' . URL::route('registration') . '">Регистрация</a>
							</li>';
		$link_login = '<li>
							<a href="" data-toggle="modal" data-target="#loginModal">Вход</a>
						</li>';

		return $link_registration . $link_login;
	}

	public static function linkForUserProfile()
	{
		$first_name = Auth::user()->first_name;
		$last_name = Auth::user()->last_name;
		$link_admin = '';
		$user = User::find(Auth::user()->getAuthIdentifier());
		foreach ($user->roles as $role) {
			if ($role->role == "admin") {
				$link_admin = '<li class="' . LinksInMenu::isActive(URL::route('admin')) . '">
									<a href ="' . URL::route('admin') . '">Админ панель</a>
								</li>';
			}
		}
		$link_user = '<li class="' . LinksInMenu::isActive(URL::route('profile')) . '">
							<a href="' . URL::route('profile') . '">' . $first_name . ' ' . $last_name . '</a>
						</li>';

		$link_logout = '<li><a href="/logout">Выход</a></li>';

		return $link_admin . $link_user . $link_logout;
	}

	public static function linkForMainMenu()
	{
		$link_main_menu = '';
		if (Auth::check() && Route::has('qiwiGate_account') && Route::has('qiwiShop_createOrder')) {
			$link_main_menu = '<li>
									<a href ="' . URL::route('qiwiShop_createOrder') . '">Магазин QIWI</a>
								</li>
								<li>
									<a href="' . URL::route('qiwiGate_account') . '">Терминал QIWI</a>
								</li>';
		}

		return $link_main_menu;
	}

	public static function isActive($url)
	{
		if (URL::current() == $url) {
			return 'active';
		}

		return '';
	}


}