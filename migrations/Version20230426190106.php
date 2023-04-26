<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426190106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE festival (id UUID NOT NULL, name VARCHAR(255) NOT NULL, starts_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ends_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN festival.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival.starts_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN festival.ends_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE festival_committee_member (festival_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(festival_id, user_id))');
        $this->addSql('CREATE INDEX IDX_CD09CA8B8AEBAF57 ON festival_committee_member (festival_id)');
        $this->addSql('CREATE INDEX IDX_CD09CA8BA76ED395 ON festival_committee_member (user_id)');
        $this->addSql('COMMENT ON COLUMN festival_committee_member.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_committee_member.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE festival_jury_member (festival_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(festival_id, user_id))');
        $this->addSql('CREATE INDEX IDX_15A427D88AEBAF57 ON festival_jury_member (festival_id)');
        $this->addSql('CREATE INDEX IDX_15A427D8A76ED395 ON festival_jury_member (user_id)');
        $this->addSql('COMMENT ON COLUMN festival_jury_member.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_jury_member.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project (id UUID NOT NULL, festival_id UUID NOT NULL, author_id UUID NOT NULL, name VARCHAR(255) NOT NULL, site_url VARCHAR(255) NOT NULL, creation_year INT NOT NULL, oriented_on VARCHAR(255) DEFAULT NULL, state VARCHAR(50) NOT NULL, starts_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ends_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE8AEBAF57 ON project (festival_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEF675F31B ON project (author_id)');
        $this->addSql('COMMENT ON COLUMN project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.starts_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN project.ends_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE project_project_subject (project_id UUID NOT NULL, project_subject_id UUID NOT NULL, PRIMARY KEY(project_id, project_subject_id))');
        $this->addSql('CREATE INDEX IDX_55E147EF166D1F9C ON project_project_subject (project_id)');
        $this->addSql('CREATE INDEX IDX_55E147EF1A98A7B5 ON project_project_subject (project_subject_id)');
        $this->addSql('COMMENT ON COLUMN project_project_subject.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_project_subject.project_subject_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_author (id UUID NOT NULL, user_entity_id UUID NOT NULL, place_of_work VARCHAR(255) NOT NULL, occupation VARCHAR(255) NOT NULL, reserve_email VARCHAR(75) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA0B338281C5F0B9 ON project_author (user_entity_id)');
        $this->addSql('COMMENT ON COLUMN project_author.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_author.user_entity_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_award (id UUID NOT NULL, project_id UUID NOT NULL, name VARCHAR(255) NOT NULL, diploma_link VARCHAR(500) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_990E2779166D1F9C ON project_award (project_id)');
        $this->addSql('COMMENT ON COLUMN project_award.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_award.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_subject (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN project_subject.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, middle_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE festival_committee_member ADD CONSTRAINT FK_CD09CA8B8AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE festival_committee_member ADD CONSTRAINT FK_CD09CA8BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE festival_jury_member ADD CONSTRAINT FK_15A427D88AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE festival_jury_member ADD CONSTRAINT FK_15A427D8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE8AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEF675F31B FOREIGN KEY (author_id) REFERENCES project_author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_project_subject ADD CONSTRAINT FK_55E147EF166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_project_subject ADD CONSTRAINT FK_55E147EF1A98A7B5 FOREIGN KEY (project_subject_id) REFERENCES project_subject (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_author ADD CONSTRAINT FK_AA0B338281C5F0B9 FOREIGN KEY (user_entity_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_award ADD CONSTRAINT FK_990E2779166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE festival_committee_member DROP CONSTRAINT FK_CD09CA8B8AEBAF57');
        $this->addSql('ALTER TABLE festival_committee_member DROP CONSTRAINT FK_CD09CA8BA76ED395');
        $this->addSql('ALTER TABLE festival_jury_member DROP CONSTRAINT FK_15A427D88AEBAF57');
        $this->addSql('ALTER TABLE festival_jury_member DROP CONSTRAINT FK_15A427D8A76ED395');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE8AEBAF57');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEF675F31B');
        $this->addSql('ALTER TABLE project_project_subject DROP CONSTRAINT FK_55E147EF166D1F9C');
        $this->addSql('ALTER TABLE project_project_subject DROP CONSTRAINT FK_55E147EF1A98A7B5');
        $this->addSql('ALTER TABLE project_author DROP CONSTRAINT FK_AA0B338281C5F0B9');
        $this->addSql('ALTER TABLE project_award DROP CONSTRAINT FK_990E2779166D1F9C');
        $this->addSql('DROP TABLE festival');
        $this->addSql('DROP TABLE festival_committee_member');
        $this->addSql('DROP TABLE festival_jury_member');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_project_subject');
        $this->addSql('DROP TABLE project_author');
        $this->addSql('DROP TABLE project_award');
        $this->addSql('DROP TABLE project_subject');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
