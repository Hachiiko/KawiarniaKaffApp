<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Table;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Table::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('edit', function (Table $table) {
                return sprintf('Edycja stolika "%s"', $table->getName());
            })
            ->setEntityLabelInSingular('Stolik')
            ->setEntityLabelInPlural('Stoliki');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Dodaj stolik');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name')
            ->setLabel('Nazwa');
    }
}
