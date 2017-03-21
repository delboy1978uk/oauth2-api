<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321112314 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client CHANGE id id VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE endpoint ADD clientId_id VARCHAR(40) DEFAULT NULL, DROP clientId');
        $this->addSql('ALTER TABLE endpoint ADD CONSTRAINT FK_3D346D2D323C022A FOREIGN KEY (clientId_id) REFERENCES Client (id)');
        $this->addSql('CREATE INDEX IDX_3D346D2D323C022A ON endpoint (clientId_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Client CHANGE id id VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE Endpoint DROP FOREIGN KEY FK_3D346D2D323C022A');
        $this->addSql('DROP INDEX IDX_3D346D2D323C022A ON Endpoint');
        $this->addSql('ALTER TABLE Endpoint ADD clientId VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, DROP clientId_id');
    }
}
