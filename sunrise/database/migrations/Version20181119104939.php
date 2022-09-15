<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;
use Illuminate\Support\Facades\DB;

class Version20181119104939 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        foreach ( DB::table('application')->get(['id', 'phone']) as $raw) {
            DB::table('application')
                ->where('id', $raw->id)
                ->update(['phone' => phone_format($raw->phone)]);
        }

        foreach ( DB::table('application_winners')->get(['id', 'phone']) as $raw) {
            DB::table('application_winners')
                ->where('id', $raw->id)
                ->update(['phone' => phone_format($raw->phone)]);
        }

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}
