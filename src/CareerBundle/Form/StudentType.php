<?php
/**
 * Created by PhpStorm.
 * User: Ramona
 * Date: 6/3/2017
 * Time: 11:28 AM
 */

namespace CareerBundle\Form;

use CareerBundle\Entity\StudyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    private $studyTypes;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username');
        $builder->add('email');
        $builder->add('password');
        $builder->add('name');
        $builder->add('startYear');
        $builder->add('specialisation', EntityType::class, array(
            // query choices from this entity
            'class' => 'CareerBundle:Specialisation',

            // use the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ));
        $builder->add('study_type', EntityType::class, array(
            // query choices from this entity
            'class' => 'CareerBundle:StudyType',

            // use the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ));
        $builder->add('shortCV', TextareaType::class, array(
            'attr' => array('class' => 'ckeditor')
        ));
        $builder->add('interests', EntityType::class, array(
            'class' => 'CareerBundle:Tag',
            'property_path' => 'interests', # in square brackets!
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
            'data_class' => 'CareerBundle\Entity\Student'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'careerbundle_student';
    }
}