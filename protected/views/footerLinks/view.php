<?php
/* @var $this FooterLinksController */
/* @var $model FooterLinks */


$this->pageTitle = Yii::app()->name . " - " . CHtml::encode($model->link_title);

echo $model->link_content;
