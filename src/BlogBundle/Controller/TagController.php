<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use BlogBundle\Entity\Tag;
use BlogBundle\Form\TagType;

class TagController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tag_repo = $em->getRepository('BlogBundle:Tag');
        $tags = $tag_repo->findAll();

        return $this->render('BlogBundle:Tag:index.html.twig', array(
                "tags" => $tags
            ));
    }

    public function addAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if($form->isValid()) {

                $em = $this->getDoctrine()->getEntityManager();

                $tag = new Tag();
                $tag->setName($form->get("name")->getData());
                $tag->setDescription($form->get("description")->getData());

                $em->persist($tag);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "La etiqueta se ha creado correctamente";
                } else {
                    $status = "Error a añadir la etiqueta!!";
                }
                
            } else {
                $status = "La etiqueta no se ha creado";
            }

            $this->session->getFlashBag()->add("status",$status);
            return $this->redirectToRoute("blog_index_tag");
        }

        return $this->render('BlogBundle:Tag:add.html.twig', array(
                "form" => $form->createView()
            ));
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $tag_repo = $em->getRepository('BlogBundle:Tag');
        $tag = $tag_repo->find($id);
        $em->remove($tag);
        $em->flush();

        return $this->redirectToRoute("blog_index_tag");
    }
}
