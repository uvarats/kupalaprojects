<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510133735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_team DROP CONSTRAINT FK_FD716E07166D1F9C');
        $this->addSql('ALTER TABLE project_team DROP CONSTRAINT FK_FD716E07296CD8AE');
        $this->addSql('ALTER TABLE project_team DROP CONSTRAINT project_team_pkey');
        $this->addSql('ALTER TABLE project_team ADD id UUID NOT NULL');
        $this->addSql('ALTER TABLE project_team ADD acceptance INT NOT NULL');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT FK_FD716E07166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT FK_FD716E07296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD716E07296CD8AE166D1F9C ON project_team (team_id, project_id)');
        $this->addSql('ALTER TABLE project_team ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_team DROP CONSTRAINT fk_fd716e07296cd8ae');
        $this->addSql('ALTER TABLE project_team DROP CONSTRAINT fk_fd716e07166d1f9c');
        $this->addSql('DROP INDEX UNIQ_FD716E07296CD8AE166D1F9C');
        $this->addSql('DROP INDEX project_team_pkey');
        $this->addSql('ALTER TABLE project_team DROP id');
        $this->addSql('ALTER TABLE project_team DROP acceptance');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT fk_fd716e07296cd8ae FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT fk_fd716e07166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_team ADD PRIMARY KEY (project_id, team_id)');
    }
}
