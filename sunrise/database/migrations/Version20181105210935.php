<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20181105210935 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organisation ADD state_id INT DEFAULT NULL, ADD business_name VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD address2 VARCHAR(255) DEFAULT NULL, ADD zip VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD website VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B45D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('CREATE INDEX IDX_E6E132B45D83CC1 ON organisation (state_id)');

        //    'business' => [
        //        'name'          => 'Positive Rewards LLC',
        //        'companyName'   => 'Positive Rewards',
        //
        //        'address'       => '43-01 21st St',
        //        'address2'      => 'Suite 231',
        //        'city'          => 'Long Island City',
        //        'region'        => 'NY',
        //        'zip'           => '11101',
        //
        //        'website'       => 'www.positiverewards.net',
        //        'email'         => 'contact@positiverewards.net',
        //        'phone'         => '+1-718-717-2635',
        //    ],

        $this->addSql(
            'UPDATE organisation SET
              business_name = "Positive Rewards LLC",
              address = "43-01 21st St",
              address2 = "Suite 231",
              city = "Long Island City",
              state_id = 33,
              zip = "11101",
              website = "www.positiverewards.net",
              email = "contact@positiverewards.net",
              phone = "+1-718-717-2635"
            WHERE name = "Positive Rewards"'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organisation DROP FOREIGN KEY FK_E6E132B45D83CC1');
        $this->addSql('DROP INDEX IDX_E6E132B45D83CC1 ON organisation');
        $this->addSql('ALTER TABLE organisation DROP state_id, DROP business_name, DROP city, DROP address, DROP address2, DROP zip, DROP email, DROP website, DROP phone');
    }
}
