<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [ 'required' => false])
            ->add('author', TextType::class, [ 'required' => false])
            ->add('publisher', TextType::class, [ 'required' => false])
            ->add('submit', SubmitType::class)
            ->remove('isbn')
            ->remove('subject')
            ->remove('isInTheLibrary')
            ->remove('proposedBy')
            ->remove('pageCount')
            ->remove('creationDate')
            ->remove('lastUpdateDate')
            ->remove('mainCategory')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
