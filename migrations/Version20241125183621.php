<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241125183621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Datetime && number of product added to WeekScrap entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE week_scrap ADD date DATETIME DEFAULT \'0000-00-00\' NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD product_added INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE week_scrap DROP date, DROP product_added');
    }
}
