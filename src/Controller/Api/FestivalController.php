<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
final class FestivalController extends AbstractController
{
    #[Route('/festival/generate-name')]
    public function generateNameByDates() {

    }
}
