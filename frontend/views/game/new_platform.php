<?php

use yii\helpers\Html;

?>

<td width="40%" class="text-question">
    <?=
        $form->field($platform, 'platform_id')
            ->dropDownList($platforms, ['prompt' => 'Выбрать...', 'name' => "GamePlatformRelease[$key][platform_id]"])
            ->label(false);
    ?>
</td>
<td width="50%" class="text-question">
    <?= 
        $form->field($platform, 'date_platform_release')
            ->textInput([
                'type' => 'date', 
                'name' => "GamePlatformRelease[$key][date_platform_release]",
                'value' => $platform->date_platform_release ? date('Y-m-d', strtotime($platform->date_platform_release)) : ''
            ])
            ->label(false);
    ?>
</td>
<td width="10%" class="delete-question">
    <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', ['class' => 'remove-platform-button']) ?>
</td>