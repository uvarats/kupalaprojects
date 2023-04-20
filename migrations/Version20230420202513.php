<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230420202513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE project_author_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_award_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE project_author (id INT NOT NULL, user_entity_id UUID NOT NULL, place_of_work VARCHAR(255) NOT NULL, occupation VARCHAR(255) NOT NULL, reserve_email VARCHAR(75) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA0B338281C5F0B9 ON project_author (user_entity_id)');
        $this->addSql('COMMENT ON COLUMN project_author.user_entity_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_award (id INT NOT NULL, project_id UUID NOT NULL, name VARCHAR(255) NOT NULL, diploma_link VARCHAR(500) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_990E2779166D1F9C ON project_award (project_id)');
        $this->addSql('COMMENT ON COLUMN project_award.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_author ADD CONSTRAINT FK_AA0B338281C5F0B9 FOREIGN KEY (user_entity_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_award ADD CONSTRAINT FK_990E2779166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD oriented_on VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEF675F31B FOREIGN KEY (author_id) REFERENCES project_author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEF675F31B ON project (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEF675F31B');
        $this->addSql('DROP SEQUENCE project_author_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_award_id_seq CASCADE');
        $this->addSql('ALTER TABLE project_author DROP CONSTRAINT FK_AA0B338281C5F0B9');
        $this->addSql('ALTER TABLE project_award DROP CONSTRAINT FK_990E2779166D1F9C');
        $this->addSql('DROP TABLE project_author');
        $this->addSql('DROP TABLE project_award');
        $this->addSql('DROP INDEX IDX_2FB3D0EEF675F31B');
        $this->addSql('ALTER TABLE project DROP author_id');
        $this->addSql('ALTER TABLE project DROP oriented_on');
    }
}
