<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140704155427 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE profiling_data ALTER profiling_id DROP NOT NULL");
        $this->addSql("ALTER TABLE profiling_data ADD CONSTRAINT FK_A35636EA43FED25D FOREIGN KEY (profiling_id) REFERENCES Profiling (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE INDEX IDX_A35636EA43FED25D ON profiling_data (profiling_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE profiling_data DROP CONSTRAINT FK_A35636EA43FED25D");
        $this->addSql("DROP INDEX IDX_A35636EA43FED25D");
        $this->addSql("ALTER TABLE profiling_data ALTER profiling_id SET NOT NULL");
    }
}
