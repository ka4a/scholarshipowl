<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20181119085452 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_winners ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE scholarship_website CHANGE domain_hosted domain_hosted TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_winners DROP city');
        $this->addSql('ALTER TABLE scholarship_website CHANGE domain_hosted domain_hosted TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
