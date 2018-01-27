<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180127152500 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Client_Scope (client_id INT NOT NULL, scope_id VARCHAR(40) NOT NULL, INDEX IDX_7A323D9819EB6921 (client_id), INDEX IDX_7A323D98682B5931 (scope_id), PRIMARY KEY(client_id, scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Client_Scope ADD CONSTRAINT FK_7A323D9819EB6921 FOREIGN KEY (client_id) REFERENCES Client (id)');
        $this->addSql('ALTER TABLE Client_Scope ADD CONSTRAINT FK_7A323D98682B5931 FOREIGN KEY (scope_id) REFERENCES Scope (identifier)');
        $this->addSql("INSERT INTO `Scope` (`identifier`) VALUES ('email');");
        $this->addSql("INSERT INTO `Scope` (`identifier`) VALUES ('profile');");
        $this->addSql("INSERT INTO `Client_Scope` (`client_id`, `scope_id`) VALUES (1, 'email');");
        $this->addSql("INSERT INTO `Client_Scope` (`client_id`, `scope_id`) VALUES (1, 'profile');");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM Client_Scope');
        $this->addSql('DELETE FROM Scope');
        $this->addSql('DROP TABLE Client_Scope');
    }
}
