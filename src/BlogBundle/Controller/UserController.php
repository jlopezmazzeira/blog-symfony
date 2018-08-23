<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use BlogBundle\Entity\User;
use BlogBundle\Form\UserType;

class UserController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function loginAction(Request $request)
    {
    	$authenticationUtils = $this->get("security.authentication_utils");
    	$error = $authenticationUtils->getLastAuthenticationError();
    	$lastUsername = $authenticationUtils->getLastUsername();

        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->issubmitted()){
            if($form->isValid()){
                $em = $this->getDoctrine()->getEntityManager();
                $user_repo = $em->getRepository("BlogBundle:User");
                $user = $user_repo->findOneBy(array("email" => $form->get('email')->getData()));
                if (count($user) == 0) {
                    $user = new User();
                    $user->setName($form->get('name')->getData());
                    $user->setSurname($form->get('surname')->getData());
                    $user->setEmail($form->get('email')->getData());

                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($form->get("password")->getData(), $user->getSalt());

                    $user->setPassword($password);
                    $user->setRole('ROLE_USER');
                    $user->setImage(null);

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($user);
                    $flush = $em->flush();
                    if ($flush == null) {
                        $status = "El usuario se ha creado exitosamente";
                    } else {
                        $status = "No te haz registrado correctamente!";
                    }
                } else {
                    $status = "El usuario ya existe";
                }
            } else {
                $status = "No te haz registrado correctamente!";
            }

            $this->session->getFlashBag()->add("status",$status);
        }
    	return $this->render("BlogBundle:User:login.html.twig", array(
    			"error" => $error,
    			"last_username" => $lastUsername,
                "form" => $form->createView()
    		));
    }
}
