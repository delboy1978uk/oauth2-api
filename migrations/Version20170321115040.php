<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321115040 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Session (id INT AUTO_INCREMENT NOT NULL, clientId VARCHAR(40) NOT NULL, redirectUri VARCHAR(255) DEFAULT NULL, ownerType VARCHAR(6) NOT NULL, ownerId VARCHAR(255) DEFAULT NULL, authCode VARCHAR(40) DEFAULT NULL, accessToken VARCHAR(40) DEFAULT NULL, refreshToken VARCHAR(40) DEFAULT NULL, accessTokenExpires DATE DEFAULT NULL, stage VARCHAR(8) NOT NULL, firstRequested DATE NOT NULL, lastUpdated DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client CHANGE id id VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE endpoint CHANGE redirectUri redirectUri VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Session');
        $this->addSql('ALTER TABLE Client CHANGE id id VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE Endpoint CHANGE redirectUri redirectUri VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
