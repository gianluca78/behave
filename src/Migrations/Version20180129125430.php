<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180129125430 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item CHANGE label label VARCHAR(255) NOT NULL, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL, CHANGE empty_value empty_value VARCHAR(255) DEFAULT NULL, CHANGE predefined_value predefined_value VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item CHANGE label label VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE empty_value empty_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE predefined_value predefined_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE field_value field_value VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE placeholder placeholder VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
