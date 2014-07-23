<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140613145928 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("CREATE SEQUENCE profiling_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE profiling (id INT NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, wall_time INT NOT NULL, cpu INT NOT NULL, memory INT NOT NULL, peak_memory INT NOT NULL, data BYTEA NOT NULL, PRIMARY KEY(id))");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("DROP SEQUENCE profiling_id_seq CASCADE");
        $this->addSql("DROP TABLE profiling");
    }
}
