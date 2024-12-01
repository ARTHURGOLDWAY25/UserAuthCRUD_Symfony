<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'constraints' => [
                        new NotBlank(['message' => 'Please confirm password']),
                    ],
                ],
                'invalid_message' => 'Passwords must match',
            ])
            

            ->add('roles', ChoiceType::class, [
                'label' => 'User Role',
                'mapped' => false, // Map to the `roles` property in the entity
                // 'multiple' => true, // Single selection
                'expanded' => false, // Render as a dropdown
                'required' => true,
                'placeholder' => 'Select a role', // Placeholder for the dropdown
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Visitor' => 'ROLE_VISITOR',
                    'Supplier' => 'ROLE_SUPPLIER',
                    'Staff' => 'ROLE_STAFF',
                ],
                'attr' => ['class' => 'form-control'], // Bootstrap styling
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
