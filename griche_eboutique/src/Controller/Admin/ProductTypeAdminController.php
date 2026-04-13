<?php

namespace App\Controller\Admin;

use App\Entity\ProductType;
use App\Form\ProductTypeFormType;
use App\Repository\ProductTypeRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/types')]
class ProductTypeAdminController extends AbstractController
{
    #[Route('', name: 'admin_types', methods: ['GET'])]
    public function index(ProductTypeRepository $repo, CartService $cart): Response
    {
        return $this->render('admin/types/index.html.twig', [
            'pageKey' => 'admin',
            'types' => $repo->findBy([], ['name' => 'ASC']),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/new', name: 'admin_types_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, CartService $cart): Response
    {
        $type = new ProductType();
        $form = $this->createForm(ProductTypeFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);
            $em->flush();
            $this->addFlash('success', 'Type créé.');
            return $this->redirectToRoute('admin_types');
        }

        return $this->render('admin/form.html.twig', [
            'pageKey' => 'admin',
            'title' => 'Nouveau type',
            'form' => $form,
            'back' => $this->generateUrl('admin_types'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_types_edit', methods: ['GET', 'POST'])]
    public function edit(ProductType $type, Request $request, EntityManagerInterface $em, CartService $cart): Response
    {
        $form = $this->createForm(ProductTypeFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Type mis à jour.');
            return $this->redirectToRoute('admin_types');
        }

        return $this->render('admin/form.html.twig', [
            'pageKey' => 'admin',
            'title' => 'Modifier type',
            'form' => $form,
            'back' => $this->generateUrl('admin_types'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_types_delete', methods: ['POST'])]
    public function delete(ProductType $type, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_type_'.$type->getId(), (string) $request->request->get('_token'))) {
            $em->remove($type);
            $em->flush();
            $this->addFlash('success', 'Type supprimé.');
        }
        return $this->redirectToRoute('admin_types');
    }
}

