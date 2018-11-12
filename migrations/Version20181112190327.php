<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181112190327 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AuthCode DROP INDEX UNIQ_F1D7D177C7440455, ADD INDEX IDX_F1D7D177C7440455 (client)');
        $this->addSql('ALTER TABLE AuthCode ADD user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE AuthCode ADD CONSTRAINT FK_F1D7D1778D93D649 FOREIGN KEY (user) REFERENCES User (id)');
        $this->addSql('ALTER TABLE AuthCode ADD CONSTRAINT FK_F1D7D177C7440455 FOREIGN KEY (client) REFERENCES Client (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F1D7D1778D93D649 ON AuthCode (user)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AuthCode DROP INDEX IDX_F1D7D177C7440455, ADD UNIQUE INDEX UNIQ_F1D7D177C7440455 (client)');
        $this->addSql('ALTER TABLE AuthCode DROP FOREIGN KEY FK_F1D7D1778D93D649');
        $this->addSql('ALTER TABLE AuthCode DROP FOREIGN KEY FK_F1D7D177C7440455');
        $this->addSql('DROP INDEX UNIQ_F1D7D1778D93D649 ON AuthCode');
        $this->addSql('ALTER TABLE AuthCode DROP user');
    }
}
