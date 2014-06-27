<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140627144008 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE profiling ALTER get_params DROP NOT NULL");
        $this->addSql("ALTER TABLE profiling ALTER post_params DROP NOT NULL");
        $this->addSql("ALTER TABLE profiling ALTER cookies_params DROP NOT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE Profiling ALTER get_params SET NOT NULL");
        $this->addSql("ALTER TABLE Profiling ALTER post_params SET NOT NULL");
        $this->addSql("ALTER TABLE Profiling ALTER cookies_params SET NOT NULL");
    }
}
