<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220522202323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_user ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE api_content_user ADD CONSTRAINT FK_4A9BE221A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_4A9BE221A76ED395 ON api_content_user (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_user DROP FOREIGN KEY FK_4A9BE221A76ED395');
        $this->addSql('DROP INDEX IDX_4A9BE221A76ED395 ON api_content_user');
        $this->addSql('ALTER TABLE api_content_user DROP user_id');
    }
}
