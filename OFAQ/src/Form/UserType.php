<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,[
                'label' => 'Votre prénom',
            ])
            ->add('email')
            ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPresetData'))
            ->add('role')
        ;
    }
            // fonctio utlisant un écouteur d'évènements qui n'affiche pas le même champ de mot de passe selon que l'on crée ou édite un compte utilisateur.
    public function onPresetData(FormEvent $event)
    {
        $user = $event->getData();
        $form = $event->getForm();
        // On vérifie si notre objet $user = nouveau ou pas
        if (!$user || null === $user->getId()) {
            // Create
            $form->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux champs doivent correspondre.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Mot de Passe'),
                'second_options' => array('label' => 'Répétez le mot de passe'),
            ));
        } else {
            // Edit
            $form->add('password', null, [
                'attr' => [
                    'placeholder' => 'Laisser vide si inchangé',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
