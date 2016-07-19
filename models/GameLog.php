<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game_log".
 *
 * @property integer $id
 * @property integer $user1Id
 * @property integer $betUser1
 * @property integer $user2Id
 * @property integer $dropUser1
 * @property integer $betUser2
 * @property integer $dropUser2
 * @property integer $winner
 */
class GameLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user1Id', 'betUser1', 'user2Id', 'dropUser1', 'betUser2', 'dropUser2', 'winner'], 'required'],
            [['user1Id', 'betUser1', 'user2Id', 'dropUser1', 'betUser2', 'dropUser2', 'winner', 'isUser1Guest', 'isUser2Guest'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user1Id' => 'User1 ID',
            'betUser1' => 'Bet User1',
            'user2Id' => 'User2 ID',
            'dropUser1' => 'Drop User1',
            'betUser2' => 'Bet User2',
            'dropUser2' => 'Drop User2',
            'winner' => 'Winner',
            'isUser1Guest' => 'is User1 Guest',
            'isUser2Guest' => 'is User2 Guest',
        ];
    }
}
