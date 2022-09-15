<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190221170554 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

         $this->addSql('ALTER TABLE organisation ADD country_id INT NOT NULL');

        $this->addSql('UPDATE organisation SET country_id = 1');

        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B4F92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id)');
        $this->addSql('CREATE INDEX IDX_E6E132B4F92F3E70 ON organisation (country_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organisation DROP FOREIGN KEY FK_E6E132B4F92F3E70');
        $this->addSql('DROP INDEX IDX_E6E132B4F92F3E70 ON organisation');
        $this->addSql('ALTER TABLE organisation DROP country_id');
    }
}
