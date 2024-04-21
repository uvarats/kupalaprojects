<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421172935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_participant (project_id UUID NOT NULL, participant_id UUID NOT NULL, PRIMARY KEY(project_id, participant_id))');
        $this->addSql('CREATE INDEX IDX_1F509CEA166D1F9C ON project_participant (project_id)');
        $this->addSql('CREATE INDEX IDX_1F509CEA9D1C3019 ON project_participant (participant_id)');
        $this->addSql('CREATE TABLE team_participant (id UUID NOT NULL, role VARCHAR(255) NOT NULL, participant_id UUID NOT NULL, team_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3CAF845C9D1C3019 ON team_participant (participant_id)');
        $this->addSql('CREATE INDEX IDX_3CAF845C296CD8AE ON team_participant (team_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3CAF845C9D1C3019296CD8AE ON team_participant (participant_id, team_id)');
        $this->addSql('ALTER TABLE project_participant ADD CONSTRAINT FK_1F509CEA166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_participant ADD CONSTRAINT FK_1F509CEA9D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_participant ADD CONSTRAINT FK_3CAF845C9D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_participant ADD CONSTRAINT FK_3CAF845C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_participant DROP CONSTRAINT FK_1F509CEA166D1F9C');
        $this->addSql('ALTER TABLE project_participant DROP CONSTRAINT FK_1F509CEA9D1C3019');
        $this->addSql('ALTER TABLE team_participant DROP CONSTRAINT FK_3CAF845C9D1C3019');
        $this->addSql('ALTER TABLE team_participant DROP CONSTRAINT FK_3CAF845C296CD8AE');
        $this->addSql('DROP TABLE project_participant');
        $this->addSql('DROP TABLE team_participant');
    }
}
