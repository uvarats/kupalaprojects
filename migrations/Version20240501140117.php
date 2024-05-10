<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501140117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_team (project_id UUID NOT NULL, team_id UUID NOT NULL, PRIMARY KEY(project_id, team_id))');
        $this->addSql('CREATE INDEX IDX_FD716E07166D1F9C ON project_team (project_id)');
        $this->addSql('CREATE INDEX IDX_FD716E07296CD8AE ON project_team (team_id)');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT FK_FD716E07166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT FK_FD716E07296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT fk_c4e0a61f166d1f9c');
        $this->addSql('DROP INDEX idx_c4e0a61f166d1f9c');
        $this->addSql('ALTER TABLE team DROP project_id');
        $this->addSql('ALTER TABLE team_invite DROP CONSTRAINT fk_b1f9570e784bb717');
        $this->addSql('DROP INDEX idx_b1f9570e784bb717');
        $this->addSql('ALTER TABLE team_invite RENAME COLUMN issued_by_id TO issuer_id');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570EBB9D6FEE FOREIGN KEY (issuer_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B1F9570EBB9D6FEE ON team_invite (issuer_id)');
        $this->addSql('ALTER TABLE team_participant ADD joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE team_participant ADD left_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_team DROP CONSTRAINT FK_FD716E07166D1F9C');
        $this->addSql('ALTER TABLE project_team DROP CONSTRAINT FK_FD716E07296CD8AE');
        $this->addSql('DROP TABLE project_team');
        $this->addSql('ALTER TABLE team ADD project_id UUID NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT fk_c4e0a61f166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c4e0a61f166d1f9c ON team (project_id)');
        $this->addSql('ALTER TABLE team_participant DROP joined_at');
        $this->addSql('ALTER TABLE team_participant DROP left_at');
        $this->addSql('ALTER TABLE team_invite DROP CONSTRAINT FK_B1F9570EBB9D6FEE');
        $this->addSql('DROP INDEX IDX_B1F9570EBB9D6FEE');
        $this->addSql('ALTER TABLE team_invite RENAME COLUMN issuer_id TO issued_by_id');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT fk_b1f9570e784bb717 FOREIGN KEY (issued_by_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b1f9570e784bb717 ON team_invite (issued_by_id)');
    }
}
