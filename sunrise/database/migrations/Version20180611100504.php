<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20180611100504 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, permissions JSON NOT NULL COMMENT \'(DC2Type:json_array)\', password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_role_user (user_id INT NOT NULL, organisation_role_id INT NOT NULL, INDEX IDX_D6607005A76ED395 (user_id), INDEX IDX_D6607005B58755AF (organisation_role_id), PRIMARY KEY(user_id, organisation_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_332CA4DDA76ED395 (user_id), INDEX IDX_332CA4DDD60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation_role (id INT AUTO_INCREMENT NOT NULL, organisation_id INT NOT NULL, name VARCHAR(255) NOT NULL, is_owner TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, permissions JSON NOT NULL COMMENT \'(DC2Type:json_array)\', INDEX IDX_152D8A729E6B1585 (organisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, permissions JSON NOT NULL COMMENT \'(DC2Type:json_array)\', UNIQUE INDEX ix_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scholarship (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', scholarship_template_id INT DEFAULT NULL, title VARCHAR(127) NOT NULL, description VARCHAR(2047) DEFAULT NULL, timezone VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, expiration_date DATETIME NOT NULL, amount NUMERIC(10, 2) NOT NULL, expired_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, recurring_type VARCHAR(255) DEFAULT NULL, recurring_value SMALLINT DEFAULT NULL, INDEX IDX_F3FD63F9C7120AF (scholarship_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scholarship_template (id INT AUTO_INCREMENT NOT NULL, website_id INT DEFAULT NULL, organisation_id INT NOT NULL, title VARCHAR(127) NOT NULL, description VARCHAR(2047) DEFAULT NULL, timezone VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, expiration_date DATETIME NOT NULL, amount NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, recurring_type VARCHAR(255) DEFAULT NULL, recurring_value SMALLINT DEFAULT NULL, UNIQUE INDEX UNIQ_27274D1518F45C82 (website_id), INDEX IDX_27274D159E6B1585 (organisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scholarship_website (id INT AUTO_INCREMENT NOT NULL, scholarship_template_id INT DEFAULT NULL, domain VARCHAR(255) NOT NULL, layout VARCHAR(255) NOT NULL, variant VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, intro VARCHAR(1024) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D0A5E99DA7A91E0B (domain), UNIQUE INDEX UNIQ_D0A5E99D9C7120AF (scholarship_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, api_token VARCHAR(60) NOT NULL, password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(127) NOT NULL, abbreviation VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_clients (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, secret VARCHAR(100) NOT NULL, redirect TEXT NOT NULL, personal_access_client TINYINT(1) NOT NULL, password_client TINYINT(1) NOT NULL, revoked TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_13CE8101A76ED395 (user_id), INDEX oauth_clients_user_id_index (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_auth_codes (id VARCHAR(100) NOT NULL, user_id INT NOT NULL, client_id INT NOT NULL, scopes TEXT DEFAULT NULL, revoked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_refresh_tokens (id VARCHAR(100) NOT NULL, access_token_id VARCHAR(100) NOT NULL, revoked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, INDEX oauth_refresh_tokens_access_token_id_index (access_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_personal_access_clients (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX oauth_personal_access_clients_client_id_index (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth_access_tokens (id VARCHAR(100) NOT NULL, user_id INT DEFAULT NULL, client_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, scopes TEXT DEFAULT NULL, revoked TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, expires_at DATETIME DEFAULT NULL, INDEX oauth_access_tokens_user_id_index (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scholarship_winner (id INT AUTO_INCREMENT NOT NULL, application_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', state_id INT NOT NULL, photo_id INT DEFAULT NULL, affidavit_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, date_of_birth DATETIME DEFAULT NULL, testimonial VARCHAR(1024) DEFAULT NULL, paypal VARCHAR(255) DEFAULT NULL, bank_name VARCHAR(255) DEFAULT NULL, name_of_account VARCHAR(255) DEFAULT NULL, account_number VARCHAR(255) DEFAULT NULL, routing_number VARCHAR(255) DEFAULT NULL, swift_code VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_149B92053E030ACD (application_id), INDEX IDX_149B92055D83CC1 (state_id), UNIQUE INDEX UNIQ_149B92057E9E4C8C (photo_id), UNIQUE INDEX UNIQ_149B9205111EC0A2 (affidavit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application_files (id INT AUTO_INCREMENT NOT NULL, application_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, size NUMERIC(10, 0) NOT NULL, INDEX IDX_E5A1A7233E030ACD (application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE application (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', scholarship_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', state_id INT NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, data JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A45BDDC128722836 (scholarship_id), INDEX IDX_A45BDDC15D83CC1 (state_id), UNIQUE INDEX uk_scholarship_id_email (scholarship_id, email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organisation_role_user ADD CONSTRAINT FK_D6607005A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organisation_role_user ADD CONSTRAINT FK_D6607005B58755AF FOREIGN KEY (organisation_role_id) REFERENCES organisation_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organisation_role ADD CONSTRAINT FK_152D8A729E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE scholarship ADD CONSTRAINT FK_F3FD63F9C7120AF FOREIGN KEY (scholarship_template_id) REFERENCES scholarship_template (id)');
        $this->addSql('ALTER TABLE scholarship_template ADD CONSTRAINT FK_27274D1518F45C82 FOREIGN KEY (website_id) REFERENCES scholarship_website (id)');
        $this->addSql('ALTER TABLE scholarship_template ADD CONSTRAINT FK_27274D159E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE scholarship_website ADD CONSTRAINT FK_D0A5E99D9C7120AF FOREIGN KEY (scholarship_template_id) REFERENCES scholarship_template (id)');
        $this->addSql('ALTER TABLE oauth_clients ADD CONSTRAINT FK_13CE8101A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE scholarship_winner ADD CONSTRAINT FK_149B92053E030ACD FOREIGN KEY (application_id) REFERENCES application (id)');
        $this->addSql('ALTER TABLE scholarship_winner ADD CONSTRAINT FK_149B92055D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE scholarship_winner ADD CONSTRAINT FK_149B92057E9E4C8C FOREIGN KEY (photo_id) REFERENCES application_files (id)');
        $this->addSql('ALTER TABLE scholarship_winner ADD CONSTRAINT FK_149B9205111EC0A2 FOREIGN KEY (affidavit_id) REFERENCES application_files (id)');
        $this->addSql('ALTER TABLE application_files ADD CONSTRAINT FK_E5A1A7233E030ACD FOREIGN KEY (application_id) REFERENCES application (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC128722836 FOREIGN KEY (scholarship_id) REFERENCES scholarship (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC15D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        
        /**
         * Data seeding
         */
        $this->addSql('INSERT INTO role VALUES(1, "Super Admin", NOW(), NOW(), "[]");');

        $states = array (
            0 => array ( 'state_id' => 1, 'name' => 'Alabama', 'abbreviation' => 'AL', 'country_id' => 1, ),
            1 => array ( 'state_id' => 2, 'name' => 'Alaska', 'abbreviation' => 'AK', 'country_id' => 1, ),
            2 => array ( 'state_id' => 3, 'name' => 'Arizona', 'abbreviation' => 'AZ', 'country_id' => 1, ),
            3 => array ( 'state_id' => 4, 'name' => 'Arkansas', 'abbreviation' => 'AR', 'country_id' => 1, ),
            4 => array ( 'state_id' => 5, 'name' => 'California', 'abbreviation' => 'CA', 'country_id' => 1, ),
            5 => array ( 'state_id' => 6, 'name' => 'Colorado', 'abbreviation' => 'CO', 'country_id' => 1, ),
            6 => array ( 'state_id' => 7, 'name' => 'Connecticut', 'abbreviation' => 'CT', 'country_id' => 1, ),
            7 => array ( 'state_id' => 8, 'name' => 'Delaware', 'abbreviation' => 'DE', 'country_id' => 1, ),
            8 => array ( 'state_id' => 9, 'name' => 'District of Columbia', 'abbreviation' => 'DC', 'country_id' => 1,),
            9 =>
                array (
                    'state_id' => 10,
                    'name' => 'Florida',
                    'abbreviation' => 'FL',
                    'country_id' => 1,
                ),
            10 =>
                array (
                    'state_id' => 11,
                    'name' => 'Georgia',
                    'abbreviation' => 'GA',
                    'country_id' => 1,
                ),
            11 =>
                array (
                    'state_id' => 12,
                    'name' => 'Hawaii',
                    'abbreviation' => 'HI',
                    'country_id' => 1,
                ),
            12 =>
                array (
                    'state_id' => 13,
                    'name' => 'Idaho',
                    'abbreviation' => 'ID',
                    'country_id' => 1,
                ),
            13 =>
                array (
                    'state_id' => 14,
                    'name' => 'Illinois',
                    'abbreviation' => 'IL',
                    'country_id' => 1,
                ),
            14 =>
                array (
                    'state_id' => 15,
                    'name' => 'Indiana',
                    'abbreviation' => 'IN',
                    'country_id' => 1,
                ),
            15 =>
                array (
                    'state_id' => 16,
                    'name' => 'Iowa',
                    'abbreviation' => 'IA',
                    'country_id' => 1,
                ),
            16 =>
                array (
                    'state_id' => 17,
                    'name' => 'Kansas',
                    'abbreviation' => 'KS',
                    'country_id' => 1,
                ),
            17 =>
                array (
                    'state_id' => 18,
                    'name' => 'Kentucky',
                    'abbreviation' => 'KY',
                    'country_id' => 1,
                ),
            18 =>
                array (
                    'state_id' => 19,
                    'name' => 'Louisiana',
                    'abbreviation' => 'LA',
                    'country_id' => 1,
                ),
            19 =>
                array (
                    'state_id' => 20,
                    'name' => 'Maine',
                    'abbreviation' => 'ME',
                    'country_id' => 1,
                ),
            20 =>
                array (
                    'state_id' => 21,
                    'name' => 'Maryland',
                    'abbreviation' => 'MD',
                    'country_id' => 1,
                ),
            21 =>
                array (
                    'state_id' => 22,
                    'name' => 'Massachusetts',
                    'abbreviation' => 'MA',
                    'country_id' => 1,
                ),
            22 =>
                array (
                    'state_id' => 23,
                    'name' => 'Michigan',
                    'abbreviation' => 'MI',
                    'country_id' => 1,
                ),
            23 =>
                array (
                    'state_id' => 24,
                    'name' => 'Minnesota',
                    'abbreviation' => 'MN',
                    'country_id' => 1,
                ),
            24 =>
                array (
                    'state_id' => 25,
                    'name' => 'Mississippi',
                    'abbreviation' => 'MS',
                    'country_id' => 1,
                ),
            25 =>
                array (
                    'state_id' => 26,
                    'name' => 'Missouri',
                    'abbreviation' => 'MO',
                    'country_id' => 1,
                ),
            26 =>
                array (
                    'state_id' => 27,
                    'name' => 'Montana',
                    'abbreviation' => 'MT',
                    'country_id' => 1,
                ),
            27 =>
                array (
                    'state_id' => 28,
                    'name' => 'Nebraska',
                    'abbreviation' => 'NE',
                    'country_id' => 1,
                ),
            28 =>
                array (
                    'state_id' => 29,
                    'name' => 'Nevada',
                    'abbreviation' => 'NV',
                    'country_id' => 1,
                ),
            29 =>
                array (
                    'state_id' => 30,
                    'name' => 'New Hampshire',
                    'abbreviation' => 'NH',
                    'country_id' => 1,
                ),
            30 =>
                array (
                    'state_id' => 31,
                    'name' => 'New Jersey',
                    'abbreviation' => 'NJ',
                    'country_id' => 1,
                ),
            31 =>
                array (
                    'state_id' => 32,
                    'name' => 'New Mexico',
                    'abbreviation' => 'NM',
                    'country_id' => 1,
                ),
            32 =>
                array (
                    'state_id' => 33,
                    'name' => 'New York',
                    'abbreviation' => 'NY',
                    'country_id' => 1,
                ),
            33 =>
                array (
                    'state_id' => 34,
                    'name' => 'North Carolina',
                    'abbreviation' => 'NC',
                    'country_id' => 1,
                ),
            34 =>
                array (
                    'state_id' => 35,
                    'name' => 'North Dakota',
                    'abbreviation' => 'ND',
                    'country_id' => 1,
                ),
            35 =>
                array (
                    'state_id' => 36,
                    'name' => 'Ohio',
                    'abbreviation' => 'OH',
                    'country_id' => 1,
                ),
            36 =>
                array (
                    'state_id' => 37,
                    'name' => 'Oklahoma',
                    'abbreviation' => 'OK',
                    'country_id' => 1,
                ),
            37 =>
                array (
                    'state_id' => 38,
                    'name' => 'Oregon',
                    'abbreviation' => 'OR',
                    'country_id' => 1,
                ),
            38 =>
                array (
                    'state_id' => 39,
                    'name' => 'Pennsylvania',
                    'abbreviation' => 'PA',
                    'country_id' => 1,
                ),
            39 =>
                array (
                    'state_id' => 40,
                    'name' => 'Puerto Rico',
                    'abbreviation' => 'PR',
                    'country_id' => 1,
                ),
            40 =>
                array (
                    'state_id' => 41,
                    'name' => 'Rhode Island',
                    'abbreviation' => 'RI',
                    'country_id' => 1,
                ),
            41 =>
                array (
                    'state_id' => 42,
                    'name' => 'South Carolina',
                    'abbreviation' => 'SC',
                    'country_id' => 1,
                ),
            42 =>
                array (
                    'state_id' => 43,
                    'name' => 'South Dakota',
                    'abbreviation' => 'SD',
                    'country_id' => 1,
                ),
            43 =>
                array (
                    'state_id' => 44,
                    'name' => 'Tennessee',
                    'abbreviation' => 'TN',
                    'country_id' => 1,
                ),
            44 =>
                array (
                    'state_id' => 45,
                    'name' => 'Texas',
                    'abbreviation' => 'TX',
                    'country_id' => 1,
                ),
            45 =>
                array (
                    'state_id' => 46,
                    'name' => 'Utah',
                    'abbreviation' => 'UT',
                    'country_id' => 1,
                ),
            46 =>
                array (
                    'state_id' => 47,
                    'name' => 'Vermont',
                    'abbreviation' => 'VT',
                    'country_id' => 1,
                ),
            47 =>
                array (
                    'state_id' => 48,
                    'name' => 'Virginia',
                    'abbreviation' => 'VA',
                    'country_id' => 1,
                ),
            48 =>
                array (
                    'state_id' => 49,
                    'name' => 'Washington',
                    'abbreviation' => 'WA',
                    'country_id' => 1,
                ),
            49 =>
                array (
                    'state_id' => 50,
                    'name' => 'West Virginia',
                    'abbreviation' => 'WV',
                    'country_id' => 1,
                ),
            50 =>
                array (
                    'state_id' => 51,
                    'name' => 'Wisconsin',
                    'abbreviation' => 'WI',
                    'country_id' => 1,
                ),
            51 =>
                array (
                    'state_id' => 52,
                    'name' => 'Wyoming',
                    'abbreviation' => 'WY',
                    'country_id' => 1,
                )
        );

        foreach ($states as $state) {
            $this->addSql('INSERT INTO state (name, abbreviation) VALUES (?, ?);', [$state['name'], $state['abbreviation']]);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organisation_role_user DROP FOREIGN KEY FK_D6607005A76ED395');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE oauth_clients DROP FOREIGN KEY FK_13CE8101A76ED395');
        $this->addSql('ALTER TABLE organisation_role_user DROP FOREIGN KEY FK_D6607005B58755AF');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC128722836');
        $this->addSql('ALTER TABLE scholarship DROP FOREIGN KEY FK_F3FD63F9C7120AF');
        $this->addSql('ALTER TABLE scholarship_website DROP FOREIGN KEY FK_D0A5E99D9C7120AF');
        $this->addSql('ALTER TABLE scholarship_template DROP FOREIGN KEY FK_27274D1518F45C82');
        $this->addSql('ALTER TABLE organisation_role DROP FOREIGN KEY FK_152D8A729E6B1585');
        $this->addSql('ALTER TABLE scholarship_template DROP FOREIGN KEY FK_27274D159E6B1585');
        $this->addSql('ALTER TABLE scholarship_winner DROP FOREIGN KEY FK_149B92055D83CC1');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC15D83CC1');
        $this->addSql('ALTER TABLE scholarship_winner DROP FOREIGN KEY FK_149B92057E9E4C8C');
        $this->addSql('ALTER TABLE scholarship_winner DROP FOREIGN KEY FK_149B9205111EC0A2');
        $this->addSql('ALTER TABLE scholarship_winner DROP FOREIGN KEY FK_149B92053E030ACD');
        $this->addSql('ALTER TABLE application_files DROP FOREIGN KEY FK_E5A1A7233E030ACD');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE organisation_role_user');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE organisation_role');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE scholarship');
        $this->addSql('DROP TABLE scholarship_template');
        $this->addSql('DROP TABLE scholarship_website');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE oauth_clients');
        $this->addSql('DROP TABLE oauth_auth_codes');
        $this->addSql('DROP TABLE oauth_refresh_tokens');
        $this->addSql('DROP TABLE oauth_personal_access_clients');
        $this->addSql('DROP TABLE oauth_access_tokens');
        $this->addSql('DROP TABLE scholarship_winner');
        $this->addSql('DROP TABLE application_files');
        $this->addSql('DROP TABLE log_entries');
        $this->addSql('DROP TABLE application');
    }
}
