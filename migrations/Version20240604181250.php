<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604181250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_report_finalists (project_report_id UUID NOT NULL, project_participant_id UUID NOT NULL, PRIMARY KEY(project_report_id, project_participant_id))');
        $this->addSql('CREATE INDEX IDX_43C07A9E5AEEE117 ON project_report_finalists (project_report_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_43C07A9E74AB04BB ON project_report_finalists (project_participant_id)');
        $this->addSql('ALTER TABLE project_report_finalists ADD CONSTRAINT FK_43C07A9E5AEEE117 FOREIGN KEY (project_report_id) REFERENCES project_report (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_report_finalists ADD CONSTRAINT FK_43C07A9E74AB04BB FOREIGN KEY (project_participant_id) REFERENCES project_participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_report ADD report_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project_report ADD protocol_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project_report ADD news_url VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_report_finalists DROP CONSTRAINT FK_43C07A9E5AEEE117');
        $this->addSql('ALTER TABLE project_report_finalists DROP CONSTRAINT FK_43C07A9E74AB04BB');
        $this->addSql('DROP TABLE project_report_finalists');
        $this->addSql('ALTER TABLE project_report DROP report_url');
        $this->addSql('ALTER TABLE project_report DROP protocol_url');
        $this->addSql('ALTER TABLE project_report DROP news_url');
    }
}
