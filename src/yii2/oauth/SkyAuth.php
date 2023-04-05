<?php
namespace sky\client\yii2\oauth;

use yii\authclient\OAuth2;
use yii\web\HttpException;
use Yii;

class SkyAuth extends OAuth2
{
    public $scope = 'basic';

    public $apiBaseUrl = 'https://api.iweb.my.id';

    public $tokenUrl = 'https://api.iweb.my.id/auth/get-access-token';

    public $authUrl = 'https://iweb.my.id/auth/oauth';

    public function initUserAttributes()
    {
        return $this->api('account/me/identity?expand=owner,profile', 'GET');
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
        return Yii::$app->session->get($this->getStateKeyPrefix() . $key);
    }

    public function setState($key, $value)
    {
        Yii::$app->session->set($this->getStateKeyPrefix() . $key, $value, 60 * 10);
    }

    public function removeState($key)
    {
        return Yii::$app->session->remove($this->getStateKeyPrefix() . $key);
    }
}