<?php
namespace sky\client\yii2\oauth;

use yii\httpclient\Client;

class SkyClient extends Client
{
    public $baseUrl = 'http://api.iweb.dev.id';
    public $email;
    public $user = [
        'email' => 'admin@web.com',
        'password' => 'admin123',
    ];
    private $_token;

    public $sessionName = '__SKYclientJwT__';

    public $requestConfig = [
        'class' => Request::class,
    ];

    public $responseConfig = [
        'class' => Response::class,
    ];

    public function createRequestPrivate($url, $method = 'get')
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

    public function requestToken()
    {
        $request = $this->createRequest()
            ->setMethod('post')
            ->addData($this->user)
            ->setUrl('account/auth/login')
            ->send();
        if ($request->isOk) {
            $this->setToken($request->data['token']);
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
        return $this->createRequestPrivate('account/me/identity')
            ->setData($data)
            ->send();
    }
}
