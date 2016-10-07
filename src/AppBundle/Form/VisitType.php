<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VisitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coming_time', DateTimeType::class, [
                'widget' => 'single_text', 
                'html5' => 'false',
                'format' => 'yyyy-MM-dd HH:mm:ss', 
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    // 'data-provide' => 'datepicker',
                    // 'data-date-format' => 'yyyy/mm/dd'
                    ],
                'required'  => false,
                ])
            ->add('leave_time', DateTimeType::class, [
                'widget' => 'single_text', 
                'html5' => 'false',
                'format' => 'yyyy-MM-dd HH:mm:ss', 
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    // 'data-provide' => 'datepicker',
                    // 'data-date-format' => 'yyyy/mm/dd'
                    ],
                'required'  => false,
                ])

            ->add('sum_in')
            ->add('sum_out')
            ->add('sum_win')
            ->add('game')

            ->add('club', EntityType::class, array(
                'class' => 'AppBundle:Club',
                'choice_label' => 'title',
            ))
            ->add('guest', EntityType::class, array(
                'class' => 'AppBundle:Guest',
                'choice_label' => 'firstName',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Visit'
        ));
    }
}
