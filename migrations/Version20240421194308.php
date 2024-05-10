<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421194308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_d79f6b119b6b5fba');
        $this->addSql('ALTER TABLE participant DROP acceptance');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B119B6B5FBA ON participant (account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_D79F6B119B6B5FBA');
        $this->addSql('ALTER TABLE participant ADD acceptance INT NOT NULL');
        $this->addSql('CREATE INDEX idx_d79f6b119b6b5fba ON participant (account_id)');
    }
}
