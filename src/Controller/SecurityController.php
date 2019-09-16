<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration( Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
           
            
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $file = $user->getPicture();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('picture_directory'), $fileName);
             

            $user->setPassword($hash)
                 ->setPicture($fileName);
                 
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig', [
                       'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */

     public function login(){
         return $this->render('security/login.html.twig');
     }

     /**
      * @Route("/deconnexion", name="security_logout")
      */
        public function logout(){}

}