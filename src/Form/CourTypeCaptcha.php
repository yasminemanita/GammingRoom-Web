<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Cour;
use App\Entity\Membre;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use MyCompany\MyBundle\Form\Type\VehicleEngineType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CourTypeCaptcha extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lienyoutube')
            ->add('nomcours')
            ->add('description')
            ->add('tags')
            ->add('nbParticipant')
            ->add('dateCreation')
            ->add('imagecours', FileType::class, array('data_class' => null), [
                'label' => 'Image',


                // unmapped means that this field is not associated to any entity property
                'mapped' => true,


                'required' => false,
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
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'Categorie',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('membre', EntityType::class, [
                'class' => Membre::class,
                'choice_label' => 'Membre',
                'multiple' => false,
                'expanded' => false

            ])->add('captcha', CaptchaType::class); // That's all !;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cour::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'cour_new';
    }
}
