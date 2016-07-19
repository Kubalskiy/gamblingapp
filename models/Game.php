<?php
/**
 * Created by PhpStorm.
 * User: stasi
 * Date: 19.12.2015
 * Time: 22:52
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use \app\models\GameQueue;
use \app\models\GameLog;
use \app\models\Profile;

class Game extends ActiveRecord
{
    public function addToQueue($userid, $bet, $currency, $isGuest)
    {
        $insert = new GameQueue();
        $insert->bet = $bet;
        $insert->userId = $userid;
        $insert->isGuest = $isGuest;
        $insert->currency = $currency;
        if (!$insert->save())
            die($insert->getErrors());

        return $insert->id;
    }

    public function findOpponent($bet, $currency = 'credit')
    {
        if (\Yii::$app->user->isGuest) {
            $userId = $this->getSesUserId();
        } else {
            $userId = \Yii::$app->user->id;
        }
        $opponent = GameQueue::find()
            ->where("currency LIKE '$currency' AND userId != $userId AND finished = 0 AND logId IS NULL")
            ->orderBy(['id' => 'ASC'])
            ->one();

        return $opponent;
    }

    public function savePlayedGame($yourBet, $oppBet, $opponentUserId, $youDrop, $oppDrop, $winner, $isYouGuest, $isOppGuest)
    {
        if (\Yii::$app->user->isGuest) {
            $userId = $this->getSesUserId();
        } else {
            $userId = \Yii::$app->user->id;
        }

        $insert = new GameLog();
        $insert->user1Id = $userId;
        $insert->betUser1 = $yourBet;
        $insert->user2Id = $opponentUserId;
        $insert->dropUser1 = $youDrop;
        $insert->isUser1Guest = $isYouGuest;
        $insert->betUser2 = $oppBet;
        $insert->dropUser2 = $oppDrop;
        $insert->isUser2Guest = $isOppGuest;
        $insert->winner = $winner;
        if (!$insert->save())
            die($insert->getErrors());

        return $insert->id;
    }

    public function getUserGame()
    {
        if (\Yii::$app->user->isGuest) {
            $userId = $this->getSesUserId();
            $isGuest = 1;
        } else {
            $userId = \Yii::$app->user->id;
            $isGuest = 0;
        }
        $existGame = GameQueue::find()
            ->where(['userId' => $userId, 'finished' => 0, 'currency' => 'credit', 'isGuest' => $isGuest])
            ->one();
        return $existGame;
    }

    public function getSesUserId()
    {
        $session = \Yii::$app->session;

        if ($id = $session->get('sesUserId', false))
            return $id;

        $query = new \yii\db\Query;
        $result = $query->from('session')
            ->where(['id' => $session->id])
            ->one();

        $session->set('sesUserId', $result['itemid']);

        return $result['itemid'];
    }

    public function getUserGameByGameId($id)
    {
        return GameQueue::findOne($id);
    }

    public function getUserCredits($userId)
    {
        if (\Yii::$app->user->isGuest) {
            return \Yii::$app->session->get('credits', 10);
        }

        $credits = Profile::find()
            ->select('credits')
            ->where(['user_id' => $userId])
            ->column();
        return $credits[0];
    }

    public function getUserName($userId, $isGuest)
    {
        if ($isGuest) {
            return 'Guest' . $userId;
        }

        $username = Profile::find()
            ->select('name')
            ->where(['user_id' => $userId])
            ->column();

        return $username[0];
    }

    public function updateProfile($userId, $updateData)
    {
        $profile = Profile::findOne($userId);

        foreach ($updateData as $key => $val)
            $profile->{$key} = $val;

        $profile->save();

    }

    public function updateQueue($id, $updateData)
    {
        $queue = GameQueue::findOne($id);
        foreach ($updateData as $key => $val)
            $queue->{$key} = $val;
        $queue->save();
    }

    public function getGameLogByLogId($logId)
    {
        return GameLog::findOne($logId);
    }

    public static function getUserProfile($userId)
    {
        return Profile::findOne($userId);
    }

}