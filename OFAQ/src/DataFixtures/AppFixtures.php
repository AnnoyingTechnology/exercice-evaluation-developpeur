<?php
namespace App\DataFixtures;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Question;
use App\Entity\Answer;
use Faker;

class AppFixtures extends Fixture
{

    private $encoder;

    // on met roleUser en propriété afin de la réutiliser dans le générateur et ne pas créer plusieurs ROLE_USER dans la DB.
    private $roleUser;
    //On passe l'encodeur de mDP à la classe AppFixtures
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
       
    }

    public function load(ObjectManager $manager)
    {
        //on appelle le générateur en lui indiquant de fournir des données françaises
        $generator = Factory::create('fr_FR');
        $populator = new \Faker\ORM\Doctrine\Populator($generator, $manager);

        // on crée le 3 rôles
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setLabel('Administrateur');

        $this->roleUser = new Role();
        $this->roleUser->setName('ROLE_USER');
        $this->roleUser->setLabel('Membre');

        $roleModo = new Role();
        $roleModo->setName('ROLE_MODERATOR');
        $roleModo->setLabel('Modérateur');
        
        //On créé les 3 utilisateurs avec leur rôle
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@ofaq.com');
        $userAdmin->setPassword($this->encoder->encodePassword($userAdmin, 'admin'));
        $userAdmin->setRole($roleAdmin);

       /* $userUser = new User();
        $userUser->setUserName('user');
        $userUser->setEmail('user@ofaq.com');
        $userUser->setPassword($this->encoder->encodePassword($userUser, 'user'));
        $userUser->setRole($roleUser);*/

        $userModo = new User();
        $userModo->setUsername('modo');
        $userModo->setEmail('modo@ofaq.com');
        $userModo->setPassword($this->encoder->encodePassword($userModo, 'modo'));
        $userModo->setRole($roleModo);

        $manager->persist($roleAdmin);
        //$manager->persist($roleUser);
        $manager->persist($roleModo);
        $manager->persist($userAdmin);
        //$manager->persist($userUser);
        $manager->persist($userModo);

        //On utilise le populator pour  créer des utilisateurs (au statut de membre ), des questions, des tags, des réponses.
        $populator->addEntity('App\Entity\User', 6, [
            'username' => function() use ($generator) { return $generator->unique()->firstName();},
        ], [function($user)  { $user->setEmail($user->getUserName().'@ofaq.com'); },
            function($user) { $user->setPassword($this->encoder->encodePassword($user, 'user'));
            }, 
             function($user) { $user->setRole($this->roleUser) ;},
        ]);

        $populator->addEntity('App\Entity\Question', 6, [
            'title' => function() use ($generator) { return $generator->unique()->words($nb = 3, $asText = true) ; },
            'body' => function() use ($generator) { return $generator->paragraph($nbSentences = 3, $variableNbSentences = true); },
            'createdAt' => function() use ($generator) { return $generator->dateTime($max = 'now', $timezone = null);},
           // 'author' =>function() use ($generator) { return $generator->numberBetween($min = 1, $max = 3);},
        ]);

        $populator->addEntity('App\Entity\Answer', 10, [
            'body'=> function() use ($generator) { return $generator->realText($maxNbChars = 200, $indexSize = 2) ;},
            'createdAt' => function() use ($generator) { return $generator->dateTime($max = 'now', $timezone = null);},
        ]);

        $populator->addEntity('App\Entity\Tag', 10, [
            'name'=>function() use ($generator) { return $generator->word() ;},
        ]);

        $inserted = $populator->execute();
        
        //Avant d'envoyer, on attribue des  tags aux questions
        //On récupère les questions
        $questions = $inserted['App\Entity\Question'];
        //$users= $inserted['App\Entity\User'];
        $tags = $inserted['App\Entity\Tag'];

        foreach($questions as $question){
            //shuffle($users);
            shuffle($tags);
            //$question->setAuthor($users[0]);
            $question->addTag($tags[1]);
            $question->addTag($tags[2]);
            $question->addTag($tags[4]);
    } 
            

        $manager->flush();
    }
}