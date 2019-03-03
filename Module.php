<?php

namespace yii\payum;

use Yii;
use InvalidArgumentException;
use Payum\Core\Payum;
use yii\i18n\PhpMessageSource;

class Module extends \yii\base\Module
{
    /**
     * @var Payum|callable
     */
    public $payum;

    public function init()
    {
        Yii::setAlias('@yii/payum', __DIR__);
        if (empty($this->payum)) {
            throw new InvalidArgumentException(sprintf(
                'self::$payums should be set'
            ));
        }

        $this->registerTranslations();
    }

    public function getPayum()
    {
        if (is_callable($this->payum)) {
            $this->payum = ($this->payum)();
        }

        return $this->payum;
    }

    private function registerTranslations()
    {
        Yii::$app->i18n->translations['yii/payum*'] = [
            'class' => PhpMessageSource::className(),
            'basePath' => __DIR__ . '/messages',
            'sourceLanguage' => 'en-US',
        ];
    }
}
