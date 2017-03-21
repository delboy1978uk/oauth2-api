<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321161930 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Client (id VARCHAR(40) NOT NULL, secret VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, autoApprove TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Endpoint (id INT AUTO_INCREMENT NOT NULL, redirectUri VARCHAR(255) DEFAULT NULL, clientId_id VARCHAR(40) DEFAULT NULL, INDEX IDX_3D346D2D323C022A (clientId_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Scope (id INT AUTO_INCREMENT NOT NULL, scope VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Session (id INT AUTO_INCREMENT NOT NULL, clientId VARCHAR(40) NOT NULL, redirectUri VARCHAR(255) DEFAULT NULL, ownerType VARCHAR(6) NOT NULL, ownerId VARCHAR(255) DEFAULT NULL, authCode VARCHAR(40) DEFAULT NULL, accessToken VARCHAR(40) DEFAULT NULL, refreshToken VARCHAR(40) DEFAULT NULL, accessTokenExpires DATE DEFAULT NULL, stage VARCHAR(8) NOT NULL, firstRequested DATE NOT NULL, lastUpdated DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Session_Scope (session_id INT NOT NULL, scope_id INT NOT NULL, INDEX IDX_A96D89F4613FECDF (session_id), INDEX IDX_A96D89F4682B5931 (scope_id), PRIMARY KEY(session_id, scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Endpoint ADD CONSTRAINT FK_3D346D2D323C022A FOREIGN KEY (clientId_id) REFERENCES Client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Session_Scope ADD CONSTRAINT FK_A96D89F4613FECDF FOREIGN KEY (session_id) REFERENCES Session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Session_Scope ADD CONSTRAINT FK_A96D89F4682B5931 FOREIGN KEY (scope_id) REFERENCES Scope (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Endpoint DROP FOREIGN KEY FK_3D346D2D323C022A');
        $this->addSql('ALTER TABLE Session_Scope DROP FOREIGN KEY FK_A96D89F4682B5931');
        $this->addSql('ALTER TABLE Session_Scope DROP FOREIGN KEY FK_A96D89F4613FECDF');
        $this->addSql('DROP TABLE Client');
        $this->addSql('DROP TABLE Endpoint');
        $this->addSql('DROP TABLE Scope');
        $this->addSql('DROP TABLE Session');
        $this->addSql('DROP TABLE Session_Scope');
    }
}
