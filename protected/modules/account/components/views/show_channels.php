<?php
/**
 * @var $this            ShowChannelsWidget
 * @var $aClientChannels array
 * @var $aImageNames     array
 */


foreach ($aClientChannels as $sKey => $mChannel) {
	if ($mChannel !== false) {
		echo '<div class="well"><a href="' . $mChannel . '"><img src="/static/images/channels/' . $aImageNames[$sKey] . '" style="height:120px;"></a>';

		$this->widget('bootstrap.widgets.TbButton',
			array(
				'buttonType'  => 'submit',
				'type'        => 'primary',
				'size'        => 'small',
				'label'       => 'Подключить Пакет',
				'htmlOptions' => array(
					'value' => $mChannel,
				),
			)
		);

		echo '</div>';
	} else {
		echo '<img src="/static/images/channels/' . $aImageNames[$sKey] . '">';

		$this->widget('bootstrap.widgets.TbButton',
			array(
				//'buttonType'  => 'submit',
				'type'        => 'primary',
				'size'        => 'small',
				'label'       => 'Подключить Пакет',
				'htmlOptions' =>
					array(
						'disabled' => 'disabled',
						'title'    => 'Данный канал недоступен!',
					),
			)
		);

	}
}
