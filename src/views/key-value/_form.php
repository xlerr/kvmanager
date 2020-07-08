<?php

use kvmanager\models\KeyValue;
use xlerr\CodeEditor\CodeEditor;
use xlerr\common\widgets\ActiveForm;
use xlerr\common\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this View */
/* @var $model KeyValue */

?>

<?php $form = ActiveForm::begin([
    'action' => [
        '',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
        'id'                  => $model->key_value_id,
    ],
]); ?>

<div class="box-body">
    <?= Html::activeHiddenInput($model, 'key_value_namespace') ?>
    <?= Html::activeHiddenInput($model, 'key_value_group') ?>

    <?= $form->field($model, 'key_value_key')->textInput([
        'maxlength' => true,
        'disabled'  => !$model->isNewRecord,
    ]) ?>

    <?= $form->field($model, 'key_value_type')->widget(Select2::class, [
        'data'         => KeyValue::typeList(),
        'hideSearch'   => true,
        'pluginEvents' => [
            'change' => new JsExpression('typeChange'),
        ],
    ]) ?>

    <?= $form->field($model, 'key_value_value')->widget(CodeEditor::class, [
        'clientOptions' => [
            'mode' => $model->getEditorMode(),
            'maxLines' => 40,
        ],
    ]) ?>

    <?= $form->field($model, 'key_value_memo')->textarea([
        'rows' => 2,
    ]) ?>

</div>

<div class="box-footer">
    <?= Html::submitButton(Yii::t('kvmanager', $model->isNewRecord ? 'Create' : 'Update'), [
        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
    ]) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    <?php $this->beginBlock('typeChange') ?>
    const mapping = <?= json_encode(KeyValue::getEditorModes()) ?>,
        editor = aceInstance['<?= Html::getInputId($model, 'key_value_value') ?>'];

    function typeChange(e) {
        let type = $(e.currentTarget).val();
        editor.getSession().setMode(mapping[type]);
    }
    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['typeChange']) ?>
</script>