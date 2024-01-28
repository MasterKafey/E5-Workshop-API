<?php

namespace App\Form\Type\Item\Vote;

use App\Entity\ItemVote;
use App\Form\Model\Item\Vote\CreateItemVoteModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateItemVoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('icon', TextType::class)
            ->add('text', TextType::class)
            ->add('propositions', CollectionType::class, [
                'entry_type' => UpdateItemVotePropositionType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemVote::class,
        ]);
    }
}