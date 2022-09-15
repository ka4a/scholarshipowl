<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180813100257 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship CHANGE expiration_date deadline DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scholarship_template CHANGE expiration_date deadline DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scholarship CHANGE start_date start DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scholarship_template CHANGE start_date start DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship CHANGE deadline expiration_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scholarship_template CHANGE deadline expiration_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scholarship CHANGE start start_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scholarship_template CHANGE start start_date DATETIME NOT NULL');
    }
}
