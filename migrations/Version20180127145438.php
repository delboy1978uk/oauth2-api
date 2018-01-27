<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180127145438 extends AbstractMigration
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
        $this->addSql('CREATE TABLE AccessToken (identifier VARCHAR(40) NOT NULL, client INT DEFAULT NULL, expiryDateTime DATE DEFAULT NULL, userIdentifier INT NOT NULL, INDEX IDX_B39617F5C7440455 (client), PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AuthCode (identifier VARCHAR(40) NOT NULL, redirectUri VARCHAR(255) DEFAULT NULL, expiryDateTime DATE DEFAULT NULL, client INT DEFAULT NULL, UNIQUE INDEX UNIQ_F1D7D177C7440455 (client), PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Client (id INT NOT NULL, name VARCHAR(40) NOT NULL, redirectUri VARCHAR(255) NOT NULL, identifier VARCHAR(40) NOT NULL, secret VARCHAR(40) DEFAULT NULL, UNIQUE INDEX indentifier_idx (identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EmailLink (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, expiry_date DATETIME NOT NULL, token VARCHAR(255) NOT NULL, INDEX IDX_D0C08DD0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Person (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(60) DEFAULT NULL, middlename VARCHAR(60) DEFAULT NULL, lastname VARCHAR(60) DEFAULT NULL, aka VARCHAR(50) DEFAULT NULL, dob DATE DEFAULT NULL, birthplace VARCHAR(50) DEFAULT NULL, country VARCHAR(3) DEFAULT NULL, image VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE RefreshToken ADD CONSTRAINT FK_7142379E350A9822 FOREIGN KEY (accessToken) REFERENCES AccessToken (identifier)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977217BBB47 FOREIGN KEY (person_id) REFERENCES Person (id)');
        $this->addSql('ALTER TABLE AccessToken ADD CONSTRAINT FK_B39617F5C7440455 FOREIGN KEY (client) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE EmailLink ADD CONSTRAINT FK_D0C08DD0A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('INSERT INTO `Person` (`id`, `firstname`, `middlename`, `lastname`, `aka`, `dob`, `birthplace`, `country`, `image`) VALUES (1, \'Derek\', \'Stephen\', \'McLean\', \'Delboy\', \'1978-02-17\', \'Glasgow\', \'GB\', NULL);');
        $this->addSql('INSERT INTO `User` (`id`, `person_id`, `email`, `password`, `state`, `registrationDate`, `lastLoginDate`) VALUES (1, 1, \'delboy1978uk@gmail.com\', \'$2y$14$80YHRgTqqOcAWplaBatC8.BGuJW3JDgtE42FyRDw.y4hdPasbMdqu\', 1, \'2018-01-27\', \'2018-01-27\');');
        $this->addSql('INSERT INTO `Client` (`id`, `identifier`, `name`, `redirectUri`, `secret`) VALUES (1, \'testclient\', \'Test Client\', \'\', \'JDJ5JDEwJDJmd1Nya1FWSDhQaDZydHVuc29jZnV2\');');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM `Client`');
        $this->addSql('DELETE FROM `User`');
        $this->addSql('DELETE FROM `Person`');
        $this->addSql('ALTER TABLE EmailLink DROP FOREIGN KEY FK_D0C08DD0A76ED395');
        $this->addSql('ALTER TABLE EmailLink DROP FOREIGN KEY FK_D0C08DD0A76ED395');
        $this->addSql('ALTER TABLE EmailLink DROP FOREIGN KEY FK_D0C08DD0A76ED395');
        $this->addSql('ALTER TABLE RefreshToken DROP FOREIGN KEY FK_7142379E350A9822');
        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F5C7440455');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977217BBB47');
        $this->addSql('DROP TABLE RefreshToken');
        $this->addSql('DROP TABLE Scope');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE AccessToken');
        $this->addSql('DROP TABLE AuthCode');
        $this->addSql('DROP TABLE Client');
        $this->addSql('DROP TABLE EmailLink');
        $this->addSql('DROP TABLE Person');
    }
}
