<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180629150605 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_winners DROP FOREIGN KEY FK_6A6B48F118F45C82');
        $this->addSql('DROP INDEX IDX_6B70A01618F45C82 ON scholarship_winners');
        $this->addSql('ALTER TABLE scholarship_winners ADD application_winner_id INT NOT NULL, DROP website_id');
        $this->addSql('ALTER TABLE scholarship_winners ADD CONSTRAINT FK_6B70A016D9FDAEB9 FOREIGN KEY (application_winner_id) REFERENCES application_winners (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B70A016D9FDAEB9 ON scholarship_winners (application_winner_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scholarship_winners DROP FOREIGN KEY FK_6B70A016D9FDAEB9');
        $this->addSql('DROP INDEX UNIQ_6B70A016D9FDAEB9 ON scholarship_winners');
        $this->addSql('ALTER TABLE scholarship_winners ADD website_id INT DEFAULT NULL, DROP application_winner_id');
        $this->addSql('ALTER TABLE scholarship_winners ADD CONSTRAINT FK_6A6B48F118F45C82 FOREIGN KEY (website_id) REFERENCES scholarship_website (id)');
        $this->addSql('CREATE INDEX IDX_6B70A01618F45C82 ON scholarship_winners (website_id)');
    }
}
