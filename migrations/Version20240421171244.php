<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421171244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT fk_d79f6b11166d1f9c');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT fk_d79f6b11296cd8ae');
        $this->addSql('DROP INDEX uniq_d79f6b11166d1f9ce7927c74');
        $this->addSql('DROP INDEX idx_d79f6b11166d1f9c');
        $this->addSql('DROP INDEX idx_d79f6b11296cd8ae');
        $this->addSql('ALTER TABLE participant DROP team_id');
        $this->addSql('ALTER TABLE participant DROP project_id');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT fk_c4e0a61f5ffb7c63');
        $this->addSql('DROP INDEX uniq_c4e0a61f5ffb7c63');
        $this->addSql('ALTER TABLE team DROP team_creator_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE team ADD team_creator_id UUID NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT fk_c4e0a61f5ffb7c63 FOREIGN KEY (team_creator_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_c4e0a61f5ffb7c63 ON team (team_creator_id)');
        $this->addSql('ALTER TABLE participant ADD team_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ADD project_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_d79f6b11166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_d79f6b11296cd8ae FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_d79f6b11166d1f9ce7927c74 ON participant (project_id, email)');
        $this->addSql('CREATE INDEX idx_d79f6b11166d1f9c ON participant (project_id)');
        $this->addSql('CREATE INDEX idx_d79f6b11296cd8ae ON participant (team_id)');
    }
}
