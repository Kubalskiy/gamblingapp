<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use \dektrium\user\Finder;
use dektrium\user\models\LoginForm;

/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SecurityController extends \dektrium\user\controllers\SecurityController
{

    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = Yii::createObject(LoginForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            \Yii::$app->session->set('settings', \app\models\Profile::getUserSettings(\Yii::$app->user->id));
            return $this->goBack();
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return Response
     */
    public function actionLogout()
    {
        \Yii::$app->session->destroy();
        \Yii::$app->getUser()->logout();
        return $this->goHome();
    }

}
