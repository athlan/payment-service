<?php


namespace App\PaymentGateway\Delivery\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        return $this->json([
            'name' => 'payment-service',
            'status' => 'ok',
        ]);
    }
}
