<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth", name="auth")
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AuthController.php',
        ]);
    }

    /**
     * Simple user registration action
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    final public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('_username');
        $email = $request->request->get('_email');
        $password = $request->request->get('_password');

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();

        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * A protected resource ACL IS_AUTHENTICATED_FULLY
     *  to test the Token JWT
     * @return Response
     */
    final public function api(): Response
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

}
