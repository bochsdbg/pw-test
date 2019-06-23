<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\InvitationCode;

class InvitationCodeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 12345; $i < 12355; ++$i) {
            $inv_code = new InvitationCode();
            $inv_code->setCode($i . '');
            $manager->persist($inv_code);
        } 
        
        $manager->flush();
    }
}
