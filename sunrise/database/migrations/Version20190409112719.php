<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190409112719 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship ADD status VARCHAR(16) NOT NULL, ADD active TINYINT(1) NOT NULL, CHANGE verified_email is_free TINYINT(1) NOT NULL');
        $this->addSql('CREATE INDEX ix_scholarship_status ON scholarship (status)');
        $this->addSql('ALTER TABLE scholarship_template CHANGE verified_email is_free TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX ix_scholarship_status ON scholarship');
        $this->addSql('ALTER TABLE scholarship ADD verified_email TINYINT(1) NOT NULL, DROP is_free, DROP status, DROP active');
        $this->addSql('ALTER TABLE scholarship_template CHANGE is_free verified_email TINYINT(1) NOT NULL');
    }
}
