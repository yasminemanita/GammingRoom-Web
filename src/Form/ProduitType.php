<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image',FileType::class, array('data_class' => null),
                [
        'label' => 'Image',


        // unmapped means that this field is not associated to any entity property
        'mapped' => true,

        'required'=>false,
        // unmapped fields can't define their validation using annotations
        // in the associated entity, so you can use the PHP constraint classes
        'constraints' => [
            new File([
                'mimeTypes' => [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                ],
                'mimeTypesMessage' => 'Image invalide : (jpg,png,jpeg)',
            ])
        ],
    ])

            ->add('libelle')
            ->add('prix')
            ->add('description')
            ->add('idCat',EntityType::class,[
                'class'=>Categorie::class,
                'choice_label'=>'Categorie',
                'multiple'=>false,
                'expanded'=>false
            ]



            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
