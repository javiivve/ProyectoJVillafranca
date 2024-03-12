<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
       ->add('nombre', null, [
    'attr' => ['class' => 'form-control custom-input']
])
->add('apellidos', null, [
    'attr' => ['class' => 'form-control custom-input']
])
->add('email', null, [
    'attr' => ['class' => 'form-control custom-input']
])
->add('agregarTerminos', CheckboxType::class, [
    'mapped' => false,
    'constraints' => [
        new IsTrue([
            'message' => 'Debes aceptar las condiciones.',
        ]),
    ],
    'attr' => ['class' => 'form-check-input custom-input']
])
->add('plainPassword', PasswordType::class, [
    'mapped' => false,
    'attr' => ['autocomplete' => 'new-password'],
    // 'attr' => ['class' => 'form-control custom-input', 'autocomplete' => 'new-password'],
    'constraints' => [
        new NotBlank([
            'message' => 'Por favor, introduce una contraseña',
        ]),
        new Length([
            'min' => 4,
            'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres',
            'max' => 4096,
        ]),
    ],
])
->add('telefono', null, [
    'attr' => ['class' => 'form-control custom-input']
])
->add('foto', FileType::class, [
    'attr' => ['class' => 'form-control-file custom-input']
]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
