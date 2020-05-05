<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Project name:',
                'help' => '*Required',
                'required' => true,
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('status', EntityType::class, [
                'label' => 'Status:',
                'required' => true,
                'class' => Status::class,
                'choice_label' => 'name',
                'multiple' => false,
                'help' => '*Required',
            ])
            ->add('employees', EntityType::class, [
                'label' => 'Employees:',
                'required' => true,
                'class' => Employee::class,
                'choice_label' => 'last_name',
                'multiple' => true,
                'attr' => ['class' => 'select2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
