<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20181121193354 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scholarship_fields (id INT AUTO_INCREMENT NOT NULL, scholarship_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', field_id VARCHAR(255) NOT NULL, eligibility_type VARCHAR(255) DEFAULT NULL, eligibility_value VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6688118328722836 (scholarship_id), INDEX IDX_66881183443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scholarship_template_fields (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, field_id VARCHAR(255) NOT NULL, eligibility_type VARCHAR(255) DEFAULT NULL, eligibility_value VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4FF2CA435DA0FB8 (template_id), INDEX IDX_4FF2CA43443707B0 (field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scholarship_fields ADD CONSTRAINT FK_6688118328722836 FOREIGN KEY (scholarship_id) REFERENCES scholarship (id)');
        $this->addSql('ALTER TABLE scholarship_fields ADD CONSTRAINT FK_66881183443707B0 FOREIGN KEY (field_id) REFERENCES fields (id)');
        $this->addSql('ALTER TABLE scholarship_template_fields ADD CONSTRAINT FK_4FF2CA435DA0FB8 FOREIGN KEY (template_id) REFERENCES scholarship_template (id)');
        $this->addSql('ALTER TABLE scholarship_template_fields ADD CONSTRAINT FK_4FF2CA43443707B0 FOREIGN KEY (field_id) REFERENCES fields (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE scholarship_fields');
        $this->addSql('DROP TABLE scholarship_template_fields');
    }
}
