<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241218212657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user relation to tier list';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tier_list ADD user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tier_list ADD CONSTRAINT FK_C4498D71A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C4498D71A76ED395 ON tier_list (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tier_list DROP FOREIGN KEY FK_C4498D71A76ED395');
        $this->addSql('DROP INDEX IDX_C4498D71A76ED395 ON tier_list');
        $this->addSql('ALTER TABLE tier_list DROP user_id');
    }
}
