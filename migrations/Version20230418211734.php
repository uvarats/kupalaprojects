<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230418211734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE festival (id UUID NOT NULL, name VARCHAR(255) NOT NULL, starts_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ends_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN festival.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival.starts_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN festival.ends_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE festival_committee_member (festival_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(festival_id, user_id))');
        $this->addSql('CREATE INDEX IDX_CD09CA8B8AEBAF57 ON festival_committee_member (festival_id)');
        $this->addSql('CREATE INDEX IDX_CD09CA8BA76ED395 ON festival_committee_member (user_id)');
        $this->addSql('COMMENT ON COLUMN festival_committee_member.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_committee_member.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project (id UUID NOT NULL, name VARCHAR(255) NOT NULL, site_url VARCHAR(255) NOT NULL, creation_year INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_project_subject (project_id UUID NOT NULL, project_subject_id UUID NOT NULL, PRIMARY KEY(project_id, project_subject_id))');
        $this->addSql('CREATE INDEX IDX_55E147EF166D1F9C ON project_project_subject (project_id)');
        $this->addSql('CREATE INDEX IDX_55E147EF1A98A7B5 ON project_project_subject (project_subject_id)');
        $this->addSql('COMMENT ON COLUMN project_project_subject.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_project_subject.project_subject_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_subject (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN project_subject.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE festival_committee_member ADD CONSTRAINT FK_CD09CA8B8AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE festival_committee_member ADD CONSTRAINT FK_CD09CA8BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_project_subject ADD CONSTRAINT FK_55E147EF166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_project_subject ADD CONSTRAINT FK_55E147EF1A98A7B5 FOREIGN KEY (project_subject_id) REFERENCES project_subject (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD last_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD middle_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE festival_committee_member DROP CONSTRAINT FK_CD09CA8B8AEBAF57');
        $this->addSql('ALTER TABLE festival_committee_member DROP CONSTRAINT FK_CD09CA8BA76ED395');
        $this->addSql('ALTER TABLE project_project_subject DROP CONSTRAINT FK_55E147EF166D1F9C');
        $this->addSql('ALTER TABLE project_project_subject DROP CONSTRAINT FK_55E147EF1A98A7B5');
        $this->addSql('DROP TABLE festival');
        $this->addSql('DROP TABLE festival_committee_member');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_project_subject');
        $this->addSql('DROP TABLE project_subject');
        $this->addSql('ALTER TABLE "user" DROP last_name');
        $this->addSql('ALTER TABLE "user" DROP first_name');
        $this->addSql('ALTER TABLE "user" DROP middle_name');
    }
}
