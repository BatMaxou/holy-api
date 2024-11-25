<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241125162103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add TierList and RankedProduct entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE ranked_product (id VARCHAR(255) NOT NULL, tier_list_id VARCHAR(255) NOT NULL, product_id VARCHAR(255) NOT NULL, tier VARCHAR(255) DEFAULT NULL, INDEX IDX_4F2886BBB25FD8A1 (tier_list_id), INDEX IDX_4F2886BB4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tier_list (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ranked_product ADD CONSTRAINT FK_4F2886BBB25FD8A1 FOREIGN KEY (tier_list_id) REFERENCES tier_list (id)');
        $this->addSql('ALTER TABLE ranked_product ADD CONSTRAINT FK_4F2886BB4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ranked_product DROP FOREIGN KEY FK_4F2886BBB25FD8A1');
        $this->addSql('ALTER TABLE ranked_product DROP FOREIGN KEY FK_4F2886BB4584665A');
        $this->addSql('DROP TABLE ranked_product');
        $this->addSql('DROP TABLE tier_list');
    }
}
