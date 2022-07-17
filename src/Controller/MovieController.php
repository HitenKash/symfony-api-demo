<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movies;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use App\Util\MovieResponseUtil;
use App\Repository\MoviesRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Util\MovieRequestUtil;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MovieController extends AbstractController
{
    private $moviesRepository;
    private $movieResponseUtil;
    private $token;
    private $requestUtil;

    public function __construct(
        MoviesRepository $moviesRepository, 
        MovieResponseUtil $movieResponseUtil,
        TokenStorageInterface $token,
        MovieRequestUtil $requestUtil
    ) {
        $this->movieResponseUtil = $movieResponseUtil;
        $this->moviesRepository = $moviesRepository;
        $this->token = $token;
        $this->requestUtil = $requestUtil;
    }

    /**
     * @Route("/movie", name="app_movie_index", methods={"GET"})
     * @OA\Tag(name="Movie")
     * @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="release_date", type="date"),
     *        @OA\Property(property="director", type="string"),
     *        @OA\Property(property="casts", type="array", @OA\Items()),
     *        @OA\Property(property="ratings", type="object", @OA\Property(property="name", type="float")),
     *     ),
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Page number"
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Number of record per page"
     * )
     * @Security(name="bearerAuth")
     */
    public function index(Request $request): Response
    {
        $pageNumber = $request->query->get('page',1)-1;
        $limit = $request->query->get('limit',10);
        $movies = $this->moviesRepository->findBy(
                    ['createdBy' =>$this->token->getToken()->getUser()],
                    ['id'=> 'ASC'],
                    $limit,
                    $pageNumber*$limit
                );
        return new Response($this->movieResponseUtil->serialize($movies), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/movie", name="app_movie_post", methods={"POST"})
     * @OA\Tag(name="Movie")
     * @OA\RequestBody(required= true,     
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="release_date", type="date"),
     *        @OA\Property(property="director", type="string"),
     *        @OA\Property(property="casts", type="array", @OA\Items()),
     *        @OA\Property(property="ratings", type="object", @OA\Property(property="name", type="float")),
     *     )
     * )
     * @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="release_date", type="date"),
     *        @OA\Property(property="director", type="string"),
     *        @OA\Property(property="casts", type="array", @OA\Items()),
     *        @OA\Property(property="ratings", type="object", @OA\Property(property="name", type="float")),
     *     )
     * )
     * @Security(name="bearerAuth")
     */
    public function post(Request $request): Response
    {   
        $movie = $this->requestUtil->validate($request->getContent(), Movies::class);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($movie);
        $entityManager->flush();
        return new Response($this->movieResponseUtil->serialize($movie), 200, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/movie/{id}", name="app_movie_get", methods={"GET"})
     * @OA\Tag(name="Movie")
     * @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="release_date", type="date"),
     *        @OA\Property(property="director", type="string"),
     *        @OA\Property(property="casts", type="array", @OA\Items()),
     *        @OA\Property(property="ratings", type="object", @OA\Property(property="name", type="float")),
     *     )
     * )
     * @Security(name="bearerAuth")
     */
    public function get(string $id): Response
    {   
        $movie = $this->moviesRepository->findOneBy(
                    ['createdBy' => $this->token->getToken()->getUser(), 'id' => $id ],
                    );
        return new Response($this->movieResponseUtil->serialize($movie), 200, ['Content-Type' => 'application/json']);
    }
}
