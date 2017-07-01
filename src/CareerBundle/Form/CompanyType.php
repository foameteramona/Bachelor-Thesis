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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
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
        $builder->add('location');
        $builder->add('website');
        $builder->add('description', TextareaType::class, array('attr' => array('class' => 'ckeditor')));
        $builder->add('topics',EntityType::class, array(
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
            'data_class' => 'CareerBundle\Entity\Company'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'careerbundle_company';
    }
}