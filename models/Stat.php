<?php
/**
 * Created by PhpStorm.
 * User: stasi
 * Date: 28.12.2015
 * Time: 14:32
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\mysql\QueryBuilder;
use yii\db\Query;
use yii\web\Session;

class Stat  extends ActiveRecord
{
    public static function getActiveUsersCount()
    {
        return Profile::find()
            -> where(['>', 'lastActivity', time()-5*60])
            -> count();
    }

    public static function getOnlineUsers()
    {
        $query = new \yii\db\Query;
        return $query->from('profile p')
        ->select('*, (select count(*) from game_log g where (g.user1Id=p.user_id and g.isUser1Guest=0) or (g.user2Id=p.user_id and g.isUser2Guest=0)) as count_')
        ->where(['>', 'lastActivity', time()-5*60])
        ->orderBy('p.name')
        ->all();
    }
}