<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClubType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', CKEditorType::class, ['config_name' => 'custom'])
            ->add('address')
            ->add('contacts')
            ->add('enabled')

            ->add('fimage', FileType::class, array(
                'required'  => false,
                'label' => 'Image (Image file)'
                ))
            ->add('position')

            ->add('slug')

            ->add('save', SubmitType::class, array('label' => 'Save'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Club'
        ));
    }
}
