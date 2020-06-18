<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618043546 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, user_id INT DEFAULT NULL, content LONGTEXT NOT NULL, create_date DATETIME NOT NULL, INDEX IDX_9474526CB281BE2E (trick_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, url_video VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, INDEX IDX_6A2CA10CB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_332CA4DDD60322AC (role_id), INDEX IDX_332CA4DDA76ED395 (user_id), PRIMARY KEY(role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, create_date DATETIME NOT NULL, cover VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_D8F0A91E12469DE2 (category_id), INDEX IDX_D8F0A91EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, img VARCHAR(255) DEFAULT NULL, token VARCHAR(255) NOT NULL, activate TINYINT(1) NOT NULL, slug_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E12469DE2');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB281BE2E');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CB281BE2E');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE trick');
        $this->addSql('DROP TABLE user');
    }
}
