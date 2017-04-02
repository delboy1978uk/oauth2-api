<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170402145837 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE accesstokenscope');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE accesstokenscope (scopeId VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, accessTokenId VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_BA02F3E59BA970FA (scopeId), INDEX IDX_BA02F3E573DB2BAE (accessTokenId), PRIMARY KEY(scopeId, accessTokenId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accesstokenscope ADD CONSTRAINT FK_BA02F3E573DB2BAE FOREIGN KEY (accessTokenId) REFERENCES scope (identifier)');
        $this->addSql('ALTER TABLE accesstokenscope ADD CONSTRAINT FK_BA02F3E59BA970FA FOREIGN KEY (scopeId) REFERENCES accesstoken (identifier)');
    }
}
