<?php
namespace sky\client\yii2;

use sky\yii\helpers\ArrayHelper;

class Response extends \yii\httpclient\Response
{
    public function getDataValue($key, $defaultValue = null)
    {
        return ArrayHelper::getValue($this->getData(), $key, $defaultValue);
    }

    public function getIsDataValidationFail()
    {
        return $this->statusCode == 422;
    }
}