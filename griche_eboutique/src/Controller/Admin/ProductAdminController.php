<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/produits')]
class ProductAdminController extends AbstractController
{
    #[Route('', name: 'admin_products', methods: ['GET'])]
    public function index(ProductRepository $repo, CartService $cart): Response
    {
        return $this->render('admin/products/index.html.twig', [
            'pageKey' => 'admin',
            'products' => $repo->findBy([], ['createdAt' => 'DESC']),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/new', name: 'admin_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, Slug $slugger, CartService $cart): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slugify($product->getName()));
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Produit créé.');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/form.html.twig', [
            'pageKey' => 'admin',
            'title' => 'Nouveau produit',
            'form' => $form,
            'back' => $this->generateUrl('admin_products'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_products_edit', methods: ['GET', 'POST'])]
    public function edit(Product $product, Request $request, EntityManagerInterface $em, Slug $slugger, CartService $cart): Response
    {
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slugify($product->getName()));
            $em->flush();
            $this->addFlash('success', 'Produit mis à jour.');
            return $this->redirectToRoute('admin_products');
        }

        return $this->render('admin/form.html.twig', [
            'pageKey' => 'admin',
            'title' => 'Modifier produit',
            'form' => $form,
            'back' => $this->generateUrl('admin_products'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_products_delete', methods: ['POST'])]
    public function delete(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_product_'.$product->getId(), (string) $request->request->get('_token'))) {
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Produit supprimé.');
        }
        return $this->redirectToRoute('admin_products');
    }
}

