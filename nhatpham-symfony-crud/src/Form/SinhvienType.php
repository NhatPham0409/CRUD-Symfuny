<?php

namespace App\Form;

use App\Entity\Sinhvien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SinhvienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('masv',TextType::class,[
                'label'=>'Mã sinh viên'
            ])
            ->add('hoten',TextType::class,[
                'label'=>'Họ tên'
            ])
            ->add('lop',TextType::class,[
                'label'=>'Lớp'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sinhvien::class,
        ]);
    }
}
