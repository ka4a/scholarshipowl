<?php

namespace Database\Migrations;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190206212658 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        try {
            $schema->getTable('scholarship_website_files');
            $this->skipIf(true, 'Table already exists');
        } catch (SchemaException $e) {
            $this->addSql('CREATE TABLE scholarship_website_files (id INT AUTO_INCREMENT NOT NULL, website_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, size NUMERIC(10, 0) NOT NULL, INDEX IDX_FF0DD0F118F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('ALTER TABLE scholarship_website_files ADD CONSTRAINT FK_FF0DD0F118F45C82 FOREIGN KEY (website_id) REFERENCES scholarship_website (id)');
            $this->addSql('ALTER TABLE scholarship_website ADD logo_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE scholarship_website ADD CONSTRAINT FK_D0A5E99DF98F144A FOREIGN KEY (logo_id) REFERENCES scholarship_website_files (id)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_D0A5E99DF98F144A ON scholarship_website (logo_id)');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_website DROP FOREIGN KEY FK_D0A5E99DF98F144A');
        $this->addSql('DROP TABLE scholarship_website_files');
        $this->addSql('DROP INDEX UNIQ_D0A5E99DF98F144A ON scholarship_website');
        $this->addSql('ALTER TABLE scholarship_website DROP logo_id');
    }
}
