<?php
namespace App\Form;

use App\Entity\Jeux;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Validator\Constraints\File;

class StringToFileTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

 
 
    public function transform($image): string
    {
        if (null === $image) {
            return '';
        }
        return new File($_ENV['IMAGE_Folder_Path'].$image,[
            'maxSize' => '4M',
            'mimeTypes' => [
                'image/jpeg',
                'image/jpg',
                'image/png',
            ],
            'mimeTypesMessage' => 'Image invalide : (jpg,png,jpeg)'
        ]);
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $issueNumber
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($issueNumber): ?Issue
    {
        // no issue number? It's optional, so that's ok
        if (!$issueNumber) {
            return "";
        }

        $issue = $this->entityManager
            ->getRepository(Issue::class)
            // query for the issue with this id
            ->find($issueNumber)
        ;

        if (null === $issue) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'Image To File Transforma error'
            ));
        }

        return $issue;
    }
}
