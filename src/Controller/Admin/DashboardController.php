<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Character;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to Album CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        $urlalbum = $routeBuilder->setController(AlbumCrudController::class)->generateUrl();
        return $this->redirect($urlalbum);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mangarubomu');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Albums', 'fas fa-list', Album::class);
        yield MenuItem::linkToCrud('Characters', 'fas fa-list', Character::class);


    }
}
