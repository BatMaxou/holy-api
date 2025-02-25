<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250225205046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change details column type to LONGTEXT';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE week_scrap CHANGE details details LONGTEXT DEFAULT \'[]\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE week_scrap CHANGE details details LONGTEXT DEFAULT \'[]\' NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
