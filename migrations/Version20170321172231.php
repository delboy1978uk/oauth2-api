<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321172231 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE endpoint DROP FOREIGN KEY FK_3D346D2D323C022A');
        $this->addSql('ALTER TABLE session_scope DROP FOREIGN KEY FK_A96D89F4682B5931');
        $this->addSql('ALTER TABLE session_scope DROP FOREIGN KEY FK_A96D89F4613FECDF');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE endpoint');
        $this->addSql('DROP TABLE scope');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE session_scope');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE client (id VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, secret VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, autoApprove TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE endpoint (id INT AUTO_INCREMENT NOT NULL, redirectUri VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, clientId_id VARCHAR(40) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_3D346D2D323C022A (clientId_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scope (id INT AUTO_INCREMENT NOT NULL, scope VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, clientId VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, redirectUri VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ownerType VARCHAR(6) NOT NULL COLLATE utf8_unicode_ci, ownerId VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, authCode VARCHAR(40) DEFAULT NULL COLLATE utf8_unicode_ci, accessToken VARCHAR(40) DEFAULT NULL COLLATE utf8_unicode_ci, refreshToken VARCHAR(40) DEFAULT NULL COLLATE utf8_unicode_ci, accessTokenExpires DATE DEFAULT NULL, stage VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci, firstRequested DATE NOT NULL, lastUpdated DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_scope (session_id INT NOT NULL, scope_id INT NOT NULL, INDEX IDX_A96D89F4613FECDF (session_id), INDEX IDX_A96D89F4682B5931 (scope_id), PRIMARY KEY(session_id, scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE endpoint ADD CONSTRAINT FK_3D346D2D323C022A FOREIGN KEY (clientId_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_scope ADD CONSTRAINT FK_A96D89F4613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_scope ADD CONSTRAINT FK_A96D89F4682B5931 FOREIGN KEY (scope_id) REFERENCES scope (id) ON DELETE CASCADE');
    }
}
