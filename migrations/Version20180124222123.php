<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180124222123 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE RefreshToken (identifier VARCHAR(40) NOT NULL, expiryDateTime DATE DEFAULT NULL, accessToken VARCHAR(40) DEFAULT NULL, UNIQUE INDEX UNIQ_7142379E350A9822 (accessToken), PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Scope (identifier VARCHAR(40) NOT NULL, PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(100) NOT NULL, state INT NOT NULL, registrationDate DATE DEFAULT NULL, lastLoginDate DATE DEFAULT NULL, UNIQUE INDEX UNIQ_2DA17977217BBB47 (person_id), UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AccessToken (identifier VARCHAR(40) NOT NULL, client VARCHAR(40) DEFAULT NULL, expiryDateTime DATE DEFAULT NULL, userIdentifier INT NOT NULL, INDEX IDX_B39617F5C7440455 (client), PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AccessTokenScope (scopeId VARCHAR(40) NOT NULL, accessTokenId VARCHAR(40) NOT NULL, INDEX IDX_BA02F3E59BA970FA (scopeId), INDEX IDX_BA02F3E573DB2BAE (accessTokenId), PRIMARY KEY(scopeId, accessTokenId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AuthCode (identifier VARCHAR(40) NOT NULL, redirectUri VARCHAR(255) DEFAULT NULL, expiryDateTime DATE DEFAULT NULL, client INT DEFAULT NULL, UNIQUE INDEX UNIQ_F1D7D177C7440455 (client), PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AuthTokenScope (scopeId VARCHAR(40) NOT NULL, authTokenId VARCHAR(40) NOT NULL, INDEX IDX_351539299BA970FA (scopeId), INDEX IDX_35153929A1F3197E (authTokenId), PRIMARY KEY(scopeId, authTokenId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Client (identifier VARCHAR(40) NOT NULL, name VARCHAR(40) NOT NULL, redirectUri VARCHAR(255) NOT NULL, PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EmailLink (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, expiry_date DATETIME NOT NULL, token VARCHAR(255) NOT NULL, INDEX IDX_D0C08DD0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Person (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(60) DEFAULT NULL, middlename VARCHAR(60) DEFAULT NULL, lastname VARCHAR(60) DEFAULT NULL, aka VARCHAR(50) DEFAULT NULL, dob DATE DEFAULT NULL, birthplace VARCHAR(50) DEFAULT NULL, country VARCHAR(3) DEFAULT NULL, image VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE RefreshToken ADD CONSTRAINT FK_7142379E350A9822 FOREIGN KEY (accessToken) REFERENCES AccessToken (identifier)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977217BBB47 FOREIGN KEY (person_id) REFERENCES Person (id)');
        $this->addSql('ALTER TABLE AccessToken ADD CONSTRAINT FK_B39617F5C7440455 FOREIGN KEY (client) REFERENCES Client (identifier)');
        $this->addSql('ALTER TABLE AccessTokenScope ADD CONSTRAINT FK_BA02F3E59BA970FA FOREIGN KEY (scopeId) REFERENCES AccessToken (identifier)');
        $this->addSql('ALTER TABLE AccessTokenScope ADD CONSTRAINT FK_BA02F3E573DB2BAE FOREIGN KEY (accessTokenId) REFERENCES Scope (identifier)');
        $this->addSql('ALTER TABLE AuthTokenScope ADD CONSTRAINT FK_351539299BA970FA FOREIGN KEY (scopeId) REFERENCES AuthCode (identifier)');
        $this->addSql('ALTER TABLE AuthTokenScope ADD CONSTRAINT FK_35153929A1F3197E FOREIGN KEY (authTokenId) REFERENCES Scope (identifier)');
        $this->addSql('ALTER TABLE EmailLink ADD CONSTRAINT FK_D0C08DD0A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AccessTokenScope DROP FOREIGN KEY FK_BA02F3E573DB2BAE');
        $this->addSql('ALTER TABLE AuthTokenScope DROP FOREIGN KEY FK_35153929A1F3197E');
        $this->addSql('ALTER TABLE EmailLink DROP FOREIGN KEY FK_D0C08DD0A76ED395');
        $this->addSql('ALTER TABLE RefreshToken DROP FOREIGN KEY FK_7142379E350A9822');
        $this->addSql('ALTER TABLE AccessTokenScope DROP FOREIGN KEY FK_BA02F3E59BA970FA');
        $this->addSql('ALTER TABLE AuthTokenScope DROP FOREIGN KEY FK_351539299BA970FA');
        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F5C7440455');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977217BBB47');
        $this->addSql('DROP TABLE RefreshToken');
        $this->addSql('DROP TABLE Scope');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE AccessToken');
        $this->addSql('DROP TABLE AccessTokenScope');
        $this->addSql('DROP TABLE AuthCode');
        $this->addSql('DROP TABLE AuthTokenScope');
        $this->addSql('DROP TABLE Client');
        $this->addSql('DROP TABLE EmailLink');
        $this->addSql('DROP TABLE Person');
    }
}
