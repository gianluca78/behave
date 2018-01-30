<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180125144131 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item CHANGE label label VARCHAR(255) NOT NULL, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251409DD88');
        $this->addSql('DROP INDEX IDX_DADD4A251409DD88 ON answer');
        $this->addSql('ALTER TABLE answer DROP observation_id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer ADD observation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251409DD88 FOREIGN KEY (observation_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_DADD4A251409DD88 ON answer (observation_id)');
        $this->addSql('ALTER TABLE item CHANGE label label VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
