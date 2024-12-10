<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241210183916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add details column to WeekScrap entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE week_scrap ADD details LONGTEXT DEFAULT \'[]\' NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE week_scrap DROP details');
    }
}
