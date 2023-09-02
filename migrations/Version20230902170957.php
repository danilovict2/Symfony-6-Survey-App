<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230902170957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE survey_question_answer DROP CONSTRAINT FK_7554B7191E27F6BF');
        $this->addSql('ALTER TABLE survey_question_answer DROP CONSTRAINT FK_7554B719AA334807');
        $this->addSql('ALTER TABLE survey_question_answer ADD CONSTRAINT FK_7554B7191E27F6BF FOREIGN KEY (question_id) REFERENCES survey_question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question_answer ADD CONSTRAINT FK_7554B719AA334807 FOREIGN KEY (answer_id) REFERENCES survey_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE survey_question_answer DROP CONSTRAINT FK_7554B7191E27F6BF');
        $this->addSql('ALTER TABLE survey_question_answer DROP CONSTRAINT FK_7554B719AA334807');
        $this->addSql('ALTER TABLE survey_question_answer ADD CONSTRAINT FK_7554B7191E27F6BF FOREIGN KEY (question_id) REFERENCES survey_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey_question_answer ADD CONSTRAINT FK_7554B719AA334807 FOREIGN KEY (answer_id) REFERENCES survey_answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
