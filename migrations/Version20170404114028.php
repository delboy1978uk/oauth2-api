<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404114028 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE authcode ADD client INT DEFAULT NULL, ADD user INT DEFAULT NULL, ADD code VARCHAR(40) NOT NULL, ADD clientId INT NOT NULL, ADD userId INT NOT NULL, ADD expires DATETIME NOT NULL, ADD redirectUri VARCHAR(255) NOT NULL, ADD scope VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE authcode ADD CONSTRAINT FK_F1D7D177C7440455 FOREIGN KEY (client) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE authcode ADD CONSTRAINT FK_F1D7D1778D93D649 FOREIGN KEY (user) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_F1D7D177C7440455 ON authcode (client)');
        $this->addSql('CREATE INDEX IDX_F1D7D1778D93D649 ON authcode (user)');
        $this->addSql('CREATE UNIQUE INDEX code_idx ON authcode (code)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AuthCode DROP FOREIGN KEY FK_F1D7D177C7440455');
        $this->addSql('ALTER TABLE AuthCode DROP FOREIGN KEY FK_F1D7D1778D93D649');
        $this->addSql('DROP INDEX IDX_F1D7D177C7440455 ON AuthCode');
        $this->addSql('DROP INDEX IDX_F1D7D1778D93D649 ON AuthCode');
        $this->addSql('DROP INDEX code_idx ON AuthCode');
        $this->addSql('ALTER TABLE AuthCode DROP client, DROP user, DROP code, DROP clientId, DROP userId, DROP expires, DROP redirectUri, DROP scope');
    }
}
