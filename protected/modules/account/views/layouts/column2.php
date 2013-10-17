<?php /* @var $this Controller */

?>

<?php $this->beginContent('/layouts/main'); ?>
<div class="container" id="main-content">
	<div class="row">
		<div class="span8">
			<?php if(Yii::app()->user->hasFlash('success')):?>
			<div class="alert alert-success"><?= Yii::app()->user->getFlash('success'); ?></div>
			<?php endif; ?>
			<?php if(Yii::app()->user->hasFlash('error')):?>
				<div class="alert alert-error"><?= Yii::app()->user->getFlash('error'); ?></div>
			<?php endif; ?>
			<?= $content; ?>
		</div>
		<!-- content -->
		<div class="span4">
			<?php $this->renderPartial('menu'); ?>
		</div>

	</div>
</div>
<link __jivo_css_style rel="stylesheet" type="text/css" href="<?= Yii::app()->request->baseUrl; ?>/static/css/jivo.css?v=1" />
<div id="jivo_top_wrap">
	<div id="jivo_chat_widget">
		<div id="jivo_chat_widget_offline">
			<div id="jivo-label" class="fixed-bottom" style="right: 10px; height: 34px;">
				<table id="jivo-label-wrapper" class="jivo_shadow jivo_rounded_corners jivo_gradient jivo_3d_effect jivo_border jivo_3px_border" style="height: 34px; border-color: rgb(19, 19, 19); background-color: rgb(28, 28, 28);">
					<tbody>
					<tr>
						<td id="jivo-label-status"></td>
						<td id="jivo-label-text" onclick="javascript: window.open('<?= Yii::app()->createAbsoluteUrl("/site/contact"); ?>');" style="font-weight: bold; font-style: normal; font-size: 13px; font-family: Arial; color: rgb(255, 255, 255); text-shadow: 0px -1px rgb(16, 16, 16);">Отправьте нам сообщение</td>
						<td id="jivo-label-copyright">
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $this->endContent(); ?>
