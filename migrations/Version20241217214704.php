<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241217214704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (id VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, otp VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user');
    }
}
