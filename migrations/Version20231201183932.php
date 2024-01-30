<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201183932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_state_log ADD performed_by_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN project_state_log.performed_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_state_log ADD CONSTRAINT FK_19B16F9B2E65C292 FOREIGN KEY (performed_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_19B16F9B2E65C292 ON project_state_log (performed_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_state_log DROP CONSTRAINT FK_19B16F9B2E65C292');
        $this->addSql('DROP INDEX IDX_19B16F9B2E65C292');
        $this->addSql('ALTER TABLE project_state_log DROP performed_by_id');
    }
}
