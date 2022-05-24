<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220522181159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_content_address (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_content_phone (id INT AUTO_INCREMENT NOT NULL, api_content_user_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, INDEX IDX_7E17683545B320F (api_content_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_content_user (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4A9BE221F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_content_phone ADD CONSTRAINT FK_7E17683545B320F FOREIGN KEY (api_content_user_id) REFERENCES api_content_user (id)');
        $this->addSql('ALTER TABLE api_content_user ADD CONSTRAINT FK_4A9BE221F5B7AF75 FOREIGN KEY (address_id) REFERENCES api_content_address (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_content_user DROP FOREIGN KEY FK_4A9BE221F5B7AF75');
        $this->addSql('ALTER TABLE api_content_phone DROP FOREIGN KEY FK_7E17683545B320F');
        $this->addSql('DROP TABLE api_content_address');
        $this->addSql('DROP TABLE api_content_phone');
        $this->addSql('DROP TABLE api_content_user');
    }
}
