<?php
namespace sky\client\yii2\widgets;

use Yii;

class AuthChoice extends \yii\authclient\widgets\AuthChoice
{
    public $clientCollection = 'authClient';

    protected function defaultClients()
    {
        /* @var $module \sky\node\Module */
        $module = Yii::$app->controller->module;
        /* @var $collection \yii\authclient\Collection */
        $collection = $module->get($this->clientCollection);

        return $collection->getClients();
    }
}