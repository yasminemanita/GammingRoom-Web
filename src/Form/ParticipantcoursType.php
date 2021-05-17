<?php

namespace App\Form;

use App\Entity\Cour;
use App\Entity\Membre;
use App\Entity\Participantcours;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantcoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('cour',EntityType::class,[
                'class'=>Cour::class,
                'choice_label'=>'Cours',
                'multiple'=>false,
                'expanded'=>false

            ])
            ->add('membre',EntityType::class,[
                'class'=>Membre::class,
                'choice_label'=>'Membre',
                'multiple'=>false,
                'expanded'=>false

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participantcours::class,
        ]);
    }
}
