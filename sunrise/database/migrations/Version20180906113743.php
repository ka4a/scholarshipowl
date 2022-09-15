<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180906113743 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_website DROP FOREIGN KEY FK_D0A5E99D9C7120AF');
        $this->addSql('DROP INDEX UNIQ_D0A5E99D9C7120AF ON scholarship_website');
        $this->addSql('ALTER TABLE scholarship_website DROP scholarship_template_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_website ADD scholarship_template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scholarship_website ADD CONSTRAINT FK_D0A5E99D9C7120AF FOREIGN KEY (scholarship_template_id) REFERENCES scholarship_template (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0A5E99D9C7120AF ON scholarship_website (scholarship_template_id)');
    }
}
