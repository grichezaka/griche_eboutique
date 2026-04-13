<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    #[Route('/commande', name: 'checkout_index', methods: ['GET'])]
    public function index(CartService $cart): Response
    {
        $d = $cart->getDetailed();
        if (count($d['lines']) === 0) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('checkout/index.html.twig', [
            'pageKey' => 'checkout',
            'cart' => $d,
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/commande/valider', name: 'checkout_confirm', methods: ['POST'])]
    public function confirm(CartService $cart, EntityManagerInterface $em): RedirectResponse
    {
        $d = $cart->getDetailed();
        if (count($d['lines']) === 0) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $items = array_map(static function (array $line): array {
            /** @var \App\Entity\Product $p */
            $p = $line['product'];
            return [
                'productId' => $p->getId(),
                'name' => $p->getName(),
                'qty' => $line['qty'],
                'unitCents' => $line['unitCents'],
                'lineCents' => $line['lineCents'],
            ];
        }, $d['lines']);

        $order = (new Order())
            ->setUser($user)
            ->setItems($items)
            ->setSubtotalCents($d['subtotalCents'])
            ->setShippingCents($d['shippingCents'])
            ->setTotalCents($d['totalCents']);

        $em->persist($order);
        $em->flush();

        $cart->clear();
        $this->addFlash('success', 'Commande effectuée.');
        return $this->redirectToRoute('shop_all');
    }
}

