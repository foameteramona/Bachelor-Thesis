<?php

namespace CareerBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('location')
            ->add('start')
            ->add('finish')
            ->add('description', TextareaType::class, array('attr' => array('class' => 'ckeditor')))
            ->add('hostedBy', EntityType::class, array(
                'class' => 'CareerBundle:Professor',
                'choice_label' => 'name',
            ))
            ->add('noOfParticipants')
            ->add('eventType', EntityType::class, array(
                'class' => 'CareerBundle:EventType',
                'choice_label' => 'name',
            ))
            ->add('topics',EntityType::class, array(
                'class' => 'CareerBundle:Tag',
                'property_path' => 'topics', # in square brackets!
                'multiple'      => true,
                'choice_label' => 'name',
                'expanded' => true,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CareerBundle\Entity\Event'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'careerbundle_event';
    }


}
