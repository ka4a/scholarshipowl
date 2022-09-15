<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190313135307 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship ADD scholarship_url VARCHAR(255) DEFAULT NULL, ADD scholarship_p_p_url VARCHAR(255) DEFAULT NULL, ADD scholarship_t_o_s_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE scholarship_template ADD scholarship_url VARCHAR(255) DEFAULT NULL, ADD scholarship_p_p_url VARCHAR(255) DEFAULT NULL, ADD scholarship_t_o_s_url VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship DROP scholarship_url, DROP scholarship_p_p_url, DROP scholarship_t_o_s_url');
        $this->addSql('ALTER TABLE scholarship_template DROP scholarship_url, DROP scholarship_p_p_url, DROP scholarship_t_o_s_url');
    }
}
