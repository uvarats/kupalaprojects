<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606174328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_report_team_finalists (project_report_id UUID NOT NULL, project_team_id UUID NOT NULL, PRIMARY KEY(project_report_id, project_team_id))');
        $this->addSql('CREATE INDEX IDX_71373B3F5AEEE117 ON project_report_team_finalists (project_report_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_71373B3FBF72D4CB ON project_report_team_finalists (project_team_id)');
        $this->addSql('ALTER TABLE project_report_team_finalists ADD CONSTRAINT FK_71373B3F5AEEE117 FOREIGN KEY (project_report_id) REFERENCES project_report (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_report_team_finalists ADD CONSTRAINT FK_71373B3FBF72D4CB FOREIGN KEY (project_team_id) REFERENCES project_team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_report_team_finalists DROP CONSTRAINT FK_71373B3F5AEEE117');
        $this->addSql('ALTER TABLE project_report_team_finalists DROP CONSTRAINT FK_71373B3FBF72D4CB');
        $this->addSql('DROP TABLE project_report_team_finalists');
    }
}
