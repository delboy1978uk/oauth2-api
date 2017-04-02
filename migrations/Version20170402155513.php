<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170402155513 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE AccessTokenScope (scopeId VARCHAR(40) NOT NULL, accessTokenId VARCHAR(40) NOT NULL, INDEX IDX_BA02F3E59BA970FA (scopeId), INDEX IDX_BA02F3E573DB2BAE (accessTokenId), PRIMARY KEY(scopeId, accessTokenId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE AccessTokenScope ADD CONSTRAINT FK_BA02F3E59BA970FA FOREIGN KEY (scopeId) REFERENCES AccessToken (identifier)');
        $this->addSql('ALTER TABLE AccessTokenScope ADD CONSTRAINT FK_BA02F3E573DB2BAE FOREIGN KEY (accessTokenId) REFERENCES Scope (identifier)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE AccessTokenScope');
    }
}
