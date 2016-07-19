<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string  $name
 * @property string  $public_email
 * @property string  $gravatar_email
 * @property string  $gravatar_id
 * @property string  $location
 * @property string  $website
 * @property string  $bio
 * @property User    $user
 * @property integer $credit
 * @property integer $cash
 *
 */
class Profile extends \dektrium\user\models\Profile
{   /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'bioString' => ['bio', 'string'],
            'publicEmailPattern' => ['public_email', 'email'],
            'gravatarEmailPattern' => ['gravatar_email', 'email'],
            'websiteUrl' => ['website', 'url'],
            'nameLength' => ['name', 'string', 'max' => 255],
            'publicEmailLength' => ['public_email', 'string', 'max' => 255],
            'gravatarEmailLength' => ['gravatar_email', 'string', 'max' => 255],
            'locationLength' => ['location', 'string', 'max' => 255],
            'websiteLength' => ['website', 'string', 'max' => 255],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'name'           => Yii::t('user', 'Name'),
            'public_email'   => Yii::t('user', 'Email (public)'),
            'gravatar_email' => Yii::t('user', 'Gravatar email'),
            'location'       => Yii::t('user', 'Location'),
            'website'        => Yii::t('user', 'Website'),
            'bio'            => Yii::t('user', 'Bio'),
            'credit'         => Yii::t('app',  'Credit'),
            'cash'           => Yii::t('app',  'Cash'),
        ];
    }

    public static function getUserSettings($userId)
    {
        $settings = Profile::findOne($userId);

        return unserialize($settings->settings);
    }
}
