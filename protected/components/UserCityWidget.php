<?php
/**
 * Class UserCityWidget
 */
class UserCityWidget extends CWidget
{
	public $sCityName = "не определён";

	public function run()
	{
		$sCsrfTokenName = Yii::app()->request->csrfTokenName;
		$sCsrfToken = Yii::app()->request->csrfToken;


		$oCityIdCookie = Yii::app()->request->cookies['cityId'];

		/**
		 * TODO прочитать комментарий ниже
		 * в куки писать ВСЮ инфу, в т.ч. название города и региона, и когда в куке инфа есть - не читать из БД!
		 * после первого считывания инфы из БД СРАЗУ ее писать в куку, ставить флаг "клиент подтвердил местоположение"
		 * если не подтвердил - предлагать подтвердить либо поменять город
		 * но читать данные ИЗ КУКИ, а не БД
		 */

		if (!empty($oCityIdCookie)) { // если в куках есть id города, выводим его
			$this->sCityName = CitiesRegions::getCityNameById($oCityIdCookie->value);
		} elseif (ids_ipGeoBase::getCityByIP()) { // если удалось определить город по ip
			$this->sCityName = ids_ipGeoBase::getCityByIP();
			$this->sCityAndRegionName = ids_ipGeoBase::getCityByIP() . ", " . ids_ipGeoBase::getRegionByIP();
		}

		//$sCityName = ids_ipGeoBase::getCityByIP('46.38.98.106');

		$sDataContent = 'Мы автоматически определили ваш город: ';
		$sDataContent .= '<strong>' . $this->sCityName . '</strong>';
		$sDataContent .= '<br/> Правильно? <br>';
		$sDataContent .= $this->widget(
			'bootstrap.widgets.TbButton',
			array(
				'label'       => 'Да, правильно',
				'type'        => 'primary',
				'size'        => 'small',
				'htmlOptions' => array(
					'data-toggle' => 'modal',
					'onclick'     => '$("#userLocation").popover("hide");'
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
						'data-target' => '#myModal',
					),
				),
				true
			);

		//TODO сделать автоматическое обновление города в виджете
		$sModalBody = $this->widget(
			'bootstrap.widgets.TbSelect2',
			array(
				'name'           => 'cityId',
				'asDropDownList' => false,
				'data'           => null,
				'options'        => array(
					'width'               => '400px',
					'style' => 'z-index: 110000',
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
										$.post(
                                            "' . Yii::app()->createUrl("/site/setCityIdToCookie") . '",
                                            {
                                                cityId: object.id,
                                                ' . $sCsrfTokenName . ': "' . $sCsrfToken . '"
                                            }
										);
										$("#myModal").modal("hide");
										return object.city;
									}',
				)
			), true
		);

		$this->render('user_city_widget', array('sDataContent' => $sDataContent, 'sModalBody' => $sModalBody));
	}
}
