<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210124171145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, restaurants_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_F529939867B3B43D (users_id), INDEX IDX_F52993984DCA160A (restaurants_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, restaurants_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D34A04AD4DCA160A (restaurants_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_order (product_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_5475E8C44584665A (product_id), INDEX IDX_5475E8C48D9F6D38 (order_id), PRIMARY KEY(product_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_EB95123F67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939867B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984DCA160A FOREIGN KEY (restaurants_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD4DCA160A FOREIGN KEY (restaurants_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C44584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C48D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F67B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C48D9F6D38');
        $this->addSql('ALTER TABLE product_order DROP FOREIGN KEY FK_5475E8C44584665A');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984DCA160A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD4DCA160A');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939867B3B43D');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F67B3B43D');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_order');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE `user`');
    }
}
