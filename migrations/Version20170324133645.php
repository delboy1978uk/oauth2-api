<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170324133645 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accesstoken DROP FOREIGN KEY FK_B39617F5750FAC43');
        $this->addSql('DROP INDEX IDX_B39617F5750FAC43 ON accesstoken');
        $this->addSql('ALTER TABLE accesstoken CHANGE userIdentifier userIdentifier INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AccessToken CHANGE userIdentifier userIdentifier INT DEFAULT NULL');
        $this->addSql('ALTER TABLE AccessToken ADD CONSTRAINT FK_B39617F5750FAC43 FOREIGN KEY (userIdentifier) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B39617F5750FAC43 ON AccessToken (userIdentifier)');
    }
}
