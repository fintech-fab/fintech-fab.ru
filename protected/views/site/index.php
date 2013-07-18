<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
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

    .required span
    {
        float:none;
    }


</style>

<br/>
<div class="container container_12">
<div class="grid_12">
<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>

   <p><a href="index.php?r=site/form1">Перейти к форме 1</a></p>

    <div class="form">
        <?php $model=new ClientJoinForm;
        ?>
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