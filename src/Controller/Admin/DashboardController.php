<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\EducationSubGroup;
use App\Entity\Festival;
use App\Entity\Participant;
use App\Entity\Project;
use App\Entity\ProjectAuthor;
use App\Entity\ProjectSubject;
use App\Entity\Team;
use App\Entity\TeamInvite;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/manage', name: 'app_admin')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');

        return $this->render('@EasyAdmin/layout.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Панель управления')
            ->setLocales([
                'ru' => 'Русский',
            ])
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        return [
            MenuItem::linkToDashboard('Панель управления', 'fa fa-home'),

            MenuItem::section('Пользователи'),
            MenuItem::linkToCrud('Пользователи', 'fas fa-users', User::class),
            MenuItem::linkToCrud('Авторы проектов', 'fas fa-user-graduate', ProjectAuthor::class),
            MenuItem::linkToCrud('Участники', 'fas fa-user-astronaut', Participant::class),
            MenuItem::linkToCrud('Команды', 'fas fa-people-group', Team::class),
            MenuItem::linkToCrud('Приглашения в команды', 'fas fa-calendar-check', TeamInvite::class),

            MenuItem::section('Фестивали и проекты'),
            MenuItem::linkToCrud('Фестивали', 'fas fa-calendar', Festival::class),
            MenuItem::linkToCrud('Предметы проектов', 'fa fa-tags', ProjectSubject::class),
            MenuItem::linkToCrud('Проекты', 'fas fa-project-diagram', Project::class),
            MenuItem::linkToCrud('Образовательные подгруппы', 'fas fa-school', EducationSubGroup::class)
        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        assert($user instanceof User);
        return parent::configureUserMenu($user)
            ->setName($user->getFirstName())
            ->setGravatarEmail($user->getEmail())
            ->addMenuItems([
                MenuItem::linkToRoute('На главную', 'fa fa-home', 'app_index')
            ]);
    }
}
