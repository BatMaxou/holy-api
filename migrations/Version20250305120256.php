<?php

namespace DoctrineMigrations;

use App\Enum\HolyTierEnum;
use App\Enum\ProductRangeEnum;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

final class Version20250305120256 extends AbstractMigration
{
    public const MILKSHAKE_RANGE_PRODUCT = [
        'Strawberry Milkshake',
        'Caramel Milkshake',
    ];

    public const MILKSHAKE_RANGE_FLAVOUR_MAP = [
        self::MILKSHAKE_RANGE_PRODUCT[0] => 'Fraise',
        self::MILKSHAKE_RANGE_PRODUCT[1] => 'Caramel',
    ];

    public function getDescription(): string
    {
        return 'Handle new milkshakes';
    }

    public function up(Schema $schema): void
    {
        $products = $this->connection->fetchAllAssociative(
            'SELECT * FROM product WHERE name in (?, ?)',
            [...self::MILKSHAKE_RANGE_PRODUCT]
        );

        $tierLists = $this->connection->fetchAllAssociative(
            'SELECT * FROM tier_list'
        );

        foreach ($products as $product) {
            $this->addSql(
                'UPDATE product SET product_range = :product_range, image_url = :image_url, discr = :discr WHERE id = :id',
                [
                    'product_range' => ProductRangeEnum::MILKSHAKE->value,
                    'image_url' => str_replace(
                        ProductRangeEnum::MERCH->value,
                        ProductRangeEnum::MILKSHAKE->value,
                        $product['image_url']
                    ),
                    'discr' => 'flavour',
                    'id' => $product['id'],
                ]
            );

            $this->addSql(
                'INSERT INTO flavour (id, flavour) VALUES (:id, :flavour)',
                [
                    'id' => $product['id'],
                    'flavour' => self::MILKSHAKE_RANGE_FLAVOUR_MAP[$product['name']],
                ]
            );

            foreach ($tierLists as $tierList) {
                $this->addSql(
                    'INSERT INTO ranked_product (id, tier_list_id, product_id, tier, order_number) VALUES (:id, :tier_list_id, :product_id, :tier, :order_number)',
                    [
                        'id' => (string) Uuid::v4(),
                        'tier_list_id' => $tierList['id'],
                        'product_id' => $product['id'],
                        'tier' => HolyTierEnum::UNRANKED->value,
                        'order_number' => null,
                    ]
                );
            }
        }
    }

    public function down(Schema $schema): void
    {
        $products = $this->connection->fetchAllAssociative(
            'SELECT * FROM product WHERE name in (?, ?)',
            [...self::MILKSHAKE_RANGE_PRODUCT]
        );

        foreach ($products as $product) {
            $this->addSql(
                'DELETE FROM ranked_product WHERE product_id = :product_id',
                ['product_id' => $product['id']]
            );

            $this->addSql(
                'DELETE FROM flavour WHERE id = :id',
                ['id' => $product['id']]
            );

            $this->addSql(
                'UPDATE product SET product_range = :product_range, image_url = :image_url, discr = :discr WHERE id = :id',
                [
                    'product_range' => ProductRangeEnum::MERCH->value,
                    'image_url' => str_replace(
                        ProductRangeEnum::MILKSHAKE->value,
                        ProductRangeEnum::MERCH->value,
                        $product['image_url']
                    ),
                    'discr' => 'product',
                    'id' => $product['id'],
                ]
            );
        }
    }
}
