<?php
/**
 * Created by PhpStorm.
 * User: stasi
 * Date: 28.12.2015
 * Time: 14:24
 */

$this->title = \Yii::t('app','Random game Online');
?>
<script>var isGuest = <?=\Yii::$app->user->isGuest?></script>
<div class="jumbotron">
    <h1><?=\Yii::t('app', 'Hello friend!')?></h1>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name:text:'.\Yii::t('app', 'Username'),
            'games:text:'.\Yii::t('app', 'Total Played'),
        ],
    ]); ?>
</div>