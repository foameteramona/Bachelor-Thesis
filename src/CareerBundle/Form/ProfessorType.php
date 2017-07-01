<?php
/**
 * Created by PhpStorm.
 * User: Ramona
 * Date: 6/3/2017
 * Time: 11:28 AM
 */

namespace CareerBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfessorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username');
        $builder->add('email');
        $builder->add('password');
        $builder->add('name');
        $builder->add('personalWebsite');
        $builder->add('department', EntityType::class, array(
            'class' => 'CareerBundle:FacultyDepartment',
            'choice_label' => 'name',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CareerBundle\Entity\Professor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'careerbundle_professor';
    }
}