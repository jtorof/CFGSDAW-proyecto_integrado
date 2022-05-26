<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526150751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_address ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE api_content_address ADD CONSTRAINT FK_FE7B8E1BA76ED395 FOREIGN KEY (user_id) REFERENCES api_content_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE7B8E1BA76ED395 ON api_content_address (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_address DROP FOREIGN KEY FK_FE7B8E1BA76ED395');
        $this->addSql('DROP INDEX UNIQ_FE7B8E1BA76ED395 ON api_content_address');
        $this->addSql('ALTER TABLE api_content_address DROP user_id');
    }
}
