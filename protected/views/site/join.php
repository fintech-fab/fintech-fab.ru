<?php
/* @var $this ClientFormFormController */
/* @var $model ClientJoinForm */
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
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'client-join',
            'action' => array('site/join'),
            'enableAjaxValidation'=>true,
        )); ?>
        <!--?php echo $form->errorSummary($model); ?-->

        <div class="row main_row">
            <?php echo $form->labelEx($model,'phone'); ?>+7
            <?php echo $form->textField($model,'phone'); ?>
            <?php echo $form->error($model,'phone'); ?>
        </div>
        <div class="row buttons">
            <?php echo CHtml::submitButton('Присоединиться'); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
</div>
