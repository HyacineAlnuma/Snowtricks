<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image as ImageValidator;


class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('file', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new ImageValidator([
                    'maxSize' => '10M',
                    "mimeTypes" => [
                        "image/png",
                        "image/jpg",
                        "image/jpeg",
                    ],
                    "mimeTypesMessage" => "Veuillez envoyer une image au format png, jpg ou jpeg, de 10 mégas octets maximum"
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}