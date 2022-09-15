<?php

namespace Database\Migrations;

use App\Entities\ApplicationStatus;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180911173245 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE application_statuses (id VARCHAR(16) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO application_statuses VALUES (?, ?)', [
            ApplicationStatus::RECEIVED,
            'Received',
        ]);
        $this->addSql('INSERT INTO application_statuses VALUES (?, ?)', [
            ApplicationStatus::REVIEW,
            'Processed',
        ]);
        $this->addSql('INSERT INTO application_statuses VALUES (?, ?)', [
            ApplicationStatus::ACCEPTED,
            'Accepted',
        ]);
        $this->addSql('INSERT INTO application_statuses VALUES (?, ?)', [
            ApplicationStatus::REJECTED,
            'Rejected',
        ]);

        $this->addSql('ALTER TABLE application ADD status_id VARCHAR(16) DEFAULT \'accepted\'');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC16BF700BD FOREIGN KEY (status_id) REFERENCES application_statuses (id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC16BF700BD ON application (status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC16BF700BD');
        $this->addSql('DROP TABLE application_statuses');
        $this->addSql('DROP INDEX IDX_A45BDDC16BF700BD ON application');
        $this->addSql('ALTER TABLE application DROP status_id');
    }
}
