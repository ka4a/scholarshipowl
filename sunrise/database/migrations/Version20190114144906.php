<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190114144906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship ADD occurrence INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scholarship_template ADD recurrence_config JSON DEFAULT NULL, DROP start, DROP deadline, DROP recurring_type, DROP recurring_value, DROP recurring_exceptions');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship DROP occurrence');
        $this->addSql('ALTER TABLE scholarship_template ADD start DATETIME DEFAULT NULL, ADD deadline DATETIME DEFAULT NULL, ADD recurring_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD recurring_value SMALLINT DEFAULT NULL, ADD recurring_exceptions JSON NOT NULL COMMENT \'(DC2Type:json_array)\', DROP recurrence_config');
    }
}
