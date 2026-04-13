<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Service\CartService;
use App\Service\Slug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categories')]
class CategoryAdminController extends AbstractController
{
    #[Route('', name: 'admin_categories', methods: ['GET'])]
    public function index(CategoryRepository $repo, CartService $cart): Response
    {
        return $this->render('admin/categories/index.html.twig', [
            'pageKey' => 'admin',
            'categories' => $repo->findBy([], ['name' => 'ASC']),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/new', name: 'admin_categories_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, Slug $slugger, CartService $cart): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($slugger->slugify($category->getName()));
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie créée.');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/form.html.twig', [
            'pageKey' => 'admin',
            'title' => 'Nouvelle catégorie',
            'form' => $form,
            'back' => $this->generateUrl('admin_categories'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $em, Slug $slugger, CartService $cart): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($slugger->slugify($category->getName()));
            $em->flush();
            $this->addFlash('success', 'Catégorie mise à jour.');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/form.html.twig', [
            'pageKey' => 'admin',
            'title' => 'Modifier catégorie',
            'form' => $form,
            'back' => $this->generateUrl('admin_categories'),
            'cartCount' => $cart->count(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_categories_delete', methods: ['POST'])]
    public function delete(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_category_'.$category->getId(), (string) $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée.');
        }
        return $this->redirectToRoute('admin_categories');
    }
}

