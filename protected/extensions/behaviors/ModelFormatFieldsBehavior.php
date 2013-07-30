<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m.novikov
 * Date: 18.04.13
 * Time: 9:06
 * To change this template use File | Settings | File Templates.
 *
 * @property Contract $owner
 *
 */

class ModelFormatFieldsBehavior extends CBehavior{

	public function formatFlagActive(){

		return $this->owner->flag_active == 1 ? 'да': 'нет';

	}


}