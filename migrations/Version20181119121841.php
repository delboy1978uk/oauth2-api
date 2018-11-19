<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181119121841 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE RefreshToken (id INT AUTO_INCREMENT NOT NULL, identifier VARCHAR(40) NOT NULL, expiryDateTime DATE DEFAULT NULL, revoked TINYINT(1) NOT NULL, accessToken_id INT DEFAULT NULL, INDEX IDX_7142379E4C5BE87 (accessToken_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(100) NOT NULL, state INT NOT NULL, registrationDate DATE DEFAULT NULL, lastLoginDate DATE DEFAULT NULL, class VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2DA17977217BBB47 (person_id), UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Scope (id INT AUTO_INCREMENT NOT NULL, identifier VARCHAR(40) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AccessToken (id INT AUTO_INCREMENT NOT NULL, client INT DEFAULT NULL, expiryDateTime DATE DEFAULT NULL, userIdentifier INT DEFAULT NULL, identifier LONGTEXT NOT NULL, revoked TINYINT(1) NOT NULL, INDEX IDX_B39617F5C7440455 (client), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AccessToken_Scope (accesstoken_id INT NOT NULL, scope_id INT NOT NULL, INDEX IDX_83FAAB42FDB3DCD1 (accesstoken_id), INDEX IDX_83FAAB42682B5931 (scope_id), PRIMARY KEY(accesstoken_id, scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AuthCode (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, client INT DEFAULT NULL, redirectUri VARCHAR(255) DEFAULT NULL, expiryDateTime DATETIME DEFAULT NULL, identifier LONGTEXT NOT NULL, revoked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_F1D7D1778D93D649 (user), INDEX IDX_F1D7D177C7440455 (client), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Client (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(40) NOT NULL, description VARCHAR(255) NOT NULL, icon VARCHAR(100) NOT NULL, grantType VARCHAR(20) NOT NULL, redirectUri VARCHAR(255) NOT NULL, identifier VARCHAR(40) NOT NULL, secret VARCHAR(40) DEFAULT NULL, confidential TINYINT(1) NOT NULL, INDEX IDX_C0E80163A76ED395 (user_id), UNIQUE INDEX indentifier_idx (identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Client_Scope (client_id INT NOT NULL, scope_id INT NOT NULL, INDEX IDX_7A323D9819EB6921 (client_id), INDEX IDX_7A323D98682B5931 (scope_id), PRIMARY KEY(client_id, scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EmailLink (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, expiry_date DATETIME NOT NULL, token VARCHAR(255) NOT NULL, INDEX IDX_D0C08DD0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Person (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(60) DEFAULT NULL, middlename VARCHAR(60) DEFAULT NULL, lastname VARCHAR(60) DEFAULT NULL, aka VARCHAR(50) DEFAULT NULL, dob DATE DEFAULT NULL, birthplace VARCHAR(50) DEFAULT NULL, country VARCHAR(3) DEFAULT NULL, image VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE RefreshToken ADD CONSTRAINT FK_7142379E4C5BE87 FOREIGN KEY (accessToken_id) REFERENCES AccessToken (id)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977217BBB47 FOREIGN KEY (person_id) REFERENCES Person (id)');
        $this->addSql('ALTER TABLE AccessToken ADD CONSTRAINT FK_B39617F5C7440455 FOREIGN KEY (client) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE AccessToken_Scope ADD CONSTRAINT FK_83FAAB42FDB3DCD1 FOREIGN KEY (accesstoken_id) REFERENCES AccessToken (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE AccessToken_Scope ADD CONSTRAINT FK_83FAAB42682B5931 FOREIGN KEY (scope_id) REFERENCES Scope (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE AuthCode ADD CONSTRAINT FK_F1D7D1778D93D649 FOREIGN KEY (user) REFERENCES User (id)');
        $this->addSql('ALTER TABLE AuthCode ADD CONSTRAINT FK_F1D7D177C7440455 FOREIGN KEY (client) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE Client ADD CONSTRAINT FK_C0E80163A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Client_Scope ADD CONSTRAINT FK_7A323D9819EB6921 FOREIGN KEY (client_id) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE Client_Scope ADD CONSTRAINT FK_7A323D98682B5931 FOREIGN KEY (scope_id) REFERENCES Scope (id)');
        $this->addSql('ALTER TABLE EmailLink ADD CONSTRAINT FK_D0C08DD0A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql("INSERT INTO `Person` (`id`, `firstname`, `middlename`, `lastname`, `aka`, `dob`, `birthplace`, `country`, `image`) VALUES (1, 'Derek', 'Stephen', 'McLean', 'Delboy', '1978-02-17', 'Glasgow', 'GB', NULL);");
        $this->addSql("INSERT INTO `User` (`id`, `person_id`, `email`, `password`, `state`, `registrationDate`, `lastLoginDate`, `class`) VALUES (1, 1, 'delboy1978uk@gmail.com', '$2y$14$80YHRgTqqOcAWplaBatC8.BGuJW3JDgtE42FyRDw.y4hdPasbMdqu', 1, '2018-01-27', '2018-01-27', 'oauthuser');");
        $this->addSql("INSERT INTO `Client` (`id`, `name`, `description`, `icon`, `grantType`, `redirectUri`, `identifier`, `secret`, `confidential`, `user_id`) VALUES (1, 'BoneMVC App', 'Official first party web client', 'https://awesome.scot/img/emails/logo.jpg', 'client_credentials', 'https://awesome.scot/fake-client-callback', 'ceac682a9a4808bf910ad49134230e0e', 'JDJ5JDEwJGNEd1J1VEdOY0YxS3QvL0pWQzMxay52', 1, 1);");
        $this->addSql("INSERT INTO `Client` (`id`, `user_id`, `name`, `description`, `icon`, `grantType`, `redirectUri`, `identifier`, `secret`, `confidential`) VALUES (2, 1, 'testclient', 'iPhone App client', 'https://awesome.scot/img/emails/logo.jpg', 'code', 'https://awesome.scot/fake-client-callback', '83c44d2a7b80fff51591478a4936fa7d', NULL, 0);");
        $this->addSql("INSERT INTO `Scope` (`identifier`, `description`) VALUES ('admin', 'Trusted client superuser scope');");
        $this->addSql("INSERT INTO `Scope` (`id`, `identifier`, `description`) VALUES (2, 'test_scope', 'Scope used in acceptance tests');");
        $this->addSql('INSERT INTO `Client_Scope` (`client_id`, `scope_id`) VALUES (1, 1);');
        $this->addSql('INSERT INTO `Client_Scope` (`client_id`, `scope_id`) VALUES (2, 2);');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AuthCode DROP FOREIGN KEY FK_F1D7D1778D93D649');
        $this->addSql('ALTER TABLE Client DROP FOREIGN KEY FK_C0E80163A76ED395');
        $this->addSql('ALTER TABLE EmailLink DROP FOREIGN KEY FK_D0C08DD0A76ED395');
        $this->addSql('ALTER TABLE AccessToken_Scope DROP FOREIGN KEY FK_83FAAB42682B5931');
        $this->addSql('ALTER TABLE Client_Scope DROP FOREIGN KEY FK_7A323D98682B5931');
        $this->addSql('ALTER TABLE RefreshToken DROP FOREIGN KEY FK_7142379E4C5BE87');
        $this->addSql('ALTER TABLE AccessToken_Scope DROP FOREIGN KEY FK_83FAAB42FDB3DCD1');
        $this->addSql('ALTER TABLE AccessToken DROP FOREIGN KEY FK_B39617F5C7440455');
        $this->addSql('ALTER TABLE AuthCode DROP FOREIGN KEY FK_F1D7D177C7440455');
        $this->addSql('ALTER TABLE Client_Scope DROP FOREIGN KEY FK_7A323D9819EB6921');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977217BBB47');
        $this->addSql('DROP TABLE RefreshToken');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE Scope');
        $this->addSql('DROP TABLE AccessToken');
        $this->addSql('DROP TABLE AccessToken_Scope');
        $this->addSql('DROP TABLE AuthCode');
        $this->addSql('DROP TABLE Client');
        $this->addSql('DROP TABLE Client_Scope');
        $this->addSql('DROP TABLE EmailLink');
        $this->addSql('DROP TABLE Person');
    }
}
