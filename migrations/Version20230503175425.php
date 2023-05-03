<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503175425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id UUID NOT NULL, team_id UUID DEFAULT NULL, project_id UUID DEFAULT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, middle_name VARCHAR(255) DEFAULT NULL, education_establishment VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D79F6B11296CD8AE ON participant (team_id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11166D1F9C ON participant (project_id)');
        $this->addSql('COMMENT ON COLUMN participant.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN participant.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN participant.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE team (id UUID NOT NULL, team_creator_id UUID NOT NULL, project_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61F5FFB7C63 ON team (team_creator_id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F166D1F9C ON team (project_id)');
        $this->addSql('COMMENT ON COLUMN team.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team.team_creator_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F5FFB7C63 FOREIGN KEY (team_creator_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B11296CD8AE');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B11166D1F9C');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F5FFB7C63');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F166D1F9C');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE team');
    }
}
