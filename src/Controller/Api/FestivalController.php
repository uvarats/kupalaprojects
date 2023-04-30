<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class FestivalController extends AbstractController
{
    #[Route('/festival/generate-name', name: 'app_api_festival_name_generate', methods: ['POST'])]
    public function generateNameByDates() {

    }
}
