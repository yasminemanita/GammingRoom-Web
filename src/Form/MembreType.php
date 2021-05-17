<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class MembreType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $membre = new Membre();
        $builder
            ->add('nom',TextType::class)
            ->add('prenom',TextType::class)
            ->add('dateNaissance',DateType::class,[
                'attr' =>['class' => 'form-control js-datepicker'],
                'label' => 'Date de naissance',
                'required' => FALSE,
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text'

            ])
            ->add('genre',ChoiceType::class,[
                'choices'  => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme'
                ],
            ])
            ->add('numeroTel')
            ->add('email',EmailType::class)
            ->add('password',PasswordType::class)
            ->add('image', FileType::class,[
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
            ->add('role',ChoiceType::class,[
                'choices'  => [
                    'Membre' => 'Membre',
                    'Coach' => 'Coach'
                ],
            ])
            ->add('description',TextareaType::class)
            ->add('captcha', CaptchaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
