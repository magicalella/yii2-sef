<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Sef */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="sef-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link_sef')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'meta_title')->textInput() ?>
    
    <?= $form->field($model, 'meta_description')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('sef', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
