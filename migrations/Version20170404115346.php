<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404115346 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accesstoken DROP FOREIGN KEY FK_B39617F58D93D649');
        $this->addSql('ALTER TABLE accesstoken DROP FOREIGN KEY FK_B39617F5C7440455');
        $this->addSql('DROP INDEX IDX_B39617F5C7440455 ON accesstoken');
        $this->addSql('DROP INDEX IDX_B39617F58D93D649 ON accesstoken');
        $this->addSql('ALTER TABLE accesstoken DROP user, DROP client');
        $this->addSql('ALTER TABLE accesstoken ADD CONSTRAINT FK_B39617F5EA1CE9BE FOREIGN KEY (clientId) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE accesstoken ADD CONSTRAINT FK_B39617F564B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_B39617F5EA1CE9BE ON accesstoken (clientId)');
        $this->addSql('CREATE INDEX IDX_B39617F564B64DCC ON accesstoken (userId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F5EA1CE9BE');
        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F564B64DCC');
        $this->addSql('DROP INDEX IDX_B39617F5EA1CE9BE ON AccessToken');
        $this->addSql('DROP INDEX IDX_B39617F564B64DCC ON AccessToken');
        $this->addSql('ALTER TABLE AccessToken ADD user INT DEFAULT NULL, ADD client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE AccessToken ADD CONSTRAINT FK_B39617F58D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE AccessToken ADD CONSTRAINT FK_B39617F5C7440455 FOREIGN KEY (client) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_B39617F5C7440455 ON AccessToken (client)');
        $this->addSql('CREATE INDEX IDX_B39617F58D93D649 ON AccessToken (user)');
    }
}
