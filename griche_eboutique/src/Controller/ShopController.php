<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    #[Route('/', name: 'shop_all', methods: ['GET'])]
    public function all(ProductRepository $products, CartService $cart): Response
    {
        return $this->render('shop/list.html.twig', [
            'pageKey' => 'all',
            'pageTitle' => 'Tous les jeux',
            'products' => $products->findByCategorySlug('jeux'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/consoles', name: 'shop_consoles', methods: ['GET'])]
    public function consoles(ProductRepository $products, CartService $cart): Response
    {
        return $this->render('shop/list.html.twig', [
            'pageKey' => 'consoles',
            'pageTitle' => 'Consoles',
            'products' => $products->findByCategorySlug('consoles'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/offres', name: 'shop_offres', methods: ['GET'])]
    public function offres(ProductRepository $products, CartService $cart): Response
    {
        return $this->render('shop/list.html.twig', [
            'pageKey' => 'offres',
            'pageTitle' => 'Offres',
            'products' => $products->findByCategorySlug('offres'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/nouveautes', name: 'shop_new', methods: ['GET'])]
    public function newArrivals(ProductRepository $products, CartService $cart): Response
    {
        return $this->render('shop/list.html.twig', [
            'pageKey' => 'new',
            'pageTitle' => 'Nouveautés',
            'products' => $products->findNewArrivals(),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/produit/{slug}', name: 'shop_product', methods: ['GET'])]
    public function product(string $slug, ProductRepository $products, CartService $cart): Response
    {
        $product = $products->findOneBy(['slug' => $slug]);
        if (!$product) {
            throw $this->createNotFoundException();
        }

        return $this->render('shop/product.html.twig', [
            'pageKey' => 'product',
            'product' => $product,
            'cartCount' => $cart->count(),
        ]);
    }
}
