<?php

namespace App\Form;

use App\Entity\Producteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class ProducteurRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siret')
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('marque')
            ->add('logo', FileType::class, [
                'label' => 'Logo (image)',
                'mapped' => false,          // Ne correspond pas directement à l'entité
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',  // limite 2 Mo
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger une image valide (PNG, JPG, GIF)',
                    ])
                ],
            ])
            ->add('adresse')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false, // ne correspond pas à une propriété de l'entité
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes et conditions.',
                    ]),
                ],
                'label' => 'J’accepte les termes et conditions',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Producteur::class,
        ]);
    }
}