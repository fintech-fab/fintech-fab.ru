<?php
/**
 * Class UserCityWidget
 */
class UserCityWidget extends CWidget
{
	public $sCityName = "не определён";
	public $bCitySelected = false;
	public $sCityAndRegionName;
	public  $sCsrfTokenName;
	public  $sCsrfToken;
	public $bUpdate = false;

	public function run()
	{
		$this->sCsrfTokenName = Yii::app()->request->csrfTokenName;
		$this->sCsrfToken = Yii::app()->request->csrfToken;

		$oCityNameCookie = Yii::app()->request->cookies['cityName'];
		$oCitySelectedCookie = Yii::app()->request->cookies['citySelected'];

		if (!empty($oCitySelectedCookie->value)) {
			$this->bCitySelected = true;
		}

		/**
		 * TODO прочитать комментарий ниже
		 * в куки писать ВСЮ инфу, в т.ч. название города и региона, и когда в куке инфа есть - не читать из БД!
		 * после первого считывания инфы из БД СРАЗУ ее писать в куку, ставить флаг "клиент подтвердил местоположение"
		 * если не подтвердил - предлагать подтвердить либо поменять город
		 * но читать данные ИЗ КУКИ, а не БД
		 */

		if ($oCityNameCookie) { // если в куках есть город, выводим его
			$this->sCityName = $oCityNameCookie->value;
		} elseif (ids_ipGeoBase::getCityByIP('46.38.98.106')) { // если удалось определить город по ip
			$this->sCityName = ids_ipGeoBase::getCityByIP('46.38.98.106');
			$this->sCityAndRegionName = ids_ipGeoBase::getCityByIP('46.38.98.106') . ", " . ids_ipGeoBase::getRegionByIP('46.38.98.106');
		} else {
			$this->sCityName = false;
		}

		//$sCityName = ids_ipGeoBase::getCityByIP('46.38.98.106');
		//TODO вынести в отдельные представления
		if (!$oCityNameCookie && $this->sCityName) {
			$sDataContent = 'Мы автоматически определили ваш город: ';
			$sDataContent .= '<strong>' . $this->sCityName . '</strong>';
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
			$sDataContent .= '<strong>' . $this->sCityName . '</strong><br/>';
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

		//TODO сделать автоматическое обновление города в виджете
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
										return object.city;
									}',
					'formatSelection'     => 'js: function (object) {
										/*$.post(
                                            "' . Yii::app()->createUrl("/site/setCityToCookie") . '",
                                            {
                                                cityName: object.city,
                                                ' . $this->sCsrfTokenName . ': "' . $this->sCsrfToken . '"
                                            }
										);*/
										$("#locationModal").modal("hide");
										$("#userLocation").popover("hide");

										$.ajax({
											url: \''.Yii::app()->createUrl("/site/setCityToCookie").'\',
											type: "POST",
											cache: false,
											dataType: "html",
											data: ({
                                                cityName: object.city,
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
