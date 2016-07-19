<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use \app\models\Stat;
use \app\data\OnlineArrayProvider;

class StatController extends Controller
{
    public function actionOnline()
    {
        if (!\Yii::$app->user->isGuest) {
            $game = new \app\models\Game();
            $game->updateProfile(\Yii::$app->user->id, ['lastActivity' => time()]);
        }
        $dataProvider = new OnlineArrayProvider();
        return $this->render('online', ['dataProvider' => $dataProvider]);
    }

    public function actionTop()
    {
        return $this->render('top');
    }
}