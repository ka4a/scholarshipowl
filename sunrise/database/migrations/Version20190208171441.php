<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190208171441 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE application_batches (id INT AUTO_INCREMENT NOT NULL, data JSON NOT NULL COMMENT \'(DC2Type:json)\', status VARCHAR(16) NOT NULL, source VARCHAR(255) NOT NULL, eligible INT DEFAULT NULL, applied INT DEFAULT NULL, errors INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application_application_batch (application_batch_id INT NOT NULL, application_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_1017FFA24CA56169 (application_batch_id), INDEX IDX_1017FFA23E030ACD (application_id), PRIMARY KEY(application_batch_id, application_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application_application_batch ADD CONSTRAINT FK_1017FFA24CA56169 FOREIGN KEY (application_batch_id) REFERENCES application_batches (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE application_application_batch ADD CONSTRAINT FK_1017FFA23E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_application_batch DROP FOREIGN KEY FK_1017FFA24CA56169');
        $this->addSql('DROP TABLE application_batches');
        $this->addSql('DROP TABLE application_application_batch');
    }
}
