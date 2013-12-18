<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

$this->pageTitle = Yii::app()->name;


$aCrumbs = Yii::app()->clientForm->getBreadCrumbs();

$this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

<?php if (Yii::app()->session['error']): ?>
	<div class="alert alert-error"><?= Yii::app()->session['error']; ?></div>
	<?php Yii::app()->session['error'] = null; ?>
<?php endif; ?>

	<div class="alert in alert-block alert-info" style="color: #000000;">
		<strong>Всего несколько шагов и Вы получите решение по займу:</strong>

		<ol>
			<?php if (SiteParams::getIsIvanovoSite()): ?>
				<li>Выберите сумму, срок займа и канал получения (на мобильный телефон или банковскую карту).</li>
			<?php endif; ?>
			<?php if (!SiteParams::getIsIvanovoSite()): ?>
				<li>Выберите удобный Пакет займов и канал получения (на мобильный телефон или банковскую карту).</li>
			<?php endif; ?>

			<li>Заполните анкету</li>
			<li>Убедитесь в наличие работающей веб-камеры и используйте один из перечисленных браузеров: Chrome или
				Firefox последних версий.
			</li>
			<li>Приготовьте свой паспорт и <span id="second-document-popover" class="dashed">второй документ</span> для
				демонстрации в вэб-камеру при прохождении идентификации (подтверждение личности).
			</li>
			<li>Обращаем Ваше внимание, что проверка анкеты осуществляется ежедневно с 10:00 до 22:00 по московскому
				времени.
			</li>
			<li>Ознакомьтесь с Офертой <a href="#" class="dotted" onclick="return doOpenModalFrame('<?php
				$sOfferLinkName = SiteParams::getIsIvanovoSite() ? 'offer_ivanovo' : 'distance';
				$sOfferTitle = SiteParams::getIsIvanovoSite() ? 'Оферта' : 'Оферта на дистанционный займ';

				echo Yii::app()
					->createAbsoluteUrl('/footerLinks/view/' . $sOfferLinkName);
				?>', '<?= $sOfferTitle ?>');">здесь.</a>
			</li>
		</ol>
	</div>

	<div class="clearfix"></div>
	<script type="text/javascript">
		$('#second-document-popover').popover({
			html: true,
			trigger: 'hover',
			content: 'Заграничный паспорт<br/>Водительское удостоверение<br/>'
				+ 'Пенсионное удостоверение<br/>Военный билет<br/>Свидетельство ИНН<br/>'
				+ 'Страховое свидетельство государственного пенсионного страхования'
		});
	</script>

	<div id="form">
		<?php require dirname(__FILE__) . '/steps/' . $sSubView . '.php' ?>
	</div>

<?php
$this->widget('YaMetrikaGoalsWidget', array(
	'iDoneSteps' => Yii::app()->clientForm->getCurrentStep()
));
