<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430163203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE education_group (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN education_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE education_sub_group (id UUID NOT NULL, education_group_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_15680F867640A7CE ON education_sub_group (education_group_id)');
        $this->addSql('COMMENT ON COLUMN education_sub_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN education_sub_group.education_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE project_education_sub_group (project_id UUID NOT NULL, education_sub_group_id UUID NOT NULL, PRIMARY KEY(project_id, education_sub_group_id))');
        $this->addSql('CREATE INDEX IDX_2F6B0CB6166D1F9C ON project_education_sub_group (project_id)');
        $this->addSql('CREATE INDEX IDX_2F6B0CB6470857B2 ON project_education_sub_group (education_sub_group_id)');
        $this->addSql('COMMENT ON COLUMN project_education_sub_group.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_education_sub_group.education_sub_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE education_sub_group ADD CONSTRAINT FK_15680F867640A7CE FOREIGN KEY (education_group_id) REFERENCES education_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_education_sub_group ADD CONSTRAINT FK_2F6B0CB6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_education_sub_group ADD CONSTRAINT FK_2F6B0CB6470857B2 FOREIGN KEY (education_sub_group_id) REFERENCES education_sub_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project DROP oriented_on');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE education_sub_group DROP CONSTRAINT FK_15680F867640A7CE');
        $this->addSql('ALTER TABLE project_education_sub_group DROP CONSTRAINT FK_2F6B0CB6166D1F9C');
        $this->addSql('ALTER TABLE project_education_sub_group DROP CONSTRAINT FK_2F6B0CB6470857B2');
        $this->addSql('DROP TABLE education_group');
        $this->addSql('DROP TABLE education_sub_group');
        $this->addSql('DROP TABLE project_education_sub_group');
        $this->addSql('ALTER TABLE project ADD oriented_on VARCHAR(255) DEFAULT NULL');
    }
}
