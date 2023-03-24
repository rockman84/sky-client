<?php
namespace sky\client\yii2;

class Request extends \yii\httpclient\Request
{
    /**
     * @return Response
     * @throws \yii\httpclient\Exception
     */
    public function send()
    {
        /* @var $response \sky\client\Response */
        $response = parent::send();
//        if (!$response->isOk && !$response->getIsDataValidationFail()) {
//            $this->client->setToken(null);
//            throw new \Exception($response->getDataValue('message', 'Unknown Error'), $response->statusCode);
//        }
        return $response;
    }
}