<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\CartService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        CartService $cart,
        UserRepository $userRepository
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('shop_all');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dob = $user->getDob();
            if (!$dob || $this->isNotOfAge($dob)) {
                $form->addError(new FormError('Inscription refusée : vous devez être majeur (18+).'));
            } else {
                $existing = $userRepository->findOneBy(['email' => $user->getEmail()]);
                if ($existing) {
                    $form->addError(new FormError('Cet email est déjà utilisé. Connecte-toi ou utilise un autre email.'));
                    return $this->render('registration/register.html.twig', [
                        'pageKey' => 'register',
                        'registrationForm' => $form,
                        'cartCount' => $cart->count(),
                    ]);
                }

                $plainPassword = (string) $form->get('plainPassword')->getData();
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
                try {
                    $em->persist($user);
                    $em->flush();
                } catch (UniqueConstraintViolationException) {
                    $form->addError(new FormError('Cet email est déjà utilisé. Connecte-toi ou utilise un autre email.'));
                    return $this->render('registration/register.html.twig', [
                        'pageKey' => 'register',
                        'registrationForm' => $form,
                        'cartCount' => $cart->count(),
                    ]);
                }

                $this->addFlash('success', 'Compte créé. Connecte-toi pour commander.');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('registration/register.html.twig', [
            'pageKey' => 'register',
            'registrationForm' => $form,
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
