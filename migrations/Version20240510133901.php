<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510133901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_participant ALTER acceptance TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE project_participant ALTER acceptance TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE project_team ALTER acceptance TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE project_team ALTER acceptance TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_team ALTER acceptance TYPE INT');
        $this->addSql('ALTER TABLE project_team ALTER acceptance TYPE INT');
        $this->addSql('ALTER TABLE project_participant ALTER acceptance TYPE INT');
        $this->addSql('ALTER TABLE project_participant ALTER acceptance TYPE INT');
    }
}
