<?php


namespace App\PaymentGateway\Delivery\Controller;


use Omnipay\Omnipay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PaymentRequestController extends AbstractController
{
    public function paymentRequest(Request $request) {

        $gateway = Omnipay::create('Dotpay');

        $gateway->initialize([
            'accountId'  => 'XXX',
            'pid'        => 'XXX',
            'type'       => '4',
            'action'     => 'https://ssl.dotpay.pl/test_payment/',
            'lang'       => 'pl',
            'apiVersion' => 'dev',
            'channel'    => '0',
            'channel_groups' => 'K,T,P'
        ]);

        $params = array(
            'amount' => 12.34,
            'currency' => 'PLN',
            'description' => 'Payment test',
            'returnUrl' => 'https://www.your-domain.nl/return_here',
            'notifyUrl' => 'https://www.your-domain.nl/notify_here',
        );

        $paramsForGateway = [
            'email' => 'client@example.com',
            'control' => 'ID_ZAMOWIENIA',
        ];

        $response = $gateway
            ->purchase($params + $paramsForGateway)
            ->send();

        if ($response->isRedirect()) {
            $response->redirect();
        }
        else if ($response->isSuccessful()) {
            echo 'Succ';
            var_dump($response);
        }
        else {
            echo 'Failed';
            var_dump($response);
        }

        return $this->json([]);
    }
}
