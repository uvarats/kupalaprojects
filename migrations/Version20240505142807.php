<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505142807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE team ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE team DROP acceptance');
        $this->addSql('ALTER TABLE team_invite ADD team_id UUID NOT NULL');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B1F9570E296CD8AE ON team_invite (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team ADD acceptance INT NOT NULL');
        $this->addSql('ALTER TABLE team DROP created_at');
        $this->addSql('ALTER TABLE team DROP updated_at');
        $this->addSql('ALTER TABLE team_invite DROP CONSTRAINT FK_B1F9570E296CD8AE');
        $this->addSql('DROP INDEX IDX_B1F9570E296CD8AE');
        $this->addSql('ALTER TABLE team_invite DROP team_id');
    }
}
