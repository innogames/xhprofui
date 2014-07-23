<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140723145313 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE profiling_data DROP CONSTRAINT fk_a35636ea727aca70");
        $this->addSql("DROP SEQUENCE ext_translations_id_seq CASCADE");
        $this->addSql("DROP SEQUENCE ext_log_entries_id_seq CASCADE");
        $this->addSql("DROP SEQUENCE profiling_data_id_seq CASCADE");
        $this->addSql("DROP TABLE ext_translations");
        $this->addSql("DROP TABLE ext_log_entries");
        $this->addSql("DROP TABLE profiling_data");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("CREATE SEQUENCE ext_translations_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE ext_log_entries_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE profiling_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE ext_translations (id INT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content TEXT DEFAULT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE INDEX translations_lookup_idx ON ext_translations (locale, object_class, foreign_key)");
        $this->addSql("CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations (locale, object_class, field, foreign_key)");
        $this->addSql("CREATE TABLE ext_log_entries (id INT NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data TEXT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE INDEX log_date_lookup_idx ON ext_log_entries (logged_at)");
        $this->addSql("CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)");
        $this->addSql("CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)");
        $this->addSql("CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)");
        $this->addSql("COMMENT ON COLUMN ext_log_entries.data IS '(DC2Type:array)'");
        $this->addSql("CREATE TABLE profiling_data (id INT NOT NULL, parent_id INT DEFAULT NULL, profiling_id INT DEFAULT NULL, method VARCHAR(255) NOT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, root INT DEFAULT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE INDEX idx_a35636ea727aca70 ON profiling_data (parent_id)");
        $this->addSql("CREATE INDEX idx_a35636ea43fed25d ON profiling_data (profiling_id)");
        $this->addSql("ALTER TABLE profiling_data ADD CONSTRAINT fk_a35636ea727aca70 FOREIGN KEY (parent_id) REFERENCES profiling_data (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE profiling_data ADD CONSTRAINT fk_a35636ea43fed25d FOREIGN KEY (profiling_id) REFERENCES profiling (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }
}
