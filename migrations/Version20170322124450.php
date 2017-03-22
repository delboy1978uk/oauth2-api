<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170322124450 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE AuthCode (identifier VARCHAR(40) NOT NULL, redirectUri VARCHAR(255) DEFAULT NULL, expiryDateTime DATE DEFAULT NULL, client INT DEFAULT NULL, INDEX IDX_F1D7D177C7440455 (client), PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE AuthTokenScope (scopeId VARCHAR(40) NOT NULL, authTokenId VARCHAR(40) NOT NULL, INDEX IDX_351539299BA970FA (scopeId), INDEX IDX_35153929A1F3197E (authTokenId), PRIMARY KEY(scopeId, authTokenId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RefreshToken (identifier VARCHAR(40) NOT NULL, expiryDateTime DATE DEFAULT NULL, accessToken VARCHAR(40) DEFAULT NULL, UNIQUE INDEX UNIQ_7142379E350A9822 (accessToken), PRIMARY KEY(identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE AuthTokenScope ADD CONSTRAINT FK_351539299BA970FA FOREIGN KEY (scopeId) REFERENCES AuthCode (identifier)');
        $this->addSql('ALTER TABLE AuthTokenScope ADD CONSTRAINT FK_35153929A1F3197E FOREIGN KEY (authTokenId) REFERENCES Scope (identifier)');
        $this->addSql('ALTER TABLE RefreshToken ADD CONSTRAINT FK_7142379E350A9822 FOREIGN KEY (accessToken) REFERENCES AccessToken (identifier)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AuthTokenScope DROP FOREIGN KEY FK_351539299BA970FA');
        $this->addSql('DROP TABLE AuthCode');
        $this->addSql('DROP TABLE AuthTokenScope');
        $this->addSql('DROP TABLE RefreshToken');
    }
}
