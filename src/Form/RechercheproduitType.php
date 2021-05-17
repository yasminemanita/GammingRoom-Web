<?php

namespace App\Form;

use App\Entity\RechercheProd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheproduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('desc',null,['label' => false,
                'attr' => ['requied' => false,
                    'placeholder' => 'Entrer le libelle d\'un produit'] ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RechercheProd::class,
        ]);
    }
}
