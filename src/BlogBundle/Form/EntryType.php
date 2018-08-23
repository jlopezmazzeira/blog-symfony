<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, 
                array(
                    "label" => "Titulo:", 
                    "attr" => array("class" => "form-name form-control")))
            ->add('content', TextareaType::class, 
                array(
                    "label" => "Contenido:", 
                    "attr" => array("class" => "form-name form-control")))
            ->add('status', ChoiceType::class, 
                array(
                    "label" => "Estado:",
                    "choices" => array("Publico" => "public", "Privado" => "private"),
                    "attr" => array("class" => "form-name form-control")))
            ->add('image', FileType::class, 
                array(
                    "label" => "Imagen:",
                    "attr" => array("class" => "form-name form-control"),
                    "data_class" => null,
                    "required" => false)
                )
            ->add('category', EntityType::class, 
                array(
                    "class" => "BlogBundle:category",
                    "label" => "Categorias:",
                    "attr" => array("class" => "form-name form-control")))
            /*->add('user', TextType::class, 
                array(
                    "label" => "Nombre:", 
                    "required" => "required", 
                    "attr" => array("class" => "form-name form-control")))*/
            ->add('tags', TextType::class, 
                array(
                    "mapped" => false,
                    "label" => "Etiquetas:",
                    "attr" => array("class" => "form-name form-control")))
            ->add('Guardar', SubmitType::class,array("attr" => array("class" => "form-submit btn btn-success")))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Entry'
        ));
    }
}
