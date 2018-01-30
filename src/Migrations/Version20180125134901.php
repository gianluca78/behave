<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180125134901 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE range_item (id INT AUTO_INCREMENT NOT NULL, observation_id INT DEFAULT NULL, min INT NOT NULL, max INT NOT NULL, step INT NOT NULL, INDEX IDX_D7B54271409DD88 (observation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE range_item ADD CONSTRAINT FK_D7B54271409DD88 FOREIGN KEY (observation_id) REFERENCES observation (id)');
        $this->addSql('ALTER TABLE item CHANGE label label VARCHAR(255) NOT NULL, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE range_item');
        $this->addSql('ALTER TABLE item CHANGE label label VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
