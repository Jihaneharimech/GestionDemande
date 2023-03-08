<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilePasswordController extends AbstractController
{
    #[Route('/profil/modifier-mot-de-passe', name: 'app_profile_password')]
    public function index(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager ): Response
    {
        $notification= null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class,$user);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $old_password = $form->get('old_password')->getData();
        
            if( $userPasswordHasher->isPasswordValid($user, $old_password))
            { 
                 // encode the password
                $user->setPassword($userPasswordHasher->hashPassword( $user, $form->get('new_password')->getData()));

                $entityManager->persist($user);
                $entityManager->flush();
                $notification= 'Votre mot de passe a ete bien mis à jour';
            }else {
                $notification = 'Le mot de passe actual ne pas le bon';
            }

        }
        return $this->render('profile/password.html.twig',[
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}

