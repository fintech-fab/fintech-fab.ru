<?php
use FintechFab\Models\User;

$user = User::find(Auth::user()->id);
$userSocial = User::find($user['id'])->SocialNetworks()->first()->toArray();
/*echo $userSocial['photo'];
echo'<pre>';
print_r($userSocial);
echo'</pre>';
die();*/

?>
<div class="jumbotron ">
	<h2 class="text-center">Профиль</h2>
	<img src="<?= $userSocial['photo'] ?>" class="img-rounded">
</div>
