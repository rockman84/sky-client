<?php
namespace sky\client\yii2\oauth;

use yii\authclient\OAuth2;
use yii\web\HttpException;
use Yii;

class SkyAuth extends OAuth2
{
    public $scope = 'basic';

    public $apiBaseUrl = 'https://api.iweb.my.id';

    public $tokenUrl = 'https://api.iweb.my.id/account/auth/get-access-token';

    public $authUrl = 'https://iweb.my.id/auth/oauth';

    public function initUserAttributes()
    {
        return $this->api('account/me/identity', 'GET');
    }

    public function api($apiSubUrl, $method = 'GET', $data = [], $headers = [])
    {
        $request = $this->createApiRequest()
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken()->getParam('access_token'),
            ])
            ->setMethod($method)
            ->setUrl($apiSubUrl)
            ->addHeaders($headers);

        if (!empty($data)) {
            if (is_array($data)) {
                $request->setData($data);
            } else {
                $request->setContent($data);
            }
        }

        return $this->sendRequest($request);
    }

    public function getState($key)
    {
        return Yii::$app->cache->get($this->getStateKeyPrefix() . $key);
    }

    public function setState($key, $value)
    {
        return Yii::$app->cache->set($this->getStateKeyPrefix() . $key, $value, 1300);
    }

    public function removeState($key)
    {
        return Yii::$app->cache->delete($this->getStateKeyPrefix() . $key);
    }
}