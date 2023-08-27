<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20230827172358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE survey ALTER slug TYPE VARCHAR(1000)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD5F9BFC2B36786B ON survey (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD5F9BFC989D9B62 ON survey (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_AD5F9BFC2B36786B');
        $this->addSql('DROP INDEX UNIQ_AD5F9BFC989D9B62');
        $this->addSql('ALTER TABLE survey ALTER slug TYPE VARCHAR(255)');
    }
}
