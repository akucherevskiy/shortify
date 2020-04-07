<?php

namespace App\Controller;

use App\Entity\{Url, UrlStat};
use App\Helpers\RequestHelper;
use App\Repository\UrlStatRepository;
use App\Services\ShortifyService;
use Doctrine\ORM\{EntityManagerInterface, NonUniqueResultException};
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, RedirectResponse, Request, Response};
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MainController extends AbstractController
{
    /**
     * @var RequestHelper
     */
    private $requestHelper;
    /**
     * @var ShortifyService
     */
    private $shortifyService;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        RequestHelper $requestHelper,
        ShortifyService $shortifyService,
        EntityManagerInterface $em
    ) {
        $this->requestHelper = $requestHelper;
        $this->shortifyService = $shortifyService;
        $this->em = $em;
    }

    /**
     * @Rest\Post("/shortify")
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function shortify(Request $request)
    {
        try {
            $fullUrl = $this->requestHelper->getUrlAsArray($request);
        } catch (BadRequestHttpException $exception) {
            return $this->json('Bad Request', Response::HTTP_BAD_REQUEST);
        }

        $shortUrl = $this->shortifyService->getShuffledString();
        if ($this->em->getRepository(Url::class)->findOneBy(['short' => $shortUrl])) {
            $shortUrl = $this->shortifyService->getShuffledString();
        }
        $this->em->persist((new Url())->setFull($fullUrl)->setShort($shortUrl));
        $this->em->flush();

        return new Response("localhost:8000/" . $shortUrl, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/{url}")
     * @param string $url
     * @return JsonResponse|RedirectResponse
     */
    public function redirectFromShort(string $url)
    {
        /** @var Url $urlObj */
        $urlObj = $this->em->getRepository(Url::class)->findOneBy(['short' => $url]);
        if (!$urlObj) {
            return $this->json('No short url found', Response::HTTP_NOT_FOUND);
        }

        $this->em->persist((new UrlStat())->setUrl($urlObj));
        $this->em->flush();

        return $this->redirect($urlObj->getFull());
    }

    /**
     * @Rest\Get("/statistics/{url}")
     * @param string $url
     * @return JsonResponse|RedirectResponse
     * @throws NonUniqueResultException
     */
    public function statistics(string $url)
    {
        $urlObj = $this->em->getRepository(Url::class)->findOneBy(['short' => $url]);
        if (!$urlObj) {
            return $this->json('No short url found', Response::HTTP_NOT_FOUND);
        }
        /** @var UrlStatRepository $urlStatRepo */
        $urlStatRepo = $this->em->getRepository(UrlStat::class);

        return $this->json(
            [
                'day' => $urlStatRepo->getDailyAccess($urlObj->getId()),
                'week' => $urlStatRepo->getWeeklyAccess($urlObj->getId()),
                'allTime' => $urlStatRepo->getAllTimeAccess($urlObj->getId())
            ]
        );
    }


}