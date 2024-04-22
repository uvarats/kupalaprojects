<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Feature\Account\Dto\UserData;
use App\Feature\Account\Form\UserDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/signup', name: 'app_signup')]
final class SignUp extends AbstractController
{
    public function __construct(

    ) {}
    public function __invoke(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $userData = new UserData();
        $form = $this->createForm(UserDataType::class, $userData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('user/signup.html.twig', []);
    }
}
