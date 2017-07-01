<?php

namespace CareerBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('shortDescription')
            ->add('requiredKnowledge', TextareaType::class, array('attr' => array('class' => 'ckeditor', 'id'=>'editor-inline')))
            ->add('niceToHaveKnowledge', TextareaType::class, array('attr' => array('class' => 'ckeditor')))
            ->add('description',TextareaType::class, array('attr' => array('class' => 'ckeditor')))
            ->add('jobType', EntityType::class, array(
                'class' => 'CareerBundle:JobType',
                'choice_label' => 'name',
                'attr' => ['class' => 'jobTypes']
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
            'data_class' => 'CareerBundle\Entity\Job'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'careerbundle_job';
    }


}
