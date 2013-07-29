<?php
/* @var $this SiteController */
/* @var $model ClientForm1 */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name;
?>
<?php
$this->widget('TopPageWidget');

?>
<style type="text/css">

    .main_row label
    {
        margin-top:5pt;
        margin-right: 5pt;
        float:left;
    }

    .gender label
    {
        float:left;
        margin-right: 5pt;
    }


    .gender span
    {
        float:left;
    }


    .required span
    {
        float:none;
    }

    .gender input
    {
        margin-right: 5pt;
        font-weight: bold;
        font-size: 0.9em;
        float:left;
        margin-left:20px;
        text-align:left;
        width:100px;
    }

</style>

<div class="container">
	<div class="row">
		<div class="span12">
		<?php $this->widget('StepsBreadCrumbs',array(
			'curStep'=>2,
		)); ?>
		</div>
	</div>
	<div class="row">
		<div class="span10 offset1">
	    	<div class="form">
	    		<p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

    			<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'client-form1',
					'enableAjaxValidation'=>true,
					'clientOptions'=>array(
						'validateOnChange'=>true,
						'afterValidateAttribute'=>'js:progressBarUpdate',//функция обновления прогресс-бара после валидации полей
						),
				)); ?>
				<!--?php echo $form->errorSummary($model); ?-->

    			<h3>Личные данные</h3>

    			<div class="row main_row">
	        		<?php echo $form->labelEx($model,'first_name'); ?>
	        		<?php echo $form->textField($model,'first_name'); ?>
	        		<?php echo $form->error($model,'first_name'); ?>
	    		</div>

    			<div class="row main_row">
	        		<?php echo $form->labelEx($model,'last_name'); ?>
	        		<?php echo $form->textField($model,'last_name'); ?>
	        		<?php echo $form->error($model,'last_name'); ?>
	    		</div>

    			<div class="row main_row">
	        		<?php echo $form->labelEx($model,'third_name'); ?>
	        		<?php echo $form->textField($model,'third_name'); ?>
	        		<?php echo $form->error($model,'third_name'); ?>
	    		</div>

    			<div class="row main_row">
	        		<?php echo $form->labelEx($model,'birthday'); ?>
	        		<?php echo $form->dateField($model,'birthday',array('style'=>"display:none;")); ?>
	        		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
    	        		'name' => 'birthday',
	            		'model' => $model,
	            		'attribute' => 'birthday',
	            		'language' => 'ru',
	            		'options' => array(
	                		'showAnim' => 'fold',
	                		'dateFormat'=>'yy-mm-dd',
	                		'onLoad'=>'js:  $("#birthday").change(function(){$("#ClientForm1_birthday").val($("#birthday").val());$("#ClientForm1_birthday").change();})',
	                		),
	            		'htmlOptions' => array(
	                		'style' => 'height:20px;',
	            		),
	        		));?>
	        		<?php echo $form->error($model,'birthday'); ?>
	    		</div>


					<div class="row gender">
					<?php echo $form->labelEx($model,'sex'); ?>
        			<?php echo $form->radioButtonList($model,'sex',['мужской','женский']); ?>
        			<?php echo $form->error($model,'sex'); ?>
    			</div>

		    	<h3>Паспортные данные</h3>

	    		<div class="row main_row">
	        		<?php echo $form->labelEx($model,'passport_series'); ?>
	        		<?php echo $form->textField($model,'passport_series'); ?>
	        		<?php echo $form->error($model,'passport_series'); ?>
	    		</div>

	    		<div class="row main_row">
	        		<?php echo $form->labelEx($model,'passport_number'); ?>
	        		<?php echo $form->textField($model,'passport_number'); ?>
	        		<?php echo $form->error($model,'passport_number'); ?>
	    		</div>

	    		<div class="row main_row">
	        		<?php echo $form->labelEx($model,'passport_code'); ?>
	        		<?php echo $form->textField($model,'passport_code'); ?>
	        		<?php echo $form->error($model,'passport_code'); ?>
	    		</div>

    			<div class="row main_row">
        			<?php echo $form->labelEx($model,'passport_date'); ?>
        			<?php echo $form->dateField($model,'passport_date',array('style'=>"display:none;")); ?>
        			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
	            		'name' => 'passport_date',
    	        		'model' => $model,
        	    		'attribute' => 'passport_date',
            			'language' => 'ru',
            			'options' => array(
                			'showAnim' => 'fold',
                			'dateFormat'=>'yy-mm-dd',
                			'onLoad'=>'js:  $("#passport_date").change(function(){$("#ClientForm1_passport_date").val($("#passport_date").val());$("#ClientForm1_passport_date").change()})',
            			),
            			'htmlOptions' => array(
	                		'style' => 'height:20px;',
	            		),
	        		));?>
	        		<?php echo $form->error($model,'passport_date'); ?>
	    		</div>

				<div class="row buttons">
					<?php echo CHtml::submitButton('Далее'); ?>
				</div>
				<?php $this->endWidget(); ?>
			</div>

			<?php
			$this->widget('FormProgressBar',array(
				'startFilledFields'=>0,
				// additional javascript options for the progress bar plugin
				'options'=>array(
					//'change'=>new CJavaScriptExpression('function(event, ui) {...}'),
				),
				'htmlOptions'=>array(
				    'style'=>'height:20px;',
				),
				'model'=>$model,
			));
			?>
		</div>
	</div>
</div>
