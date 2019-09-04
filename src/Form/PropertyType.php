<?php

namespace App\Form;

use App\Entity\Property;
use App\Entity\Option;
use App\Form\OptionType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
			 ->add('heat', ChoiceType::class, [
                'choices' => [
                    'Gaz' => 'gaz',
                    'Electrique' => 'elec',
                ],
            ])
            //->add('heat', ChoiceType::class,[
			 //     'choices' => $this->getChoices()
			//])
            ->add('city')
			 ->add('pictureFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])
			 ->add('options', EntityType::class, [
                'class' => Option::class,
                'required' => false,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('address')
            ->add('postalCode')
            ->add('sold', CheckboxType::class)
            ->add('createdAt', DateType::class)
			->add('updatedAt', DateType::class)
			->add(
        'save',
        SubmitType::class,
        [
            'attr' => ['class' => 'form-control btn-primary pull-right'],
            'label' => 'Publier votre mobilier'
        ]
    );
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
	private function getChoices (){
	 $choices = Property::HEAT;
	 $output = [];
	 foreach($choices as $k => $v){
	  $output[$v] = $k; 
	 }
	 return $output;
	}
}
