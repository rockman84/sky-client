<?php
namespace sky\client\yii2\oauth;

use Yii;
use yii\web\NotFoundHttpException;

class AuthAction extends \yii\authclient\AuthAction
{
    public $clientCollection = 'authClient';

    public $defaultClientId = 'sky';

    public $clientIdGetParamName = 'authclient';

    public function run()
    {
        $clientId = $this->defaultClientId;
        if (!empty($clientId)) {
            /* @var $module \sky\node\Module */
            $module = Yii::$app->controller->module;

            /* @var $collection \yii\authclient\Collection */
            $collection = $module->get($this->clientCollection);
            if (!$collection->hasClient($clientId)) {
                throw new NotFoundHttpException("Unknown auth client '{$clientId}'");
            }
            $client = $collection->getClient($clientId);

            return $this->auth($client);
        }

        throw new NotFoundHttpException();
    }
}