<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180327132056 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE observation_date (id INT AUTO_INCREMENT NOT NULL, observation_id INT NOT NULL, start_date_timestamp DATETIME NOT NULL, end_date_timestamp DATETIME NOT NULL, INDEX IDX_C8CB89CF1409DD88 (observation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE observation_date ADD CONSTRAINT FK_C8CB89CF1409DD88 FOREIGN KEY (observation_id) REFERENCES observation (id)');
        $this->addSql('ALTER TABLE observation CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description TINYTEXT NOT NULL');
        $this->addSql('ALTER TABLE item CHANGE label label VARCHAR(255) NOT NULL, CHANGE empty_value empty_value VARCHAR(255) DEFAULT NULL, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL, CHANGE label_y label_y VARCHAR(255) DEFAULT NULL, CHANGE label_max_y label_max_y VARCHAR(255) DEFAULT NULL, CHANGE label_min_y label_min_y VARCHAR(255) DEFAULT NULL, CHANGE label_x label_x VARCHAR(255) DEFAULT NULL, CHANGE label_max_x label_max_x VARCHAR(255) DEFAULT NULL, CHANGE label_min_x label_min_x VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE observation_date');
        $this->addSql('ALTER TABLE item CHANGE label label VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE empty_value empty_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_y label_y VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_max_y label_max_y VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_min_y label_min_y VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_x label_x VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_max_x label_max_x VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE label_min_x label_min_x VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE observation CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE description description TINYTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
