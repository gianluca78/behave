<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115143024 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_text (id INT AUTO_INCREMENT NOT NULL, observation_id INT DEFAULT NULL, field_name VARCHAR(255) NOT NULL, field_id VARCHAR(255) NOT NULL, field_value VARCHAR(255) NOT NULL, placeholder VARCHAR(255) NOT NULL, position_number INT NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_F3BBE33C1409DD88 (observation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_text ADD CONSTRAINT FK_F3BBE33C1409DD88 FOREIGN KEY (observation_id) REFERENCES observation (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE item_text');
    }
}
