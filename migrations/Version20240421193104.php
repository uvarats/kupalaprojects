<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421193104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_participant DROP CONSTRAINT FK_1F509CEA166D1F9C');
        $this->addSql('ALTER TABLE project_participant DROP CONSTRAINT FK_1F509CEA9D1C3019');
        $this->addSql('ALTER TABLE project_participant DROP CONSTRAINT project_participant_pkey');
        $this->addSql('ALTER TABLE project_participant ADD id UUID NOT NULL');
        $this->addSql('ALTER TABLE project_participant ADD acceptance INT NOT NULL');
        $this->addSql('ALTER TABLE project_participant ADD CONSTRAINT FK_1F509CEA166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_participant ADD CONSTRAINT FK_1F509CEA9D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F509CEA166D1F9C9D1C3019 ON project_participant (project_id, participant_id)');
        $this->addSql('ALTER TABLE project_participant ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_participant DROP CONSTRAINT fk_1f509cea166d1f9c');
        $this->addSql('ALTER TABLE project_participant DROP CONSTRAINT fk_1f509cea9d1c3019');
        $this->addSql('DROP INDEX UNIQ_1F509CEA166D1F9C9D1C3019');
        $this->addSql('DROP INDEX project_participant_pkey');
        $this->addSql('ALTER TABLE project_participant DROP id');
        $this->addSql('ALTER TABLE project_participant DROP acceptance');
        $this->addSql('ALTER TABLE project_participant ADD CONSTRAINT fk_1f509cea166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_participant ADD CONSTRAINT fk_1f509cea9d1c3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_participant ADD PRIMARY KEY (project_id, participant_id)');
    }
}
