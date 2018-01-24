<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180116163345 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE text_frequency_item (id INT AUTO_INCREMENT NOT NULL, observation_id INT DEFAULT NULL, field_name VARCHAR(255) NOT NULL, field_id VARCHAR(255) NOT NULL, field_value VARCHAR(255) NOT NULL, observation_length_in_minutes INT NOT NULL, position_number INT NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_EB0D9E7C1409DD88 (observation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE text_frequency_item ADD CONSTRAINT FK_EB0D9E7C1409DD88 FOREIGN KEY (observation_id) REFERENCES observation (id)');
        $this->addSql('ALTER TABLE text_item CHANGE field_name field_name VARCHAR(255) NOT NULL, CHANGE field_id field_id VARCHAR(255) NOT NULL, CHANGE field_value field_value VARCHAR(255) NOT NULL, CHANGE placeholder placeholder VARCHAR(255) NOT NULL, CHANGE label label VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE text_frequency_item');
        $this->addSql('ALTER TABLE text_item CHANGE field_name field_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE field_id field_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE field_value field_value VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE placeholder placeholder VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE label label VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
