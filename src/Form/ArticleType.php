<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Filter;
use App\Entity\Type;
use App\Repository\FilterRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('date', null, [
                'html5'=>false,
                'format'=>'d-M-y'
            ])
            ->add('capital')
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name'
            ])

            ->add('filter_continent',EntityType::class, [
                'class'=>Filter::class,
                /* création d'une requête SQL qui va chercher dans les filtres,
                le nom du filtre en fonction du nom de la catégorie */
                'query_builder'=> function (FilterRepository $filterRepository){
                    return $filterRepository->createQueryBuilder('f')
                        ->leftJoin('f.category', 'category')
                        ->where('category.id = 3');

                },
                'choice_label'=>'name'
            ])

            ->add('filter_climat', EntityType::class, [
                'class' => Filter::class,
                'query_builder' => function (FilterRepository $filterRepository) {
                    return $filterRepository->createQueryBuilder('f')
                        ->leftJoin('f.category', 'category')
                        ->where('category.id = 2');
                },
                'choice_label'=>'name'
            ])
            ->add('filter_activities', EntityType::class, [
                'class'=> Filter::class,
                'query_builder'=> function (FilterRepository $filterRepository){
                    return $filterRepository->createQueryBuilder('f')
                        ->leftJoin('f.category', 'category')
                        ->where('category.id = 1');
                },
                'choice_label'=>'name'
            ])

            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
