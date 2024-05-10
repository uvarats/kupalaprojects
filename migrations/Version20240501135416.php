<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501135416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team_invite (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, status VARCHAR(255) NOT NULL, issued_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, issued_by_id UUID NOT NULL, recipient_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B1F9570E784BB717 ON team_invite (issued_by_id)');
        $this->addSql('CREATE INDEX IDX_B1F9570EE92F8F78 ON team_invite (recipient_id)');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570E784BB717 FOREIGN KEY (issued_by_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570EE92F8F78 FOREIGN KEY (recipient_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team_invite DROP CONSTRAINT FK_B1F9570E784BB717');
        $this->addSql('ALTER TABLE team_invite DROP CONSTRAINT FK_B1F9570EE92F8F78');
        $this->addSql('DROP TABLE team_invite');
    }
}
