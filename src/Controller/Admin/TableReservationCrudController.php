<?php

namespace App\Controller\Admin;

use App\Admin\Field\EnumField;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Table;
use App\Entity\TableReservation;
use App\Enum\OrderStatusEnum;
use App\Enum\TableReservationStatusEnum;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TableReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TableReservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Rezerwacja stolika')
            ->setEntityLabelInPlural('Rezerwacje stolików')
            ->setPageTitle('edit', function (TableReservation $reservation) {
                return sprintf('Edycja rezerwacji #%d', $reservation->getId());
            });
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->setLabel('Lp.')
            ->hideOnForm();

        yield AssociationField::new('owner')
            ->setLabel('Klient')
            ->hideOnForm();

        yield AssociationField::new('table')
            ->setLabel('Stolik')
            ->hideOnForm();

        yield TextField::new('firstName')
            ->setLabel('Imię')
            ->hideOnForm();

        yield TextField::new('lastName')
            ->setLabel('Nazwisko')
            ->hideOnForm();

        yield EnumField::new('status')
            ->setLabel('Status')
            ->setEnumClass(TableReservationStatusEnum::class);

        yield TextField::new('phone')
            ->setLabel('Numer telefonu')
            ->hideOnForm();

        yield DateTimeField::new('dateFrom')
            ->setLabel('Data od')
            ->hideOnForm();

        yield DateTimeField::new('dateFrom')
            ->setLabel('Data do')
            ->hideOnForm();
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->addSelect('reservation_owner')
            ->addSelect('reservation_table')
            ->join(sprintf('%s.owner', $rootAlias), 'reservation_owner')
            ->join(sprintf('%s.table', $rootAlias), 'reservation_table');

        return $queryBuilder;
    }
}
