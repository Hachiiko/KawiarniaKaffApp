<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Enum\OrderStatusEnum;
use App\Admin\Field\EnumField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\TextAlign;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Zamówienie')
            ->setEntityLabelInPlural('Zamówienia')
            ->setPageTitle('edit', function (Order $order) {
                return sprintf('Edycja zamówienia #%d', $order->getId());
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

        yield TextField::new('firstName')
            ->setLabel('Imię')
            ->hideOnForm();

        yield TextField::new('lastName')
            ->setLabel('Nazwisko')
            ->hideOnForm();

        yield TextField::new('phone')
            ->setLabel('Numer telefonu')
            ->hideOnForm();

        yield EnumField::new('status')
            ->setLabel('Status')
            ->setEnumClass(OrderStatusEnum::class);

        yield MoneyField::new('priceTotal')
            ->setLabel('Wartość')
            ->setTextAlign(TextAlign::LEFT)
            ->setCurrency('PLN')
            ->hideOnForm();

        yield TextField::new('token')
            ->setLabel('Kod odbioru')
            ->hideOnForm();

        yield DateTimeField::new('createdAt')
            ->setLabel('Data')
            ->hideOnForm();
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->addSelect('order_owner')
            ->addSelect('order_product')
            ->join(sprintf('%s.owner', $rootAlias), 'order_owner')
            ->join(sprintf('%s.products', $rootAlias), 'order_product');

        return $queryBuilder;
    }
}
