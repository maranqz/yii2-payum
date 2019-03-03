<?php


namespace yii\payum\widgets\redirect;

use Yii;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Reply\HttpResponse;
use yii\base\Widget;

class RedirectWidget extends Widget
{
    const REDIRECT_MESSAGE = 'Redirecting to payment page...';

    /**
     * @var HttpResponse|HttpRedirect|HttpPostRedirect
     */
    public $response;

    public $redirectMessage;

    public function init()
    {
        if (empty($this->response)) {
            throw new \InvalidArgumentException(sprintf('Property "response" should be set'));
        }

        if (empty($this->redirectMessage)) {
            $this->redirectMessage = Yii::t(
                'yii/payum',
                self::REDIRECT_MESSAGE
            );
        }

        parent::init();
    }

    public function run()
    {
        $result = null;
        if ($this->response instanceof HttpPostRedirect) {
            $result = $this->renderPost($this->response);
        } else {
            $result = $this->renderGet($this->response);
        }

        return $result;
    }

    private function renderGet(HttpRedirect $response)
    {
        $this->view->registerJs(
            sprintf('document.location.href="%s"', $response->getUrl()),
            $this->view::POS_READY
        );
    }

    private function renderPost(HttpPostRedirect $response)
    {
        $formInputs = '';
        foreach ($response->getFields() as $name => $value) {
            $formInputs .= sprintf(
                    '<input type="hidden" name="%1$s" value="%2$s" />',
                    htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
                ) . "\n";
        }

        $content = <<<'HTML'
    <form action="%1$s" method="post" id="%4$s">
        <p>%3$s</p>
        <p>%2$s</p>
    </form>
HTML;
        $this->view->registerJs(
            sprintf('$("#%s").submit();', $this->getId()),
            $this->view::POS_READY
        );

        return sprintf(
            $content,
            htmlspecialchars($response->getUrl(), ENT_QUOTES, 'UTF-8'),
            $formInputs,
            $this->redirectMessage,
            $this->getId()
        );
    }
}
