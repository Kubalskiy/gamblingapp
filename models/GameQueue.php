<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game_queue".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $bet
 * @property string $currency
 * @property integer $finished
 * @property integer $logId
 */
class GameQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'bet'], 'required'],
            [['userId', 'isGuest', 'bet', 'finished', 'logId'], 'integer'],
            [['currency'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'bet' => 'Bet',
            'isGuest' => 'isGuest',
            'currency' => 'Currency',
            'finished' => 'Finished',
            'logId' => 'Log ID',
        ];
    }
}
