<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514163552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produits_commander (id INT AUTO_INCREMENT NOT NULL, _order_id INT NOT NULL, produit_id INT NOT NULL, qte INT NOT NULL, INDEX IDX_2AF19DBAA35F2858 (_order_id), INDEX IDX_2AF19DBAF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produits_commander ADD CONSTRAINT FK_2AF19DBAA35F2858 FOREIGN KEY (_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE produits_commander ADD CONSTRAINT FK_2AF19DBAF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits_commander DROP FOREIGN KEY FK_2AF19DBAA35F2858');
        $this->addSql('ALTER TABLE produits_commander DROP FOREIGN KEY FK_2AF19DBAF347EFB');
        $this->addSql('DROP TABLE produits_commander');
    }
}
