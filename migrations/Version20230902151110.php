<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230902151110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE survey_question_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE survey_question_answer (id INT NOT NULL, question_id INT NOT NULL, answer_id INT NOT NULL, full_answer TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7554B7191E27F6BF ON survey_question_answer (question_id)');
        $this->addSql('CREATE INDEX IDX_7554B719AA334807 ON survey_question_answer (answer_id)');
        $this->addSql('ALTER TABLE survey_question_answer ADD CONSTRAINT FK_7554B7191E27F6BF FOREIGN KEY (question_id) REFERENCES survey_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question_answer ADD CONSTRAINT FK_7554B719AA334807 FOREIGN KEY (answer_id) REFERENCES survey_answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question_survey_answer DROP CONSTRAINT fk_9de29c18a6df29ba');
        $this->addSql('ALTER TABLE survey_question_survey_answer DROP CONSTRAINT fk_9de29c18f650a2a');
        $this->addSql('DROP TABLE survey_question_survey_answer');
        $this->addSql('ALTER TABLE survey ALTER status SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE survey_question_answer_id_seq CASCADE');
        $this->addSql('CREATE TABLE survey_question_survey_answer (survey_question_id INT NOT NULL, survey_answer_id INT NOT NULL, PRIMARY KEY(survey_question_id, survey_answer_id))');
        $this->addSql('CREATE INDEX idx_9de29c18f650a2a ON survey_question_survey_answer (survey_answer_id)');
        $this->addSql('CREATE INDEX idx_9de29c18a6df29ba ON survey_question_survey_answer (survey_question_id)');
        $this->addSql('ALTER TABLE survey_question_survey_answer ADD CONSTRAINT fk_9de29c18a6df29ba FOREIGN KEY (survey_question_id) REFERENCES survey_question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question_survey_answer ADD CONSTRAINT fk_9de29c18f650a2a FOREIGN KEY (survey_answer_id) REFERENCES survey_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question_answer DROP CONSTRAINT FK_7554B7191E27F6BF');
        $this->addSql('ALTER TABLE survey_question_answer DROP CONSTRAINT FK_7554B719AA334807');
        $this->addSql('DROP TABLE survey_question_answer');
        $this->addSql('ALTER TABLE survey ALTER status DROP NOT NULL');
    }
}
