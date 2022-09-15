<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180629122932 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE `scholarship_website_files` to `scholarship_files`');
        $this->addSql('ALTER TABLE scholarship_winners ADD scholarship_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE scholarship_winners ADD CONSTRAINT FK_6B70A01628722836 FOREIGN KEY (scholarship_id) REFERENCES scholarship (id)');
        $this->addSql('CREATE INDEX IDX_6B70A01628722836 ON scholarship_winners (scholarship_id)');
        $this->addSql('ALTER TABLE scholarship_winners RENAME INDEX uniq_6a6b48f13da5256d TO UNIQ_6B70A0163DA5256D');
        $this->addSql('ALTER TABLE scholarship_winners RENAME INDEX idx_6a6b48f118f45c82 TO IDX_6B70A01618F45C82');
        $this->addSql('ALTER TABLE application_winners RENAME INDEX uniq_149b92053e030acd TO UNIQ_1704115F3E030ACD');
        $this->addSql('ALTER TABLE application_winners RENAME INDEX idx_149b92055d83cc1 TO IDX_1704115F5D83CC1');
        $this->addSql('ALTER TABLE application_winners RENAME INDEX uniq_149b92057e9e4c8c TO UNIQ_1704115F7E9E4C8C');
        $this->addSql('ALTER TABLE application_winners RENAME INDEX uniq_149b9205111ec0a2 TO UNIQ_1704115F111EC0A2');
        $this->addSql('ALTER TABLE scholarship_files DROP FOREIGN KEY FK_FF0DD0F118F45C82');
        $this->addSql('DROP INDEX IDX_FF0DD0F118F45C82 ON scholarship_files');
        $this->addSql('ALTER TABLE scholarship_files ADD scholarship_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', DROP website_id');
        $this->addSql('ALTER TABLE scholarship_files ADD CONSTRAINT FK_DC803C8E28722836 FOREIGN KEY (scholarship_id) REFERENCES scholarship (id)');
        $this->addSql('CREATE INDEX IDX_DC803C8E28722836 ON scholarship_files (scholarship_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_winners RENAME INDEX uniq_1704115f3e030acd TO UNIQ_149B92053E030ACD');
        $this->addSql('ALTER TABLE application_winners RENAME INDEX uniq_1704115f7e9e4c8c TO UNIQ_149B92057E9E4C8C');
        $this->addSql('ALTER TABLE application_winners RENAME INDEX uniq_1704115f111ec0a2 TO UNIQ_149B9205111EC0A2');
        $this->addSql('ALTER TABLE application_winners RENAME INDEX idx_1704115f5d83cc1 TO IDX_149B92055D83CC1');
        $this->addSql('ALTER TABLE scholarship_files DROP FOREIGN KEY FK_DC803C8E28722836');
        $this->addSql('DROP INDEX IDX_DC803C8E28722836 ON scholarship_files');
        $this->addSql('ALTER TABLE scholarship_files ADD website_id INT DEFAULT NULL, DROP scholarship_id');
        $this->addSql('ALTER TABLE scholarship_files ADD CONSTRAINT FK_FF0DD0F118F45C82 FOREIGN KEY (website_id) REFERENCES scholarship_website (id)');
        $this->addSql('CREATE INDEX IDX_FF0DD0F118F45C82 ON scholarship_files (website_id)');
        $this->addSql('ALTER TABLE scholarship_winners DROP FOREIGN KEY FK_6B70A01628722836');
        $this->addSql('DROP INDEX IDX_6B70A01628722836 ON scholarship_winners');
        $this->addSql('ALTER TABLE scholarship_winners DROP scholarship_id');
        $this->addSql('ALTER TABLE scholarship_winners RENAME INDEX uniq_6b70a0163da5256d TO UNIQ_6A6B48F13DA5256D');
        $this->addSql('ALTER TABLE scholarship_winners RENAME INDEX idx_6b70a01618f45c82 TO IDX_6A6B48F118F45C82');
        $this->addSql('RENAME TABLE `scholarship_files` to `scholarship_website_files`');
    }
}
