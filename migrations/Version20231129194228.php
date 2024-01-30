<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129194228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vote (id UUID NOT NULL, jury_member_id UUID NOT NULL, voting_id UUID NOT NULL, decision INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A108564BE158A6A ON vote (jury_member_id)');
        $this->addSql('CREATE INDEX IDX_5A1085644254ACF8 ON vote (voting_id)');
        $this->addSql('COMMENT ON COLUMN vote.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN vote.jury_member_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN vote.voting_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE voting (id UUID NOT NULL, project_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC28DA55166D1F9C ON voting (project_id)');
        $this->addSql('COMMENT ON COLUMN voting.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN voting.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564BE158A6A FOREIGN KEY (jury_member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085644254ACF8 FOREIGN KEY (voting_id) REFERENCES voting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vote DROP CONSTRAINT FK_5A108564BE158A6A');
        $this->addSql('ALTER TABLE vote DROP CONSTRAINT FK_5A1085644254ACF8');
        $this->addSql('ALTER TABLE voting DROP CONSTRAINT FK_FC28DA55166D1F9C');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP TABLE voting');
    }
}
