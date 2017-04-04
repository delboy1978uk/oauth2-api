<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404114834 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE authcode CHANGE userId userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE refreshtoken ADD client INT DEFAULT NULL, ADD user INT DEFAULT NULL, ADD refreshToken VARCHAR(40) NOT NULL, ADD clientId INT NOT NULL, ADD userId INT DEFAULT NULL, ADD expires DATETIME NOT NULL, ADD scope VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE refreshtoken ADD CONSTRAINT FK_7142379EC7440455 FOREIGN KEY (client) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE refreshtoken ADD CONSTRAINT FK_7142379E8D93D649 FOREIGN KEY (user) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_7142379EC7440455 ON refreshtoken (client)');
        $this->addSql('CREATE INDEX IDX_7142379E8D93D649 ON refreshtoken (user)');
        $this->addSql('CREATE UNIQUE INDEX refresh_token_idx ON refreshtoken (refreshToken)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE AuthCode CHANGE userId userId INT NOT NULL');
        $this->addSql('ALTER TABLE RefreshToken DROP FOREIGN KEY FK_7142379EC7440455');
        $this->addSql('ALTER TABLE RefreshToken DROP FOREIGN KEY FK_7142379E8D93D649');
        $this->addSql('DROP INDEX IDX_7142379EC7440455 ON RefreshToken');
        $this->addSql('DROP INDEX IDX_7142379E8D93D649 ON RefreshToken');
        $this->addSql('DROP INDEX refresh_token_idx ON RefreshToken');
        $this->addSql('ALTER TABLE RefreshToken DROP client, DROP user, DROP refreshToken, DROP clientId, DROP userId, DROP expires, DROP scope');
    }
}
