<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180125121922 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, observation_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_DADD4A251409DD88 (observation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251409DD88 FOREIGN KEY (observation_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item DROP field_name, DROP field_id, CHANGE label label VARCHAR(255) NOT NULL, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE answer');
        $this->addSql('ALTER TABLE item ADD field_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD field_id VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label label VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
