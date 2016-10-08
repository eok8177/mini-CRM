<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', ChoiceType::class, array(
                'choices'  => array(
                    'Manager' => 'ROLE_MANAGER',
                    'Admin' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SADMIN',
                ),
            ))
            ->add('username', TextType::class, array('label' => 'Login'))
            ->add('email', EmailType::class)
            ->add('firstName', TextType::class, array('label' => 'First Name', 'required'  => false))
            ->add('lastName', TextType::class, array('label' => 'Last Name', 'required'  => false))
            ->add('phone', TextType::class, array('label' => 'Phone', 'required'  => false))
            ->add('skype', TextType::class, array('label' => 'Skype', 'required'  => false))

            ->add('plainPassword', PasswordType::class, [
                'required'    => false,
                'empty_data'  => null,
                'label' => 'New Password',
                'attr' => array("autocomplete" => "off")
                ])

            ->add('club', EntityType::class, array(
                'class' => 'AppBundle:Club',
                'choice_label' => 'title',
            ))

            ->add('isActive', CheckboxType::class, array('label' => 'Active'))

            ->add('photo', FileType::class, 
                array('required'  => false),
                array('label' => 'Photo')
                )
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}
