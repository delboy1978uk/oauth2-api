<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404113302 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accesstoken ADD client INT DEFAULT NULL, ADD user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE accesstoken ADD CONSTRAINT FK_B39617F5C7440455 FOREIGN KEY (client) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE accesstoken ADD CONSTRAINT FK_B39617F58D93D649 FOREIGN KEY (user) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_B39617F5C7440455 ON accesstoken (client)');
        $this->addSql('CREATE INDEX IDX_B39617F58D93D649 ON accesstoken (user)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F5C7440455');
        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F58D93D649');
        $this->addSql('DROP INDEX IDX_B39617F5C7440455 ON AccessToken');
        $this->addSql('DROP INDEX IDX_B39617F58D93D649 ON AccessToken');
        $this->addSql('ALTER TABLE AccessToken DROP client, DROP user');
    }
}
