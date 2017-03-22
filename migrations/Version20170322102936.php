<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170322102936 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accesstoken ADD client VARCHAR(40) DEFAULT NULL, ADD userIdentifier INT DEFAULT NULL');
        $this->addSql('ALTER TABLE accesstoken ADD CONSTRAINT FK_B39617F5750FAC43 FOREIGN KEY (userIdentifier) REFERENCES User (id)');
        $this->addSql('ALTER TABLE accesstoken ADD CONSTRAINT FK_B39617F5C7440455 FOREIGN KEY (client) REFERENCES Client (identifier)');
        $this->addSql('CREATE INDEX IDX_B39617F5750FAC43 ON accesstoken (userIdentifier)');
        $this->addSql('CREATE INDEX IDX_B39617F5C7440455 ON accesstoken (client)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F5750FAC43');
        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F5C7440455');
        $this->addSql('DROP INDEX IDX_B39617F5750FAC43 ON AccessToken');
        $this->addSql('DROP INDEX IDX_B39617F5C7440455 ON AccessToken');
        $this->addSql('ALTER TABLE AccessToken DROP client, DROP userIdentifier');
    }
}
