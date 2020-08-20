<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Комментарии к играм';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comments-index">
    <h3>
        Комментарии к играм 
    </h3>
    
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Игры
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            Game 1
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    Comments list
                </div>
            </div>
        </div>
    </div>
</div>