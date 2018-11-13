<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181113135650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Client DROP FOREIGN KEY FK_C0E801638D93D649');
        $this->addSql('DROP INDEX IDX_C0E801638D93D649 ON Client');
        $this->addSql('ALTER TABLE Client CHANGE user user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Client ADD CONSTRAINT FK_C0E80163A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_C0E80163A76ED395 ON Client (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Client DROP FOREIGN KEY FK_C0E80163A76ED395');
        $this->addSql('DROP INDEX IDX_C0E80163A76ED395 ON Client');
        $this->addSql('ALTER TABLE Client CHANGE user_id user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Client ADD CONSTRAINT FK_C0E801638D93D649 FOREIGN KEY (user) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_C0E801638D93D649 ON Client (user)');
    }
}
