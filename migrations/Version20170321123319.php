<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321123319 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Session_Scope (session_id INT NOT NULL, scope_id INT NOT NULL, INDEX IDX_A96D89F4613FECDF (session_id), INDEX IDX_A96D89F4682B5931 (scope_id), PRIMARY KEY(session_id, scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Session_Scope ADD CONSTRAINT FK_A96D89F4613FECDF FOREIGN KEY (session_id) REFERENCES Session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Session_Scope ADD CONSTRAINT FK_A96D89F4682B5931 FOREIGN KEY (scope_id) REFERENCES Scope (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client CHANGE id id VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE endpoint DROP FOREIGN KEY FK_3D346D2D323C022A');
        $this->addSql('ALTER TABLE endpoint ADD CONSTRAINT FK_3D346D2D323C022A FOREIGN KEY (clientId_id) REFERENCES Client (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Session_Scope');
        $this->addSql('ALTER TABLE Client CHANGE id id VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE Endpoint DROP FOREIGN KEY FK_3D346D2D323C022A');
        $this->addSql('ALTER TABLE Endpoint ADD CONSTRAINT FK_3D346D2D323C022A FOREIGN KEY (clientId_id) REFERENCES client (id)');
    }
}
