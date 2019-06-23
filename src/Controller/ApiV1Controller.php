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

            $constraint = new Assert\Collection([
                'fields' => [
                    'email' => new Assert\Email(),
                    'pip' => new Assert\Length(['min' => 3, 'max' => 255]),
                    'mobile' => new Assert\Length(['min' => 3, 'max' => 255]),
                    'password' => new Assert\Length(['min' => 3, 'max' => 255]),
                ],
                'allowExtraFields' => true,
            ]);
            
            $errors = $validator->validate($req->query->all(), $constraint);
            if (count($errors)) {
                $cnt = count($errors);
                throw new Exception("Validation errors($cnt): " . $this->formatErrors($errors), 3);
            }

            $user = new User();

            $user->setEmail($req->query->get('email'));
            $user->setPassword($req->query->get('password'));
            $user->setMobile($req->query->get('mobile'));
            $user->setFullName($req->query->get('pip'));
            $user->setInvitedByCode($inv_code);

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

    private function formatErrors(ConstraintViolationListInterface $errors): string 
    {
        $messages = [];
        foreach ($errors as $e) {
            $messages[] = $e->getPropertyPath() . ': ' . $e->getMessage();
        }
        return join('; ', $messages);
    }
}
