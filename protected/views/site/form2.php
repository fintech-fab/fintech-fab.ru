<?php
/* @var $this SiteController */
/* @var $model ClientForm2 */
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
	.checkbox
	{
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
				'curStep'=>3,
			)); ?>
		</div>
	</div>
	<div class="row">
		<div class="span10 offset1">
		    <div class="form">
				<p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'client-form2',
					'enableAjaxValidation'=>true,
					'clientOptions'=>array(
						'validateOnChange'=>true,
						//'afterValidateAttribute'=>'js:progressBarUpdate',//функция обновления прогресс-бара после валидации полей
					),
				)); ?>
				<!--?php echo $form->errorSummary($model); ?-->

			    <h3>Второй документ</h3>

		    	<div class="row main_row">
		    	    <?php echo $form->labelEx($model,'document'); ?>
		        	<?php echo $form->dropDownList($model,'document',$model->getDocuments()); ?>
		        	<?php echo $form->error($model,'document'); ?>
		    	</div>

		    	<div class="row main_row">
			        <?php echo $form->labelEx($model,'document_number'); ?>
			        <?php echo $form->textField($model,'document_number'); ?>
			        <?php echo $form->error($model,'document_number'); ?>
			    </div>

			    <h3>Адрес</h3>

	    		<div class="row main_row">
			        <?php echo $form->labelEx($model,'address_reg_region'); ?>
		    	    <?php echo $form->textField($model,'address_reg_region'); ?>
		        	<?php echo $form->error($model,'address_reg_region'); ?>
		    	</div>

	    		<div class="row main_row">
	        		<?php echo $form->labelEx($model,'address_reg_city'); ?>
	        		<?php echo $form->textField($model,'address_reg_city'); ?>
		        	<?php echo $form->error($model,'address_reg_city'); ?>
	    		</div>

		    	<div class="row main_row">
			        <?php echo $form->labelEx($model,'address_reg_address'); ?>
		    	    <?php echo $form->textField($model,'address_reg_address'); ?>
	    	    	<?php echo $form->error($model,'address_reg_address'); ?>
			    </div>

			    <h3>Контактные данные</h3>

			    <div class="row main_row">
		    	    <?php echo $form->labelEx($model,'job_phone'); ?>+7
	    	    	<?php echo $form->textField($model,'job_phone'); ?>
		        	<?php echo $form->error($model,'job_phone'); ?>
		    	</div>

		    	<div class="row main_row">
		        	<?php echo $form->labelEx($model,'email'); ?>
			        <?php echo $form->textField($model,'email'); ?>
		    	    <?php echo $form->error($model,'email'); ?>
		    	</div>

				<div class="row checkbox">
					<?php echo $form->checkBox($model,'complete'); ?>
					<?php echo $form->labelEx($model,'complete'); ?>
					<?php echo $form->error($model,'complete'); ?>
				</div>
				<div class="row main_row"></div>

				<div class="row buttons">
					<?php echo CHtml::submitButton('Далее →',
						array('class' => 'btn btn-info')
					); ?>
				</div>

				<?php $this->endWidget(); ?>
			</div>

			<?php
			$this->widget('FormProgressBar',array(
				'allFields'=>17,
				'filledFields'=>9,
				'htmlOptions'=>array(
					'style'=>'height:20px;',
				),
				'model'=>$model,
			));
			?>
		</div>
	</div>
</div>
