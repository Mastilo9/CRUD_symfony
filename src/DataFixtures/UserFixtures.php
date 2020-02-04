<?php

namespace App\DataFixtures;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
      $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
      for ($i = 1; $i < 10; $i++) {
        $user = new User();
        $user->setInfo('information '.$i);
        $user->setUsername('user'.$i);
        $user->setEmail('user'.$i.'@mail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'.$i));

        for($j = 0; $j < 4; $j++) {
          $note = new Note();
          $note->setTitle('Title '.$j);
          $note->setEssay('User notes about title '.$j);

          // relates this product to the category
          $note->setUser($user);
          $manager->persist($note);
        }


        $manager->persist($user);


      }
      $manager->flush();
    }
}
