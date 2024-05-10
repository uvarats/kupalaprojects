<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130195931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_state_log (id UUID NOT NULL, project_id UUID NOT NULL, from_state VARCHAR(255) NOT NULL, to_state VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_19B16F9B166D1F9C ON project_state_log (project_id)');
        $this->addSql('COMMENT ON COLUMN project_state_log.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_state_log.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_state_log.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE project_state_log ADD CONSTRAINT FK_19B16F9B166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_state_log DROP CONSTRAINT FK_19B16F9B166D1F9C');
        $this->addSql('DROP TABLE project_state_log');
    }
}
