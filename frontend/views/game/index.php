<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use common\models\GamePlatformRelease;

$this->title = 'Новая публикация';
$this->params['breadcrumbs'][] = ['label' => 'Игры', 'url' => ['site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="game-create">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'form-game',
            'enableClientValidation' => false,
            'options' => [
                'enctype' => 'multipart/form-data',
        ],
    ]); ?>
    
    <div class="row">
    
        <div class="col-md-7">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?=
                $form->field($model, 'release_date', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                    ->widget(DateTimePicker::className(), [
                        'id' => 'release_date',
                        'language' => 'ru',
                        'value' => date('yyyy-mm-dd hh:ii'),
                        'options' => [
                            'placeholder' => 'ГГГГ-ММ-ДД ЧЧ:ММ',
                        ],
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd hh:ii',
                    ]])->label($model->getAttributeLabel('release_date'), ['class' => 'date-label'])
            ?>
            <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'youtube')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'youtube_btnlink')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'twitch')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'series')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
            <?=
                $form->field($model, 'published')->checkbox([
                    'template' => '<div class="col-md-1">{label}</div><div class="col-md-5">{input}</div><div class="col-md-6">{error}</div>'
                ])
            ?>
            <?=
                $form->field($model, 'publish_at', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
                    ->widget(DateTimePicker::className(), [
                        'id' => 'publish_at',
                        'language' => 'ru',
                        'value' => date('yyyy-mm-dd hh:ii'),
                        'options' => [
                            'placeholder' => 'ГГГГ-ММ-ДД ЧЧ:ММ',
                        ],
                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd hh:ii',
                    ]])->label($model->getAttributeLabel('publish_at'), ['class' => 'date-label'])
            ?>
            <?= $form->field($model, 'cover')->textInput(['maxlength' => true]) ?>
        </div>
    
        <div class="col-md-5">
            <h5>Платформы</h5>
                <?php $platform = new GamePlatformRelease(); ?>
                    <table id="platforms-list" class="table">
                        <tbody>
                            <?php // Формируем поле для ввода вопроса для текущего голосования ?>
                            <?php foreach ($model->gamePlatformReleases as $key => $_platform) : ?>
                                <tr>
                                    <?=
                                        $this->render('new_platform', [
                                            'key' => $_platform->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $_question->id,
                                            'form' => $form,
                                            'platform' => $_platform,
                                            'platforms' => $platforms
                                        ])
                                    ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php // Поля для нового вопроса  ?>
                            <tr id="platform-new-item-block" style="display: none;">
                                <?=
                                    $this->render('new_platform', [
                                        'key' => '__id__',
                                        'form' => $form,
                                        'platform' => $platform,
                                        'platforms' => $platforms
                                    ])
                                ?>
                            </tr>
                        </tbody>
                    </table>
                    <?=
                        Html::a('Добавить платформу', 'javascript:void(0);', [
                            'id' => 'platform-new-button',
                            'class' => 'add-platform-btn'
                        ])
                    ?>
            <h5>Жанры</h5>

        </div>
        
        <?php ob_start(); // включаем буферизацию для js ?>
        <script>
            // Добавление кнопки нового вопроса
            var platform_k = <?php echo isset($key) ? str_replace('new', '', $key) : 0; ?>;
            $('#platform-new-button').on('click', function () {
                platform_k += 1;
                console.log(platform_k);
                $('#platforms-list').find('tbody')
                    .append('<tr>' + $('#platform-new-item-block').html().replace(/__id__/g, 'new' + platform_k) + '</tr>');
                });
                <?php
                    if (!Yii::$app->request->isPost && $model->isNewRecord) {
                        echo "$('#platform-new-button').click();";
                    }
            ?>
        </script>

        <?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean())); ?>

    </div>

    <div class="row">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>