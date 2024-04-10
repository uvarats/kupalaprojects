<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Festival;
use App\Service\Festival\FestivalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class FestivalController extends AbstractController
{
    public function __construct(
        private readonly FestivalService $festivalService
    ) {}

    #[Route('/festivals/dates', name: 'app_api_festivals_dates')]
    public function getFestivalsDates(): JsonResponse
    {
        $dates = $this->festivalService->getActiveFestivalsDates();

        return $this->json($dates);
    }

    #[Route('/festival/{id}/dates', name: 'app_api_festival_dates')]
    public function getFestivalDates(Festival $festival): JsonResponse
    {
        $dates = $this->festivalService->getFestivalDates($festival);

        return $this->json($dates);
    }
}
