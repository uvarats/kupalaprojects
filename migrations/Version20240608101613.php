<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240608101613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE education_sub_group DROP CONSTRAINT fk_15680f867640a7ce');
        $this->addSql('DROP INDEX idx_15680f867640a7ce');
        $this->addSql('ALTER TABLE education_sub_group ADD allows_projects BOOLEAN DEFAULT true NOT NULL');
        $this->addSql('ALTER TABLE education_sub_group ADD parent_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE education_sub_group DROP education_group_id');
        $this->addSql('ALTER TABLE education_sub_group ADD CONSTRAINT FK_15680F86727ACA70 FOREIGN KEY (parent_id) REFERENCES education_sub_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_15680F86727ACA70 ON education_sub_group (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE education_sub_group DROP CONSTRAINT FK_15680F86727ACA70');
        $this->addSql('DROP INDEX IDX_15680F86727ACA70');
        $this->addSql('ALTER TABLE education_sub_group ADD education_group_id UUID NOT NULL');
        $this->addSql('ALTER TABLE education_sub_group DROP allows_projects');
        $this->addSql('ALTER TABLE education_sub_group DROP parent_id');
        $this->addSql('ALTER TABLE education_sub_group ADD CONSTRAINT fk_15680f867640a7ce FOREIGN KEY (education_group_id) REFERENCES education_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_15680f867640a7ce ON education_sub_group (education_group_id)');
    }
}
