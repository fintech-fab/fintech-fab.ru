<?php

/**
 * Class UserCityWidget
 */
class UserCityWidget extends CWidget
{
	public $sCityName;
	public $bCitySelected = false; //город выбран
	public $sCityAndRegion;
	public $sCsrfTokenName;
	public $sCsrfToken;
	public $bUpdate = false;

	/**
	 * TODO причесать тут весь код, плюс код представления
	 * TODO сделать чистку и валидацию того, что шлет юзер
	 */

	public function run()
	{
		$this->sCsrfTokenName = Yii::app()->request->csrfTokenName;
		$this->sCsrfToken = Yii::app()->request->csrfToken;

		$oCityNameCookie = Yii::app()->request->cookies['cityName'];
		$oCityAndRegionCookie = Yii::app()->request->cookies['cityAndRegion'];
		$oCitySelectedCookie = Yii::app()->request->cookies['citySelected'];

		if (!empty($oCitySelectedCookie->value) && !empty($oCityAndRegionCookie) && !empty($oCityNameCookie)) {
			$this->bCitySelected = true;
		}

		$sCity = ids_ipGeoBase::getCityByIP();
		if ($oCityNameCookie && $oCityAndRegionCookie) { // если в куках есть город и регион, выводим его
			$this->sCityName = $oCityNameCookie->value;
			$this->sCityAndRegion = $oCityAndRegionCookie->value;
		} elseif ($sCity) { // если удалось определить город по ip
			$this->sCityName = $sCity;
			$sRegion = ids_ipGeoBase::getRegionByIP();
			$this->sCityAndRegion = $sCity;
			$this->sCityAndRegion .= ($sCity != $sRegion) ? (', ' . $sRegion) : '';
			if (!$oCityAndRegionCookie) {
				Yii::app()->request->cookies['cityAndRegion'] = new CHttpCookie('cityAndRegion', $this->sCityAndRegion);
			}
		} else {
			$this->sCityName = false;
		}

		//TODO возможно, вынести в отдельные представления
		if (!$oCityNameCookie && $this->sCityName) {
			$sDataContent = 'Мы автоматически определили ваш город: ';
			$sDataContent .= '<strong>' . $this->sCityAndRegion . '</strong>';
			$sDataContent .= '<br/> Правильно? <br/>';
			$sDataContent .= $this->widget(
				'bootstrap.widgets.TbButton',
				array(
					'label'       => 'Да, правильно',
					'type'        => 'primary',
					'size'        => 'small',
					'htmlOptions' => array(
						'data-toggle' => 'modal',

						'onclick'     => 'js: confirmCity()'
					),
				),
				true
			);
			$sDataContent .= '&nbsp;' . $this->widget(
					'bootstrap.widgets.TbButton',
					array(
						'label'       => 'Нет, изменить город',
						'type'        => 'primary',
						'size'        => 'small',
						'htmlOptions' => array(
							'data-toggle' => 'modal',
							'data-target' => '#locationModal',
						),
					),
					true
				);
		} elseif ($this->sCityName) {
			$sDataContent = 'Ваш город: ';
			$sDataContent .= '<strong>' . $this->sCityAndRegion . '</strong><br/>';
			$sDataContent .= '&nbsp;' . $this->widget(
					'bootstrap.widgets.TbButton',
					array(
						'label'       => 'Изменить город',
						'type'        => 'primary',
						'size'        => 'small',
						'htmlOptions' => array(
							'data-toggle' => 'modal',
							'data-target' => '#locationModal',
						),
					),
					true
				);
		} else {
			$this->sCityName = "город не определён";
			$sDataContent = 'Ваш город не удалось определить. Укажите город самостоятельно. ';
			$sDataContent .= '&nbsp;' . $this->widget(
					'bootstrap.widgets.TbButton',
					array(
						'label'       => 'Указать город',
						'type'        => 'primary',
						'size'        => 'small',
						'htmlOptions' => array(
							'data-toggle' => 'modal',
							'data-target' => '#locationModal',
						),
					),
					true
				);
		}


		$sModalBody = $this->widget(
			'bootstrap.widgets.TbSelect2',
			array(
				'name'           => 'cityName',
				'asDropDownList' => false,
				'data'           => null,
				'options'        => array(
					'width'               => '400px',
					'style'               => 'z-index: 110000',
					'placeholder'         => $this->sCityName,
					'minimumInputLength'  => '2', // минимум введённых символов для начала поиска
					'ajax'                => array(
						'url'      => Yii::app()->createUrl("/site/getCitiesAndRegionsListJson"),
						'dataType' => 'json',
						'data'     => 'js: function (term) {
										return {
											sInput: term,
										};
									}',
						'results'  => 'js: function (data) {
										return {
											results: data
										};
									}',
					),
					'formatNoMatches'     => 'js: function() {
										return "Ничего не найдено";
									}',
					'formatSearching'     => 'js: function() {
										return "Подождите, идёт поиск...";
									}',
					'formatInputTooShort' => 'js: function (input, min) {
										var n = min - input.length;
										return "Пожалуйста, введите ещё " + n + " символ" + (n == 1 ? "" : "а") + " для начала поиска";
									}',
					'formatResult'        => 'js: function (object) {
										return object.cityAndRegion;
									}',
					'formatSelection'     => 'js: function (object) {
										$("#locationModal").modal("hide");
										$("#userLocation").popover("hide");
										//шлем инфу на сервер и обновляем виджет через ajax
										$.ajax({
											url: \'' . Yii::app()->createUrl("/site/setCityToCookie") . '\',
											type: "POST",
											cache: false,
											dataType: "html",
											data: ({
                                                cityName: object.cityName,
                                                cityAndRegion: object.cityAndRegion,
                                                ' . $this->sCsrfTokenName . ': "' . $this->sCsrfToken . '"
                                            }),
											success: function(html){
												$("#userCityWidget").html(html);
											}
										});
										return object.city;
									}',
				)
			), true
		);

		$this->render('user_city_widget', array('sDataContent' => $sDataContent, 'sModalBody' => $sModalBody));
	}
}
