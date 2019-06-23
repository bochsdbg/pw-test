<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\InvitationCode;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $inv_codes = [];
        for ($i = 12345; $i < 12400; ++$i) {
            $inv_code = new InvitationCode();
            $inv_code->setCode($i . '');
            $manager->persist($inv_code);

            $inv_codes[] = $inv_code;
        }

        $users = [];
        for ($i = 1; $i < 3; ++$i) {
            $user = new User();
            $user->setFullName('user' . $i);
            $user->setEmail('user' . $i . '@mail.com');
            $user->setMobile('+123456789' . $i);
            $user->setPassword('123');
            $user->setInvitedByCode($inv_codes[$i]);
            $manager->persist($user);

            $users[] = $user;
        }

        $users[0]->addInvitationCode($inv_codes[50]);
        $users[0]->addInvitationCode($inv_codes[51]);
        $users[0]->addInvitationCode($inv_codes[52]);
        $users[0]->addInvitationCode($inv_codes[53]);
        $manager->persist($users[0]);

        $manager->flush();
    }
}
