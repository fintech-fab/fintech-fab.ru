<?php

/**
 * Class UserCityWidget
 */
class UserCityWidget3 extends UserCityWidget
{
	public $sView = 'user_city_widget3';

	/**
	 * @return mixed
	 */
	protected function getModalBody()
	{
		return $this->widget(
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
										jQuery("#locationModal").modal("hide");
										jQuery("#userLocation").popover("hide");
										//шлем инфу на сервер и обновляем виджет через ajax
										jQuery.ajax({
											url: \'' . Yii::app()->createUrl("/site/setCityToCookie") . '\',
											type: "POST",
											cache: false,
											dataType: "html",
											data: ({
                                                cityName: object.cityName,
                                                cityAndRegion: object.cityAndRegion,
                                                ' . $this->sCsrfTokenName . ': "' . $this->sCsrfToken . '",
                                                bootstrap3: 1
                                            }),
											success: function(html){
												jQuery("#userCityWidget").html(html);
											}
										});
										return object.city;
									}',
				)
			), true
		);
	}
}
