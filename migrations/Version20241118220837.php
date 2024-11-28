<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241118220837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add week_scrap table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE week_scrap (id VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE week_scrap');
    }
}
