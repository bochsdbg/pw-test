<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiV1Controller extends AbstractController
{
    /**
     * @Route("/core/v1/test", name="api_v1_test")
     */
    public function test()
    {
        return $this->json([
            'message' => 'test',
        ]);
    }

    /**
     * @Route("/core/v1/chinv")
     */
    public function chinv()
    {
        return $this->json([
            'error' => 0,
            // 'comment' => '',
            'pip' => '',
            'email' => '',
            'mobile' => '',
        ]);
    }

    /**
     * @Route("/core/v1/insreg")
     */
    public function insreg()
    {
        return $this->json([
            'error' => 0,
            'comment' => '',
        ]);
    }
}
