<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421170226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE education_group ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN education_group.id IS \'\'');
        $this->addSql('ALTER TABLE education_sub_group ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE education_sub_group ALTER education_group_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN education_sub_group.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN education_sub_group.education_group_id IS \'\'');
        $this->addSql('ALTER TABLE festival ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE festival ALTER starts_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE festival ALTER ends_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN festival.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN festival.starts_at IS \'\'');
        $this->addSql('COMMENT ON COLUMN festival.ends_at IS \'\'');
        $this->addSql('ALTER TABLE festival_committee_member ALTER festival_id TYPE UUID');
        $this->addSql('ALTER TABLE festival_committee_member ALTER user_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN festival_committee_member.festival_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN festival_committee_member.user_id IS \'\'');
        $this->addSql('ALTER TABLE festival_jury_member ALTER festival_id TYPE UUID');
        $this->addSql('ALTER TABLE festival_jury_member ALTER user_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN festival_jury_member.festival_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN festival_jury_member.user_id IS \'\'');
        $this->addSql('ALTER TABLE festival_mail ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE festival_mail ALTER festival_id TYPE UUID');
        $this->addSql('ALTER TABLE festival_mail ALTER mail_author_id TYPE UUID');
        $this->addSql('ALTER TABLE festival_mail ALTER sent_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN festival_mail.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.festival_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.mail_author_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.sent_at IS \'\'');
        $this->addSql('ALTER TABLE participant ADD account_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE participant ALTER team_id TYPE UUID');
        $this->addSql('ALTER TABLE participant ALTER project_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN participant.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN participant.team_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN participant.project_id IS \'\'');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B119B6B5FBA FOREIGN KEY (account_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D79F6B119B6B5FBA ON participant (account_id)');
        $this->addSql('ALTER TABLE project ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE project ALTER festival_id TYPE UUID');
        $this->addSql('ALTER TABLE project ALTER author_id TYPE UUID');
        $this->addSql('ALTER TABLE project ALTER starts_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE project ALTER ends_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN project.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project.festival_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project.author_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project.starts_at IS \'\'');
        $this->addSql('COMMENT ON COLUMN project.ends_at IS \'\'');
        $this->addSql('ALTER TABLE project_project_subject ALTER project_id TYPE UUID');
        $this->addSql('ALTER TABLE project_project_subject ALTER project_subject_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_project_subject.project_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project_project_subject.project_subject_id IS \'\'');
        $this->addSql('ALTER TABLE project_education_sub_group ALTER project_id TYPE UUID');
        $this->addSql('ALTER TABLE project_education_sub_group ALTER education_sub_group_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_education_sub_group.project_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project_education_sub_group.education_sub_group_id IS \'\'');
        $this->addSql('ALTER TABLE project_author ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE project_author ALTER user_entity_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_author.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project_author.user_entity_id IS \'\'');
        $this->addSql('ALTER TABLE project_award ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE project_award ALTER project_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_award.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project_award.project_id IS \'\'');
        $this->addSql('ALTER TABLE project_state_log ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE project_state_log ALTER project_id TYPE UUID');
        $this->addSql('ALTER TABLE project_state_log ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE project_state_log ALTER performed_by_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_state_log.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project_state_log.project_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN project_state_log.created_at IS \'\'');
        $this->addSql('COMMENT ON COLUMN project_state_log.performed_by_id IS \'\'');
        $this->addSql('ALTER TABLE project_subject ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_subject.id IS \'\'');
        $this->addSql('ALTER TABLE team ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE team ALTER team_creator_id TYPE UUID');
        $this->addSql('ALTER TABLE team ALTER project_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN team.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN team.team_creator_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN team.project_id IS \'\'');
        $this->addSql('ALTER TABLE "user" ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'\'');
        $this->addSql('ALTER TABLE vote ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE vote ALTER jury_member_id TYPE UUID');
        $this->addSql('ALTER TABLE vote ALTER voting_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN vote.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN vote.jury_member_id IS \'\'');
        $this->addSql('COMMENT ON COLUMN vote.voting_id IS \'\'');
        $this->addSql('ALTER TABLE voting ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE voting ALTER project_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN voting.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN voting.project_id IS \'\'');
        $this->addSql('ALTER TABLE messenger_messages ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE messenger_messages ALTER id ADD GENERATED BY DEFAULT AS IDENTITY');
        $this->addSql('ALTER TABLE messenger_messages ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE messenger_messages ALTER available_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE messenger_messages ALTER delivered_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE festival_jury_member ALTER festival_id TYPE UUID');
        $this->addSql('ALTER TABLE festival_jury_member ALTER user_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN festival_jury_member.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_jury_member.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_state_log ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE project_state_log ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE project_state_log ALTER project_id TYPE UUID');
        $this->addSql('ALTER TABLE project_state_log ALTER performed_by_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_state_log.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_state_log.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN project_state_log.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_state_log.performed_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE education_group ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN education_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_author ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE project_author ALTER user_entity_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_author.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_author.user_entity_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE festival_mail ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE festival_mail ALTER sent_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE festival_mail ALTER festival_id TYPE UUID');
        $this->addSql('ALTER TABLE festival_mail ALTER mail_author_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN festival_mail.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.sent_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_mail.mail_author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE messenger_messages ALTER id SET DEFAULT messenger_messages_id_seq');
        $this->addSql('ALTER TABLE messenger_messages ALTER id DROP IDENTITY');
        $this->addSql('ALTER TABLE messenger_messages ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE messenger_messages ALTER available_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE messenger_messages ALTER delivered_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE project_education_sub_group ALTER project_id TYPE UUID');
        $this->addSql('ALTER TABLE project_education_sub_group ALTER education_sub_group_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_education_sub_group.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_education_sub_group.education_sub_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE festival ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE festival ALTER starts_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE festival ALTER ends_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN festival.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival.starts_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN festival.ends_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE festival_committee_member ALTER festival_id TYPE UUID');
        $this->addSql('ALTER TABLE festival_committee_member ALTER user_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN festival_committee_member.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN festival_committee_member.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "user" ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_award ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE project_award ALTER project_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_award.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_award.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE vote ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE vote ALTER jury_member_id TYPE UUID');
        $this->addSql('ALTER TABLE vote ALTER voting_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN vote.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN vote.jury_member_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN vote.voting_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE project ALTER starts_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE project ALTER ends_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE project ALTER festival_id TYPE UUID');
        $this->addSql('ALTER TABLE project ALTER author_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.starts_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN project.ends_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN project.festival_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE education_sub_group ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE education_sub_group ALTER education_group_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN education_sub_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN education_sub_group.education_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE voting ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE voting ALTER project_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN voting.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN voting.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_project_subject ALTER project_id TYPE UUID');
        $this->addSql('ALTER TABLE project_project_subject ALTER project_subject_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_project_subject.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project_project_subject.project_subject_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE team ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE team ALTER team_creator_id TYPE UUID');
        $this->addSql('ALTER TABLE team ALTER project_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN team.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team.team_creator_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN team.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B119B6B5FBA');
        $this->addSql('DROP INDEX IDX_D79F6B119B6B5FBA');
        $this->addSql('ALTER TABLE participant DROP account_id');
        $this->addSql('ALTER TABLE participant ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE participant ALTER team_id TYPE UUID');
        $this->addSql('ALTER TABLE participant ALTER project_id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN participant.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN participant.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN participant.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_subject ALTER id TYPE UUID');
        $this->addSql('COMMENT ON COLUMN project_subject.id IS \'(DC2Type:uuid)\'');
    }
}
