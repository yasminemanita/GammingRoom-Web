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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CourType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['flow_step']) {
            case 1:
                $validValues = [2, 4];
                $builder
                    ->add('lienyoutube')
                    ->add('nomcours')
                    ->add('description')
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
                    ->add('tags');
                break;
            case 2:
                $builder
                    ->add('nbParticipant', TextType::class,[

                        // unmapped means that this field is not associated to any entity property
                        'mapped' => true,


                        'required' => true])
                    ->add('dateCreation')
                    ->add('categorie', EntityType::class, [
                        'class' => Categorie::class,
                        'choice_label' => 'Categorie',
                        'multiple' => false,
                        'expanded' => false
                    ])
                    ;//->add('captcha', CaptchaType::class); // That's all !;
            break;
        }
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
