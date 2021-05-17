<?php

namespace App\Form;

use App\Entity\Jeux;
use App\Form\StringToFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class JeuxType extends AbstractType
{
    private $transformer;

    public function __construct(StringToFileTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#ffffff',
                    //...
                ),
            ))
            ->add('typePlateforme', ChoiceType::class, [
                'choices'  => [
                    'Desktop' => 'Desktop',
                    'Web' => 'Web',
                    'Mobile' => 'Mobile',
                ],
            ])
            ->add('fileimage', FileType::class,['data_class'=>null],[
                
                'label' => 'Image(jpg,png,jpeg)',
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
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Jeux::class,
        ]);
    }
}
