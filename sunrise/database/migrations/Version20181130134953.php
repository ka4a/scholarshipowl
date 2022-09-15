<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20181130134953 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_fields CHANGE eligibility_type eligibility_type VARCHAR(8) DEFAULT NULL, CHANGE eligibility_value eligibility_value VARCHAR(1024) DEFAULT NULL');
        $this->addSql('ALTER TABLE scholarship_template_fields CHANGE eligibility_type eligibility_type VARCHAR(8) DEFAULT NULL, CHANGE eligibility_value eligibility_value VARCHAR(1024) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_fields CHANGE eligibility_type eligibility_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE eligibility_value eligibility_value VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE scholarship_template_fields CHANGE eligibility_type eligibility_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE eligibility_value eligibility_value VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
