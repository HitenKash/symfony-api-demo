<?php

namespace App\Controller;


use App\Entity\User;
use App\Util\RequestUtil;
use App\Util\RegistrationResponseUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class AuthController extends AbstractController {

    private $requestUtil;
    private $responseUtil;

    public function __construct(RequestUtil $requestUtil, RegistrationResponseUtil $responseUtil)
    {
        $this->responseUtil = $responseUtil;
        $this->requestUtil = $requestUtil;
    }

    /**
     * @Route("/register", name="api_auth_register", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Register a user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"default"}))
     *     )
     * )
     * @OA\Tag(name="Token")
     * @OA\RequestBody(@Model(type=User::class, groups={"create"}))
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder) {

        $userModel = $this->requestUtil->validate($request->getContent(), User::class);
        $userModel->setPassword($encoder->encodePassword($userModel, $userModel->getPassword()));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userModel);
        $entityManager->flush();       

        $userJsonString = $this->responseUtil->serialize($userModel);
        return new Response($userJsonString, 200, ['Content-Type' => 'application/json']);
    }
}