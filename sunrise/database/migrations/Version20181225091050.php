<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20181225091050 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_winners ADD photo_small_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE application_winners ADD CONSTRAINT FK_1704115F493A699F FOREIGN KEY (photo_small_id) REFERENCES application_files (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1704115F493A699F ON application_winners (photo_small_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_winners DROP FOREIGN KEY FK_1704115F493A699F');
        $this->addSql('DROP INDEX UNIQ_1704115F493A699F ON application_winners');
        $this->addSql('ALTER TABLE application_winners DROP photo_small_id');
    }
}
