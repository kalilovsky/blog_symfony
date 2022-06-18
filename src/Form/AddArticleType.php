<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Image;

class AddArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('smalldesc',TextType::class)
            ->add('category')
            ->add('photoarticle',FileType::class,["mapped" => false, 'constraints' => new Image(['maxSize'=>'5m', "mimeTypesMessage" => "Veuillez envoyer une image au format png, jpg, jpeg ou gif, de 10 mégas octets maximum"])]);
    }

    public function getAllCategories(ManagerRegistry $doctrine){
        $repository = $doctrine->getRepository(Category::class);
        return $repository->findAll();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

}
