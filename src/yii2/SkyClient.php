<?php
namespace sky\client\yii2\oauth;

use yii\httpclient\Client;

class SkyClient extends Client
{
    public $baseUrl = 'http://api.iweb.dev.id';
    public $cred = [
        'client_id' => null,
        'client_secret' => null,
    ];

    public $grant_type = 'client_credentials';

    public $sessionName = '__SKYclientJwT__';

    public $requestConfig = [
        'class' => Request::class,
    ];

    public $responseConfig = [
        'class' => Response::class,
    ];

    public function apiPrivate($url, $method = 'get')
    {
        if (!$this->getToken()) {
            $this->requestToken();
        }
        return $this->createRequest()
            ->setUrl($url)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->getToken(),
            ])
            ->setMethod($method);
    }

    public function getAccessToken()
    {
        $request = $this->createRequest()
            ->setMethod('post')
            ->addData(array_merge(['grant_type' => $this->grant_type ], $this->cred))
            ->setUrl('auth/get-access-token')
            ->send();
        if ($request->isOk) {
            $this->setToken($request->data['access_token']);
        }
        return $request->statusCode;
    }

    public function setToken($token)
    {
        $_SESSION[$this->sessionName] = $token;
    }

    public function getToken()
    {
        return $_SESSION[$this->sessionName] ?? null;
    }

    public function getIdentity($data = [])
    {
        return $this->apiPrivate('account/me/identity')
            ->setData($data)
            ->send();
    }
}
