<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Type\ProductVariantType;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('edit', function (Product $product) {
                return sprintf('Edycja produktu "%s"', $product->getName());
            })
            ->setEntityLabelInSingular('Produkt')
            ->setEntityLabelInPlural('Produkty');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Dodaj produkt');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name')
            ->setLabel('Nazwa');

        yield AssociationField::new('category')
            ->setLabel('Kategoria');

        yield ImageField::new('image')
            ->setLabel('Zdjęcie')
            ->setUploadDir('/public/uploads/')
            ->setBasePath('/uploads');

        yield CollectionField::new('variants')
            ->setLabel('Warianty')
            ->setEntryType(ProductVariantType::class)
            ->allowAdd()
            ->allowDelete()
            ->hideOnIndex();
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->addSelect('product_category')
            ->join(sprintf('%s.category', $rootAlias), 'product_category');

        return $queryBuilder;
    }
}
