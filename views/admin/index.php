<?php

use humhub\widgets\ActiveForm;
use humhub\libs\Html;
use humhub\modules\space\widgets\SpacePickerField;
use humhub\modules\user\widgets\UserPickerField;
?>

<div class="panel panel-default">

    <div class="panel-heading"><?php echo Yii::t('NofcIntranetAnnouncementSpaceConfiguratorModule.base', '<strong>Nofc</strong> Intranet Announcement Space Configurator configuration'); ?></div>

    <div class="panel-body">
        <div class="help-block">
            <?php echo Yii::t('NofcIntranetAnnouncementSpaceConfiguratorModule.setting', 'Choose default spaces which existing and new users are automatically made members of and made to receive all space related notifications.'); ?>
        </div>

        <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <?= $form->field($model, 'spaces')->widget(SpacePickerField::class)->label(false); ?>
            <?= $form->field($model, 'assignAll')->checkbox(); ?>
        </div>
        <div class="form-group">
            <?= Html::saveButton() ?>
            <?= Html::a(Yii::t('base', 'Back'), $prevPage, ['class' => 'btn btn-default pull-right']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
