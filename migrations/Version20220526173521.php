<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526173521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_user ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE api_content_user ADD CONSTRAINT FK_4A9BE221F5B7AF75 FOREIGN KEY (address_id) REFERENCES api_content_address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A9BE221F5B7AF75 ON api_content_user (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_user DROP FOREIGN KEY FK_4A9BE221F5B7AF75');
        $this->addSql('DROP INDEX UNIQ_4A9BE221F5B7AF75 ON api_content_user');
        $this->addSql('ALTER TABLE api_content_user DROP address_id');
    }
}
