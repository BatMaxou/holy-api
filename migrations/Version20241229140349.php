<?php

namespace DoctrineMigrations;

use App\Enum\ProductRangeEnum;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241229140349 extends AbstractMigration
{
    const NEW_MILKSHAKE_RANGE_PRODUCT = [
        'Hazelnut Milkshake',
        'Banana Milkshake',
        'Vanilla Milkshake',
    ];

    public function getDescription(): string
    {
        return 'Handle new milkshake range';
    }

    public function up(Schema $schema): void
    {
        $products = $this->connection->fetchAllAssociative(
            'SELECT * FROM product WHERE name in (?, ?, ?)',
            [...self::NEW_MILKSHAKE_RANGE_PRODUCT]
        );

        foreach ($products as $product) {
            $this->addSql(
                'UPDATE product SET product_range = :product_range, image_url = :image_url WHERE id = :id',
                [
                    'product_range' => ProductRangeEnum::MILKSHAKE->value,
                    'image_url' => str_replace(
                        ProductRangeEnum::DISCOVER_PACK->value,
                        ProductRangeEnum::MILKSHAKE->value,
                        $product['image_url']
                    ),
                    'id' => $product['id'],
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        $products = $this->connection->fetchAllAssociative(
            'SELECT * FROM product WHERE name in (?, ?, ?)',
            [...self::NEW_MILKSHAKE_RANGE_PRODUCT]
        );

        foreach ($products as $product) {
            $this->addSql(
                'UPDATE product SET product_range = :product_range, image_url = :image_url WHERE id = :id',
                [
                    'product_range' => ProductRangeEnum::DISCOVER_PACK->value,
                    'image_url' => str_replace(
                        ProductRangeEnum::MILKSHAKE->value,
                        ProductRangeEnum::DISCOVER_PACK->value,
                        $product['image_url']
                    ),
                    'id' => $product['id'],
                ]
            );
        }
    }
}
