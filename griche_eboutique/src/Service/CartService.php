<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private const KEY = 'cart_v1';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ProductRepository $productRepository,
    ) {
    }

    public function getItems(): array
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get(self::KEY, []);
        return is_array($cart) ? $cart : [];
    }

    public function count(): int
    {
        return array_sum(array_map(static fn ($q) => (int) $q, $this->getItems()));
    }

    public function add(int $productId, int $qty = 1): void
    {
        $qty = max(1, $qty);
        $cart = $this->getItems();
        $cart[$productId] = ($cart[$productId] ?? 0) + $qty;
        $this->requestStack->getSession()->set(self::KEY, $cart);
    }

    public function setQty(int $productId, int $qty): void
    {
        $cart = $this->getItems();
        if ($qty <= 0) unset($cart[$productId]);
        else $cart[$productId] = $qty;
        $this->requestStack->getSession()->set(self::KEY, $cart);
    }

    public function clear(): void
    {
        $this->requestStack->getSession()->remove(self::KEY);
    }

    public function shippingCents(int $subtotalCents): int
    {
        if ($subtotalCents >= 8000) return 0;
        return 499;
    }

    public function getDetailed(): array
    {
        $cart = $this->getItems();
        $lines = [];
        $subtotal = 0;

        foreach ($cart as $productId => $qty) {
            $product = $this->productRepository->find((int) $productId);
            if (!$product) continue;
            $qty = max(1, (int) $qty);
            $lineTotal = $product->getPriceCents() * $qty;
            $subtotal += $lineTotal;
            $lines[] = [
                'product' => $product,
                'qty' => $qty,
                'unitCents' => $product->getPriceCents(),
                'lineCents' => $lineTotal,
            ];
        }

        $shipping = $this->shippingCents($subtotal);
        return [
            'lines' => $lines,
            'subtotalCents' => $subtotal,
            'shippingCents' => $shipping,
            'totalCents' => $subtotal + $shipping,
        ];
    }
}

