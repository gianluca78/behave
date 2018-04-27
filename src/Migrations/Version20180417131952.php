<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417131952 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item ADD partial_length_in_seconds INT DEFAULT NULL, ADD observation_length_in_seconds INT DEFAULT NULL, ADD typology VARCHAR(255) DEFAULT NULL, CHANGE label label VARCHAR(255) NOT NULL, CHANGE empty_value empty_value VARCHAR(255) DEFAULT NULL, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL, CHANGE label_y label_y VARCHAR(255) DEFAULT NULL, CHANGE label_max_y label_max_y VARCHAR(255) DEFAULT NULL, CHANGE label_min_y label_min_y VARCHAR(255) DEFAULT NULL, CHANGE label_x label_x VARCHAR(255) DEFAULT NULL, CHANGE label_max_x label_max_x VARCHAR(255) DEFAULT NULL, CHANGE label_min_x label_min_x VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE observation CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description TINYTEXT NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP partial_length_in_seconds, DROP observation_length_in_seconds, DROP typology, CHANGE label label VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE empty_value empty_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_y label_y VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_max_y label_max_y VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_min_y label_min_y VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_x label_x VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_max_x label_max_x VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_min_x label_min_x VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE observation CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE description description TINYTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
