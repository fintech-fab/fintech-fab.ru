<?php
/* @var $this SiteController */
/* @var $model ClientForm2 */
/* @var $form CActiveForm */
?>
<?php
$this->beginWidget('TopPageWidget');
$this->endWidget();
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

<div class="container container_12" style="margin-top: 20px;">
<div class="grid_12">
    <div class="form">
    <p class="note">Поля, отмеченные <span class="required">*</span> , являются обязательными.</p>

    <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'client-form2',
	'enableAjaxValidation'=>true,
)); ?>
	<!--?php echo $form->errorSummary($model); ?-->

    <h3>Второй документ</h3>
        <?php
        $aDocuments=array("Заграничный паспорт"=>"Заграничный паспорт",
            "Водительское удостоверение"=>"Водительское удостоверение",
            "Пенсионное удостоверение"=>"Пенсионное удостоверение",
            "Военный билет"=>"Военный билет",
            "Свидетельство ИНН"=>"Свидетельство ИНН",
            "Страховое свидетельство государственного пенсионного страхования"=>"Страховое свидетельство государственного пенсионного страхования"
        );
        ?>
    <div class="row main_row">
        <?php echo $form->labelEx($model,'document'); ?>
        <?php echo $form->dropDownList($model,'document',$aDocuments); ?>
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
        <?php echo $form->labelEx($model,'phone'); ?>+7
        <?php echo $form->textField($model,'phone'); ?>
        <?php echo $form->error($model,'phone'); ?>
    </div>

    <div class="row main_row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email'); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Далее'); ?>
	</div>
</div>
    <?php $this->endWidget(); ?>
</div>
</div>
