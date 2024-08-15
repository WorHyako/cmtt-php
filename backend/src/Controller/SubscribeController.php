<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route ('/subscribe')]
class SubscribeController extends AbstractController
{
    #[Route('', name: 'app_subscribe')]
    public function index(): Response
    {
        return $this->render('subscribe/index.html.twig', [
            'controller_name' => 'SubscribeController',
        ]);
    }
}
