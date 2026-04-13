<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/compte/profil', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, CartService $cart): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dob = $user->getDob();
            if (!$dob || $this->isNotOfAge($dob)) {
                $form->addError(new FormError('Mise à jour refusée : vous devez être majeur (18+).'));
            } else {
                $em->flush();
                $this->addFlash('success', 'Profil mis à jour.');
                return $this->redirectToRoute('profile_edit');
            }
        }

        return $this->render('profile/edit.html.twig', [
            'pageKey' => 'profile',
            'profileForm' => $form,
            'cartCount' => $cart->count(),
        ]);
    }

    private function isNotOfAge(\DateTimeImmutable $dob): bool
    {
        $now = new \DateTimeImmutable('now');
        $age = $now->diff($dob)->y;
        return $age < 18;
    }
}

