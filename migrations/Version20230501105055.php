<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501105055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE festival_mail (id UUID NOT NULL, festival_id UUID NOT NULL, mail_author_id UUID NOT NULL, subject VARCHAR(255) NOT NULL, content TEXT NOT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8BD845DF8AEBAF57 ON festival_mail (festival_id)');
        $this->addSql('CREATE INDEX IDX_8BD845DF8EAFED78 ON festival_mail (mail_author_id)');
        $this->addSql('COMMENT ON COLUMN festival_mail.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.mail_author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.sent_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE festival_mail ADD CONSTRAINT FK_8BD845DF8AEBAF57 FOREIGN KEY (festival_id) REFERENCES festival (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE festival_mail ADD CONSTRAINT FK_8BD845DF8EAFED78 FOREIGN KEY (mail_author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE festival_mail DROP CONSTRAINT FK_8BD845DF8AEBAF57');
        $this->addSql('ALTER TABLE festival_mail DROP CONSTRAINT FK_8BD845DF8EAFED78');
        $this->addSql('DROP TABLE festival_mail');
    }
}
