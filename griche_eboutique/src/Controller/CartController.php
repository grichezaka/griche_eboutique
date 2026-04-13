<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'cart_index', methods: ['GET'])]
    public function index(CartService $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'pageKey' => 'cart',
            'cart' => $cart->getDetailed(),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(int $id, Request $request, CartService $cart): RedirectResponse
    {
        $qty = (int) $request->request->get('qty', 1);
        $cart->add($id, $qty);
        $this->addFlash('success', 'Ajouté au panier.');
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/panier/maj', name: 'cart_update', methods: ['POST'])]
    public function update(Request $request, CartService $cart): RedirectResponse
    {
        $items = $request->request->all('items');
        if (is_array($items)) {
            foreach ($items as $productId => $qty) {
                $cart->setQty((int) $productId, (int) $qty);
            }
        }
        $this->addFlash('success', 'Panier mis à jour.');
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/panier/vider', name: 'cart_clear', methods: ['POST'])]
    public function clear(CartService $cart): RedirectResponse
    {
        $cart->clear();
        $this->addFlash('success', 'Panier vidé.');
        return $this->redirectToRoute('cart_index');
    }
}

