<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180702200638 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE application_file_application_winner (application_winner_id INT NOT NULL, application_file_id INT NOT NULL, INDEX IDX_A361E678D9FDAEB9 (application_winner_id), INDEX IDX_A361E67878757C5F (application_file_id), PRIMARY KEY(application_winner_id, application_file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application_file_application_winner ADD CONSTRAINT FK_A361E678D9FDAEB9 FOREIGN KEY (application_winner_id) REFERENCES application_winners (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE application_file_application_winner ADD CONSTRAINT FK_A361E67878757C5F FOREIGN KEY (application_file_id) REFERENCES application_files (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scholarship_winners CHANGE application_winner_id application_winner_id INT NOT NULL');
        $this->addSql('ALTER TABLE application_winners DROP FOREIGN KEY FK_149B9205111EC0A2');
        $this->addSql('DROP INDEX UNIQ_1704115F111EC0A2 ON application_winners');
        $this->addSql('ALTER TABLE application_winners DROP affidavit_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE application_file_application_winner');
        $this->addSql('ALTER TABLE application_winners ADD affidavit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE application_winners ADD CONSTRAINT FK_149B9205111EC0A2 FOREIGN KEY (affidavit_id) REFERENCES application_files (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1704115F111EC0A2 ON application_winners (affidavit_id)');
        $this->addSql('ALTER TABLE scholarship_winners CHANGE application_winner_id application_winner_id INT DEFAULT NULL');
    }
}
