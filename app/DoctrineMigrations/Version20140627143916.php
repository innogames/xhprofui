<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140627143916 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE profiling ADD server_name VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE profiling ADD request_uri VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE profiling ADD request_method VARCHAR(4) NOT NULL");
        $this->addSql("ALTER TABLE profiling ADD get_params TEXT NOT NULL");
        $this->addSql("ALTER TABLE profiling ADD post_params TEXT NOT NULL");
        $this->addSql("ALTER TABLE profiling ADD cookies_params TEXT NOT NULL");
        $this->addSql("COMMENT ON COLUMN profiling.get_params IS '(DC2Type:json_array)'");
        $this->addSql("COMMENT ON COLUMN profiling.post_params IS '(DC2Type:json_array)'");
        $this->addSql("COMMENT ON COLUMN profiling.cookies_params IS '(DC2Type:json_array)'");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE profiling DROP server_name");
        $this->addSql("ALTER TABLE profiling DROP request_uri");
        $this->addSql("ALTER TABLE profiling DROP request_method");
        $this->addSql("ALTER TABLE profiling DROP get_params");
        $this->addSql("ALTER TABLE profiling DROP post_params");
        $this->addSql("ALTER TABLE profiling DROP cookies_params");
    }
}
