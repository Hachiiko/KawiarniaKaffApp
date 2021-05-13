<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Table;
use App\Entity\TableReservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->redirect(
            $this->adminUrlGenerator
                ->setController(ProductCrudController::class)
                ->set(EA::MENU_INDEX, 0)
                ->generateUrl()
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kawiarnia Kaff');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Produkty', 'fas fa-coffee', Product::class);

        yield MenuItem::linkToCrud('Kategorie produktów', 'fas fa-box', ProductCategory::class);

        yield MenuItem::linkToCrud('Zamówienia', 'fas fa-shopping-basket', Order::class);

        yield MenuItem::linkToCrud('Stoliki', 'fas fa-utensils', Table::class);

        yield MenuItem::linkToCrud('Rezerwacje', 'fas fa-chair', TableReservation::class);
    }
}

