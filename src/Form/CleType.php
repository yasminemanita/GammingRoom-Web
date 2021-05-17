<?php

namespace App\Form;

use App\Entity\Cle;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('produit',EntityType::class,[
                'class' => Produit::class,
                // uses the User.username property as the visible option string
                'choice_label' => 'libelle',
            ])
            ->add('number',TextType::class, array(
                "mapped" => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cle::class,
        ]);
    }
}
