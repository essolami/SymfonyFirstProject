<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription",name="security_registration")
     */
  public function registration(HttpFoundationRequest $request , ManagerRegistry $manager , UserPasswordEncoderInterface $encoder){
    $user = new User();
    $form = $this->createForm(RegistrationType::class,$user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $hash = $encoder->encodePassword($user , $user->getPassword());
        $user->setPassword($hash);
        $manager->getManager()->persist($user);
        $manager->getManager()->flush();
        return $this->redirectToRoute('home');
    }
    return $this->render('security/registration.html.twig',['form'=> $form->createView()]);
  }
}
