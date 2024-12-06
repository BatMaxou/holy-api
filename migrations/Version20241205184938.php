<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241205184938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add order number to ranked product and change tier to not nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ranked_product CHANGE tier tier VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ranked_product ADD order_number INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ranked_product DROP order_number');
        $this->addSql('ALTER TABLE ranked_product CHANGE tier tier VARCHAR(255) DEFAULT NULL');
    }
}
