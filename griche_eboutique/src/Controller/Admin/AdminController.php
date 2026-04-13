<?php

namespace App\Controller\Admin;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_home', methods: ['GET'])]
    public function index(CartService $cart): Response
    {
        return $this->render('admin/index.html.twig', [
            'pageKey' => 'admin',
            'cartCount' => $cart->count(),
        ]);
    }
}

