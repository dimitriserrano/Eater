<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210124171851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F67B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB95123F67B3B43D ON restaurant (users_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DCA160A');
        $this->addSql('DROP INDEX UNIQ_8D93D6494DCA160A ON user');
        $this->addSql('ALTER TABLE user DROP restaurants_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F67B3B43D');
        $this->addSql('DROP INDEX UNIQ_EB95123F67B3B43D ON restaurant');
        $this->addSql('ALTER TABLE restaurant DROP users_id');
        $this->addSql('ALTER TABLE `user` ADD restaurants_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6494DCA160A FOREIGN KEY (restaurants_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494DCA160A ON `user` (restaurants_id)');
    }
}
