<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\InvitationCode;
use Symfony\Component\HttpFoundation\Request;
use \Exception;

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
    public function chinv(Request $req)
    {
        $code = $req->query->get('inv', '');

        $inv_code = $this->getDoctrine()
            ->getRepository(InvitationCode::class)
            ->findOneByCode($code);

        try {
            if ($inv_code === null) {
                throw new Exception('Requested invitation code did not found', 1);
            }

            $user = $inv_code->getInvitee();
            if ($user === null) {
                throw new Exception('Nobody was registered by the requested invitation code', 2);
            }

            return $this->json([
                'error' => 0,
                'pip' => $user->getFullName(),
                'email' => $user->getEmail(),
                'mobile' => $user->getMobile(),
            ]);
        } catch(Exception $e) {
            return $this->json([
                'error' => $e->getCode(),
                'comment' => $e->getMessage(),
            ]);
        }
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
