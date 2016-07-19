<?php
namespace app\data;

use \app\models\Stat;

class OnlineArrayProvider extends \yii\data\ArrayDataProvider
{
    /**
     * Initialize the dataprovider by filling allModels
     */
    public function init()
    {
        //Get all all authors with their articles
        $users = Stat::getOnlineUsers();
        foreach ($users as $user)
        {
            $this->allModels[] = [
                'name' => $user->Name,
                'games' => $user->count_,
            ];
        }
    }
}