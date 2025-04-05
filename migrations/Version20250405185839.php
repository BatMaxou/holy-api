<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Enum\ProductRangeEnum;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405185839 extends AbstractMigration
{
    public const ECHANTILLON = [
        'Échantillon HOLY Energy®',
        'Échantillon HOLY Iced Tea®',
        'Échantillon HOLY Hydration®',
    ];

    public function getDescription(): string
    {
        return 'Handle echantillons';
    }

    public function up(Schema $schema): void
    {
        $products = $this->connection->fetchAllAssociative(
            'SELECT * FROM product WHERE name in (?, ?, ?)',
            [...self::ECHANTILLON]
        );

        foreach ($products as $product) {
            $this->addSql(
                'UPDATE product SET product_range = :product_range, image_url = :image_url, discr = :discr WHERE id = :id',
                [
                    'product_range' => ProductRangeEnum::DEFAULT->value,
                    'image_url' => str_replace(
                        ProductRangeEnum::HYDRATION->value,
                        ProductRangeEnum::DEFAULT->value,
                        $product['image_url']
                    ),
                    'discr' => 'product',
                    'id' => $product['id'],
                ]
            );

            $this->addSql(
                'DELETE FROM flavour WHERE id = :id',
                [
                    'id' => $product['id'],
                ]
            );

            $this->addSql(
                'DELETE FROM ranked_product WHERE product_id = :product_id',
                [
                    'product_id' => $product['id'],
                ]
            );
        }
    }

    public function down(Schema $schema): void {}
}
