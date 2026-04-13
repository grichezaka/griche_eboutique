<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260413091810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform()->getName();

        if ($platform === 'sqlite') {
            $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(80) NOT NULL, slug VARCHAR(120) NOT NULL)');
            $this->addSql('CREATE UNIQUE INDEX uniq_category_slug ON category (slug)');

            $this->addSql('CREATE TABLE product_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(80) NOT NULL)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_13675885E237E06 ON product_type (name)');

            $this->addSql('CREATE TABLE user_account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(60) NOT NULL, last_name VARCHAR(60) NOT NULL, dob DATE NOT NULL, address_line1 VARCHAR(120) NOT NULL, postal_code VARCHAR(20) NOT NULL, city VARCHAR(80) NOT NULL, country VARCHAR(80) NOT NULL)');
            $this->addSql('CREATE UNIQUE INDEX uniq_user_email ON user_account (email)');

            $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(120) NOT NULL, slug VARCHAR(160) NOT NULL, description CLOB NOT NULL, price_cents INTEGER NOT NULL, image_path VARCHAR(255) DEFAULT \'\' NOT NULL, created_at DATETIME NOT NULL, category_id INTEGER NOT NULL, type_id INTEGER NOT NULL, CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D34A04ADC54C8C93 FOREIGN KEY (type_id) REFERENCES product_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
            $this->addSql('CREATE INDEX idx_product_category ON product (category_id)');
            $this->addSql('CREATE INDEX idx_product_type ON product (type_id)');
            $this->addSql('CREATE UNIQUE INDEX uniq_product_slug ON product (slug)');

            $this->addSql('CREATE TABLE shop_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL, items CLOB NOT NULL, subtotal_cents INTEGER NOT NULL, shipping_cents INTEGER NOT NULL, total_cents INTEGER NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_323FC9CAA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
            $this->addSql('CREATE INDEX idx_order_user ON shop_order (user_id)');

            return;
        }

        if ($platform === 'mysql' || $platform === 'mariadb') {
            $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, slug VARCHAR(120) NOT NULL, UNIQUE INDEX uniq_category_slug (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, UNIQUE INDEX UNIQ_13675885E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user_account (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(60) NOT NULL, last_name VARCHAR(60) NOT NULL, dob DATE NOT NULL, address_line1 VARCHAR(120) NOT NULL, postal_code VARCHAR(20) NOT NULL, city VARCHAR(80) NOT NULL, country VARCHAR(80) NOT NULL, UNIQUE INDEX uniq_user_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

            $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(120) NOT NULL, slug VARCHAR(160) NOT NULL, description LONGTEXT NOT NULL, price_cents INT NOT NULL, image_path VARCHAR(255) DEFAULT \'\' NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX idx_product_category (category_id), INDEX idx_product_type (type_id), UNIQUE INDEX uniq_product_slug (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
            $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC54C8C93 FOREIGN KEY (type_id) REFERENCES product_type (id)');

            $this->addSql('CREATE TABLE shop_order (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', items JSON NOT NULL, subtotal_cents INT NOT NULL, shipping_cents INT NOT NULL, total_cents INT NOT NULL, INDEX idx_order_user (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CAA76ED395 FOREIGN KEY (user_id) REFERENCES user_account (id)');

            return;
        }

        $this->abortIf(true, 'Unsupported database platform: '.$platform);
    }

    public function down(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform()->getName();

        if ($platform === 'sqlite') {
            $this->addSql('DROP TABLE category');
            $this->addSql('DROP TABLE product');
            $this->addSql('DROP TABLE product_type');
            $this->addSql('DROP TABLE shop_order');
            $this->addSql('DROP TABLE user_account');
            return;
        }

        if ($platform === 'mysql' || $platform === 'mariadb') {
            $this->addSql('DROP TABLE shop_order');
            $this->addSql('DROP TABLE product');
            $this->addSql('DROP TABLE user_account');
            $this->addSql('DROP TABLE product_type');
            $this->addSql('DROP TABLE category');
            return;
        }

        $this->abortIf(true, 'Unsupported database platform: '.$platform);
    }
}
