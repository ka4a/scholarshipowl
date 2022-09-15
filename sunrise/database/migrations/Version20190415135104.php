<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190415135104 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_fields ADD optional TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE scholarship_template_fields ADD optional TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application CHANGE status_id status_id VARCHAR(16) DEFAULT \'accepted\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE scholarship_fields DROP optional');
        $this->addSql('ALTER TABLE scholarship_template_fields DROP optional');
    }
}
