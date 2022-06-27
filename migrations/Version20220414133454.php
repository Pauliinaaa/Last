<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414133454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A59D8A214 ON comments (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(64) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A59D8A214');
        $this->addSql('DROP INDEX IDX_5F9E962A59D8A214 ON comments');
        $this->addSql('ALTER TABLE comments DROP recipe_id, CHANGE user_nick user_nick VARCHAR(25) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE user_email user_email VARCHAR(128) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE content content VARCHAR(1020) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE recipes CHANGE name name VARCHAR(120) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE content content VARCHAR(1020) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE users CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
