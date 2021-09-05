<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905135741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, email_provider_id INT NOT NULL, sender VARCHAR(255) NOT NULL, recipients JSON NOT NULL, cc JSON NOT NULL, bcc JSON NOT NULL, subject VARCHAR(998) NOT NULL, body LONGTEXT NOT NULL, INDEX IDX_E7927C741C93D41D (email_provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_provider (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, executed_at DATETIME DEFAULT NULL, INDEX IDX_99A26CD5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C741C93D41D FOREIGN KEY (email_provider_id) REFERENCES email_provider (id)');
        $this->addSql('ALTER TABLE email_provider ADD CONSTRAINT FK_99A26CD5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C741C93D41D');
        $this->addSql('ALTER TABLE email_provider DROP FOREIGN KEY FK_99A26CD5A76ED395');
        $this->addSql('DROP TABLE email');
        $this->addSql('DROP TABLE email_provider');
        $this->addSql('DROP TABLE user');
    }
}
