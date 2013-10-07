<?php

/**
 * @var $this UserCityWidget
 */

$sCsrfTokenName = Yii::app()->request->csrfTokenName;
$sCsrfToken = Yii::app()->request->csrfToken;

?>
<br/>
<div id="userCityWidget" class="span2">
	<?php
	//TODO сменить на реальный класс
	$model = new FakeActiveRecord;

	//тут задается ID текущего города
	$model->cityId = 2097;

	$this->widget('bootstrap.widgets.TbEditableField',
		array(
			'type'      => 'select2',
			'title'=>'Начните вводить название города:',
			'model'     => $model,
			'attribute' => 'cityId',
			'url'       => array('/site/setCityIdToCookie'),
			'source' => array(2097=>'Москва'),//нужно сюда передать текущий город, ID указан выше
			'placement' => 'bottom',
			'select2'   => array(

					'width'               => '400px',

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
										return "Пожалуйста, введите ещё " + n + " символ"
										+ (n == 1 ? "" : "а") + " для начала поиска";
									}',
					'formatResult'        => 'js: function (object) {
										return object.city;
									}',
					'formatSelection'     => 'js: function (object) {
										$.post(
                                            "' . Yii::app()->createUrl("/site/setCityIdToCookie") . '",
                                            {
                                                city_id: object.id,
                                                ' . $sCsrfTokenName . ': "' . $sCsrfToken . '"
                                            }
										);
										return object.city;
									}',
				)

		)
	);


	?>
</div>
