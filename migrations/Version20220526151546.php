<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526151546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_address DROP FOREIGN KEY FK_FE7B8E1BA76ED395');
        $this->addSql('DROP INDEX UNIQ_FE7B8E1BA76ED395 ON api_content_address');
        $this->addSql('ALTER TABLE api_content_address CHANGE user_id api_content_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE api_content_address ADD CONSTRAINT FK_FE7B8E1B545B320F FOREIGN KEY (api_content_user_id) REFERENCES api_content_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE7B8E1B545B320F ON api_content_address (api_content_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_address DROP FOREIGN KEY FK_FE7B8E1B545B320F');
        $this->addSql('DROP INDEX UNIQ_FE7B8E1B545B320F ON api_content_address');
        $this->addSql('ALTER TABLE api_content_address CHANGE api_content_user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE api_content_address ADD CONSTRAINT FK_FE7B8E1BA76ED395 FOREIGN KEY (user_id) REFERENCES api_content_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE7B8E1BA76ED395 ON api_content_address (user_id)');
    }
}
