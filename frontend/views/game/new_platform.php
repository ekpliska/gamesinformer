<?php

use yii\helpers\Html;
use kartik\datetime\DateTimePicker;

?>
<td width="40%" class="text-question">
    <?=
        $form->field($platform, 'platform_id')
            ->dropDownList($platforms, ['name' => "GamePlatformRelease[$key][platform_id]"])
            ->label(false);
    ?>
</td>
<td width="50%" class="text-question">
    <?=
        $form->field($platform, 'date_platform_release', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
            ->widget(DateTimePicker::className(), [
                'name' => "GamePlatformRelease[$key][date_platform_release]",
                'language' => 'ru',
                'value' => date('yyyy-mm-dd hh:ii'),
                'options' => [
                    'placeholder' => 'ГГГГ-ММ-ДД',
                ],
                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoClose' => true,
                    'format' => 'yyyy-mm-dd',
        ]])->label(false)
    ?>
</td>
<td width="10%" class="delete-question">
    <?=
    Html::button('<i class="glyphicon glyphicon-trash"></i>', [
        'class' => 'voting-remove-question-button btn-delete-question',
        'data-toggle' => 'modal',
        'data-target' => '#delete_question_vote_message',
    ])
    ?>
</td>