<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230418211924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE festival_jury_member (festival_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(festival_id, user_id))');
        $this->addSql('CREATE INDEX IDX_15A427D88AEBAF57 ON festival_jury_member (festival_id)');
        $this->addSql('CREATE INDEX IDX_15A427D8A76ED395 ON festival_jury_member (user_id)');
        $this->addSql('COMMENT ON COLUMN festival_jury_member.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_jury_member.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE festival_jury_member ADD CONSTRAINT FK_15A427D88AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE festival_jury_member ADD CONSTRAINT FK_15A427D8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE festival_jury_member DROP CONSTRAINT FK_15A427D88AEBAF57');
        $this->addSql('ALTER TABLE festival_jury_member DROP CONSTRAINT FK_15A427D8A76ED395');
        $this->addSql('DROP TABLE festival_jury_member');
    }
}
