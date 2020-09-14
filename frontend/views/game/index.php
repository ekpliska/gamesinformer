<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GamePlatformRelease;
use kartik\select2\Select2;

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
        
        <?php if (Yii::$app->session->hasFlash('success')) : ?>
            <?php $message = Yii::$app->session->getFlash('success')['message'] ?>
            <div class="alert-message" data-notification-status="success">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('error')) : ?>
            <?php $message = Yii::$app->session->getFlash('error')['message'] ?>
            <div class="alert-message" data-notification-status="error">
                <?= $message ?>
            </div>
        <?php endif; ?>
    
        <div class="col-md-7">
            
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'release_date')->textInput(['type' => 'date']); ?>
           
            <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'youtube')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'youtube_btnlink')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'twitch')->textInput(['maxlength' => true]) ?>
            <?php // $form->field($model, 'series')->textInput(['maxlength' => true]) ?>
            <?=
                $form->field($model, 'series_id')->widget(Select2::classname(), [
                    'data' => $series,
                    'model' => $model,
                    'options' => [
                        'placeholder' => 'Выберите серию из списка ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);
            ?>
            
            <?= $form->field($model, 'description')->textarea(['rows' => 15]) ?>
            <?= $form->field($model, 'published')->checkbox() ?>
            
            <?= $form->field($model, 'is_aaa')->checkbox() ?>
            
            <?= $form->field($model, 'publish_at')->textInput(['type' => 'date']); ?>
            
            <div class="alert alert-warning">
                <strong>Внимание!</strong>
                Для использования в качестве обложки превью из YouTube ролика обязательно указывайте полную ссылку 
                содержащую <code>watch?v=<КОД_ВИДЕО></code>.
                <br />
                Например: <code>https://www.youtube.com/watch?v=BODEmT7OKWg</code>
            </div>
            
            <?= $form->field($model, 'cover_file')->fileInput(['accept' => 'image/*']) ?>
            
        </div>
    
        <div class="col-md-5">
            <div class="panel panel-info">
                <div class="panel-heading">Платформы</div>
                <div class="panel-body">
                    
                    <?php $platform = new GamePlatformRelease(); ?>
                    <table id="platforms-list" class="table">
                        <tbody>
                            <?php // Формируем поле для добавления платформы ?>
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
                            <?php // Поля для новой платформы  ?>
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
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">Жанры</div>
                <div class="panel-body">
                    <?=
                        $form->field($model, 'genres_list')
                            ->checkboxList($genres, ['id' => 'id',
                                'item' => function($index, $label, $name, $checked, $value) {
                                    $return = '<input type="checkbox" name="' . $name . '" value="' . $value . '" id="' . $index . '">';
                                    $return .= '<label class="input-checkbox">' . ucwords($label);
                                    $return .= '</label><br />';
                                    return $return;
                                }]
                            )
                            ->label(false);
                    ?>
                </div>
            </div>

        </div>
        
        <?php ob_start(); // включаем буферизацию для js ?>
        <script>
            // Добавление платформы
            var platform_k = <?php echo isset($key) ? str_replace('new', '', $key) : 0; ?>;
            $('#platform-new-button').on('click', function () {
                platform_k += 1;
                
                $('#platforms-list').find('tbody')
                    .append('<tr>' + $('#platform-new-item-block').html().replace(/__id__/g, 'new' + platform_k) + '</tr>');
            });
            
            // Удаление платформы
            var elemQiestion;
            $(document).on('click', '.remove-platform-button', function () {
                elemQiestion = $(this).closest('tbody tr');
                elemQiestion.remove();
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