<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190122221531 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scholarship_template_requirements (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, requirement_id VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, config JSON NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2F27E2295DA0FB8 (template_id), INDEX IDX_2F27E2297B576F77 (requirement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scholarship_requirements (id INT AUTO_INCREMENT NOT NULL, scholarship_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', requirement_id VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, config JSON NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F3F48F4228722836 (scholarship_id), INDEX IDX_F3F48F427B576F77 (requirement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE requirements (id VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_70BEA1AA5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application_requirements (id INT AUTO_INCREMENT NOT NULL, application_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', requirement_id INT DEFAULT NULL, value LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D3AB6AF33E030ACD (application_id), INDEX IDX_D3AB6AF37B576F77 (requirement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application_file_application_requirement (application_requirement_id INT NOT NULL, application_file_id INT NOT NULL, INDEX IDX_B45CA78B1C983147 (application_requirement_id), INDEX IDX_B45CA78B78757C5F (application_file_id), PRIMARY KEY(application_requirement_id, application_file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scholarship_template_requirements ADD CONSTRAINT FK_2F27E2295DA0FB8 FOREIGN KEY (template_id) REFERENCES scholarship_template (id)');
        $this->addSql('ALTER TABLE scholarship_template_requirements ADD CONSTRAINT FK_2F27E2297B576F77 FOREIGN KEY (requirement_id) REFERENCES requirements (id)');
        $this->addSql('ALTER TABLE scholarship_requirements ADD CONSTRAINT FK_F3F48F4228722836 FOREIGN KEY (scholarship_id) REFERENCES scholarship (id)');
        $this->addSql('ALTER TABLE scholarship_requirements ADD CONSTRAINT FK_F3F48F427B576F77 FOREIGN KEY (requirement_id) REFERENCES requirements (id)');
        $this->addSql('ALTER TABLE application_requirements ADD CONSTRAINT FK_D3AB6AF33E030ACD FOREIGN KEY (application_id) REFERENCES application (id)');
        $this->addSql('ALTER TABLE application_requirements ADD CONSTRAINT FK_D3AB6AF37B576F77 FOREIGN KEY (requirement_id) REFERENCES scholarship_requirements (id)');
        $this->addSql('ALTER TABLE application_file_application_requirement ADD CONSTRAINT FK_B45CA78B1C983147 FOREIGN KEY (application_requirement_id) REFERENCES application_requirements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE application_file_application_requirement ADD CONSTRAINT FK_B45CA78B78757C5F FOREIGN KEY (application_file_id) REFERENCES application_files (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scholarship_fields ADD title VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE scholarship_template_fields ADD title VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_requirements DROP FOREIGN KEY FK_D3AB6AF37B576F77');
        $this->addSql('ALTER TABLE scholarship_template_requirements DROP FOREIGN KEY FK_2F27E2297B576F77');
        $this->addSql('ALTER TABLE scholarship_requirements DROP FOREIGN KEY FK_F3F48F427B576F77');
        $this->addSql('ALTER TABLE application_file_application_requirement DROP FOREIGN KEY FK_B45CA78B1C983147');
        $this->addSql('DROP TABLE scholarship_template_requirements');
        $this->addSql('DROP TABLE scholarship_requirements');
        $this->addSql('DROP TABLE requirements');
        $this->addSql('DROP TABLE application_requirements');
        $this->addSql('DROP TABLE application_file_application_requirement');
        $this->addSql('ALTER TABLE scholarship_fields DROP title, DROP description');
        $this->addSql('ALTER TABLE scholarship_template_fields DROP title, DROP description');
    }
}
