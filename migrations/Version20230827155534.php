<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230827155534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE survey_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE survey_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE survey_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE survey (id INT NOT NULL, created_by_id INT NOT NULL, image VARCHAR(255) NOT NULL, title VARCHAR(1000) NOT NULL, status SMALLINT NOT NULL, description TEXT DEFAULT NULL, expire_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AD5F9BFCB03A8386 ON survey (created_by_id)');
        $this->addSql('COMMENT ON COLUMN survey.expire_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE survey_answer (id INT NOT NULL, survey_id INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F2D38249B3FE509D ON survey_answer (survey_id)');
        $this->addSql('COMMENT ON COLUMN survey_answer.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN survey_answer.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE survey_question (id INT NOT NULL, survey_id INT NOT NULL, type VARCHAR(45) NOT NULL, question VARCHAR(2000) NOT NULL, description TEXT DEFAULT NULL, data TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA000F69B3FE509D ON survey_question (survey_id)');
        $this->addSql('CREATE TABLE survey_question_survey_answer (survey_question_id INT NOT NULL, survey_answer_id INT NOT NULL, PRIMARY KEY(survey_question_id, survey_answer_id))');
        $this->addSql('CREATE INDEX IDX_9DE29C18A6DF29BA ON survey_question_survey_answer (survey_question_id)');
        $this->addSql('CREATE INDEX IDX_9DE29C18F650A2A ON survey_question_survey_answer (survey_answer_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE survey ADD CONSTRAINT FK_AD5F9BFCB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_answer ADD CONSTRAINT FK_F2D38249B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question ADD CONSTRAINT FK_EA000F69B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question_survey_answer ADD CONSTRAINT FK_9DE29C18A6DF29BA FOREIGN KEY (survey_question_id) REFERENCES survey_question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question_survey_answer ADD CONSTRAINT FK_9DE29C18F650A2A FOREIGN KEY (survey_answer_id) REFERENCES survey_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE survey_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE survey_answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE survey_question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE survey DROP CONSTRAINT FK_AD5F9BFCB03A8386');
        $this->addSql('ALTER TABLE survey_answer DROP CONSTRAINT FK_F2D38249B3FE509D');
        $this->addSql('ALTER TABLE survey_question DROP CONSTRAINT FK_EA000F69B3FE509D');
        $this->addSql('ALTER TABLE survey_question_survey_answer DROP CONSTRAINT FK_9DE29C18A6DF29BA');
        $this->addSql('ALTER TABLE survey_question_survey_answer DROP CONSTRAINT FK_9DE29C18F650A2A');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE survey_answer');
        $this->addSql('DROP TABLE survey_question');
        $this->addSql('DROP TABLE survey_question_survey_answer');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
