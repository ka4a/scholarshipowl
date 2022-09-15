<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180612230913 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scholarship_website_files (id INT AUTO_INCREMENT NOT NULL, website_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, size NUMERIC(10, 0) NOT NULL, INDEX IDX_FF0DD0F118F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scholarship_website_winners (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, website_id INT DEFAULT NULL, testimonial VARCHAR(1024) NOT NULL, UNIQUE INDEX UNIQ_6A6B48F13DA5256D (image_id), INDEX IDX_6A6B48F118F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scholarship_website_files ADD CONSTRAINT FK_FF0DD0F118F45C82 FOREIGN KEY (website_id) REFERENCES scholarship_website (id)');
        $this->addSql('ALTER TABLE scholarship_website_winners ADD CONSTRAINT FK_6A6B48F13DA5256D FOREIGN KEY (image_id) REFERENCES scholarship_website_files (id)');
        $this->addSql('ALTER TABLE scholarship_website_winners ADD CONSTRAINT FK_6A6B48F118F45C82 FOREIGN KEY (website_id) REFERENCES scholarship_website (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_website_winners DROP FOREIGN KEY FK_6A6B48F13DA5256D');
        $this->addSql('DROP TABLE scholarship_website_files');
        $this->addSql('DROP TABLE scholarship_website_winners');
    }
}
