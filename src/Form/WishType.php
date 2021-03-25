<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title",TextType::class, [
                "label" => "Titre",
                "required" => false
            ])
            ->add("description", TextareaType::class, [
                "label" => "Description",
                "required" => false
            ])
            ->add("author", TextType::class, [
                "label" => "Auteur",
                "required" => false
            ])
            ->add("categories", EntityType::class, [
                "label" => "Catégorie",
                "class" => Category::class,
                "choice_label" => "name",
                "attr" => [
                    "class" => "form-select"
                ],
                "multiple" => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
