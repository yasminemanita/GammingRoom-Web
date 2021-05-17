<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomevent')
            ->add('datedeb', DateType::class,[
                'empty_data' => null,
                'attr'=>['class'=>'form-controller js-datepicker'],
                'widget'=>'single_text',
                'html5'=>FALSE
            ])
            ->add('datefin', DateType::class,[
                'empty_data' => null,
                'attr'=>['class'=>'form-controller js-datepicker'],
                    'widget'=>'single_text',
                    'html5'=>FALSE
            ])
            ->add('image', FileType::class,array('data_class'=>null),[
                'label' => 'Image(jpg,png,jpeg)',

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
            ->add('nbremaxParticipant')
            ->add('description')
            ->add('lieu')
            ->add('lienyoutube')
            ->add('categorie',EntityType::class,[
                'class'=>Categorie::class,
                'choice_label'=>'nomcategorie',
                'multiple'=>false,
                'expanded'=>false
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
