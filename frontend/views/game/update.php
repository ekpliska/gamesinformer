<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GamePlatformRelease;
use kartik\select2\Select2;

$this->title = 'Игра: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Игры', 'url' => ['site/index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Просмотр';

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
            
            <?= $form->field($model, 'release_date')->textInput(['type' => 'date', 'value' => date('Y-m-d', strtotime($model->release_date))]); ?>
            <?= $form->field($model, 'only_year')->checkbox() ?>
            <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'youtube')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'youtube_btnlink')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'twitch')->textInput(['maxlength' => true]) ?>
            <?php // $form->field($model, 'series')->textInput(['maxlength' => true]) ?>
            <?php $model->series_id = $model->seriesGame ? $model->seriesGame[0]->series_id : null; ?>
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
            
            <?= $form->field($model, 'publish_at')->textInput(['type' => 'date', 'value' => date('Y-m-d', strtotime($model->publish_at))]); ?>

            <?php if (!empty($model->cover)) : ?>
                <div class="text-center">
                    <img 
                        src="<?= 'http://api.gamenotificator.net' . $model->cover ?>" 
                        class="img-thumbnail" alt="avatar" 
                        onerror="this.onerror=null;this.src='<?= $model->cover ?>';">
                </div>
            <?php endif; ?>
            
            <?= $form->field($model, 'cover_file')->fileInput(['accept' => 'image/*']) ?>
            
            <?php $model->tag_list = $selected_tag_ids; ?>
            <?=
                $form->field($model, 'tag_list')->widget(Select2::classname(), [
                    'data' => $tags,
                    'options' => [
                        'placeholder' => 'Выберите тег из списка ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ],
                ]);
            ?>
            
        </div>
    
        <div class="col-md-5">
            <div class="panel panel-info">
                <div class="panel-heading">Платформы</div>
                <?php $platform = new GamePlatformRelease(); ?>
                <table id="platforms-list" class="table">
                    <tbody>
                        <?php // Формируем поле для добавления платформы ?>
                        <?php foreach ($model->gamePlatformReleases as $key => $_platform) : ?>
                            <tr>
                                <?=
                                    $this->render('new_platform', [
                                        'key' => $_platform->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $_platform->id,
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
            <div class="panel panel-info">
                <div class="panel-heading">Жанры</div>
                    <?php $lists = $model->genresList; ?>
                    <?=
                        $form->field($model, 'genres_list')
                            ->checkboxList($genres, ['id' => 'id',
                                'item' => function($index, $label, $name, $checked, $value) use ($lists) {
                                    $_checked = in_array($value, $lists) ? 'checked' : '';
                                    $return = '<input type="checkbox" name="' . $name . '" value="' . $value . '" id="' . $index . '"' . $_checked . '>';
                                    $return .= '<label class="input-checkbox">' . ucwords($label);
                                    $return .= '</label><br />';
                                    return $return;
                                }]
                            )
                            ->label(false);
                    ?>
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
        <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы дейсвительно хотите удалить выбранную публикацию?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>