<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\InvitationCode;
use Symfony\Component\HttpFoundation\Request;
use \Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
        $inv = $req->query->get('inv', '');

        try {
            $inv_code = $this->getDoctrine()
                ->getRepository(InvitationCode::class)
                ->findOneByCode($inv);

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
    public function insreg(Request $req, ValidatorInterface $validator)
    {
        $inv = $req->query->get('inv', '');

        try {
            $inv_code = $this->getDoctrine()
                ->getRepository(InvitationCode::class)
                ->findOneByCode($inv);

            if ($inv_code === null) {
                throw new Exception('Requested invitation code did not found', 1);
            }
            if ($inv_code->getInvitee() !== null) { // <-- race condition between here
                throw new Exception('Invitation code is already used', 2);
            }

            $user = new User();

            $user->setEmail($req->query->get('email', ''));
            $user->setPassword($req->query->get('password', ''));
            $user->setMobile($req->query->get('mobile', ''));
            $user->setFullName($req->query->get('pip', ''));
            $user->setInvitedByCode($inv_code);
            
            $errors = $validator->validate($user);
            if (count($errors)) {
                $cnt = count($errors);
                throw new Exception("Validation errors($cnt): " . $this->formatErrors($errors), 3);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush(); // <-- and here

            return $this->json([
                'error' => 0,
            ]);
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getCode(),
                'comment' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @Route("/core/v1/")
     */
    public function index(Request $req) 
    {
        switch ($req->query->get('do')) {
        case 'insreg': return $this->forward('App\\Controller\\ApiV1Controller::insreg', [], $req->query->all());
        case 'chinv': return $this->forward('App\\Controller\\ApiV1Controller::chinv', [], $req->query->all());
        default: new NotFoundHttpException('Method does not exist');
        }
    }

    private function formatErrors(ConstraintViolationListInterface $errors): string 
    {
        $messages = [];
        foreach ($errors as $e) {
            $messages[] = $e->getPropertyPath() . ': ' . $e->getMessage();
        }
        return join('; ', $messages);
    }
}
