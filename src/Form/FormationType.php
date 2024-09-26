<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Regex;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomFormation', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 1]), // At least 1 character
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'NomFormation must contain only letters.',
                    ]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'minMessage' => 'Description must be at least {{ limit }} characters long.']),
                ],
            ])
            ->add('dateDebut', null, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(['value' => 'today', 'message' => 'DateDebut must be after the current date.']),
                ],
            ])
            ->add('dateFin', null, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(['propertyPath' => 'parent["dateDebut"]', 'message' => 'DateFin must be after DateDebut.']),
                ],
            ])
            ->add('prix', null, [
                'constraints' => [
                    new NotBlank(),
                    new Type(['type' => 'numeric', 'message' => 'Price must be a number.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}