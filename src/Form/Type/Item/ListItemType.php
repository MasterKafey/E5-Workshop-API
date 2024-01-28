<?php

namespace App\Form\Type\Item;

use App\Entity\ItemArticle;
use App\Entity\ItemCategory;
use App\Entity\ItemMedia;
use App\Entity\ItemURL;
use App\Entity\ItemVote;
use App\Form\Model\Item\ListItemModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'article' => ItemArticle::class,
                    'category' => ItemCategory::class,
                    'media' => ItemMedia::class,
                    'url' => ItemURL::class,
                    'vote' => ItemVote::class,
                ],
            ])
            ->add('page', IntegerType::class)
            ->add('max', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListItemModel::class,
        ]);
    }
}