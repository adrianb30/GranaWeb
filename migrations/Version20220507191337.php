<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507191337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carrito (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_77E6BED5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carrito_detalle (id INT AUTO_INCREMENT NOT NULL, carrito_id INT NOT NULL, cantidad INT NOT NULL, total INT NOT NULL, INDEX IDX_41291588DE2CF6E7 (carrito_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carrito_detalle_producto (carrito_detalle_id INT NOT NULL, producto_id INT NOT NULL, INDEX IDX_FE8C7BEBFC60F131 (carrito_detalle_id), INDEX IDX_FE8C7BEB7645698E (producto_id), PRIMARY KEY(carrito_detalle_id, producto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carrito ADD CONSTRAINT FK_77E6BED5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE carrito_detalle ADD CONSTRAINT FK_41291588DE2CF6E7 FOREIGN KEY (carrito_id) REFERENCES carrito (id)');
        $this->addSql('ALTER TABLE carrito_detalle_producto ADD CONSTRAINT FK_FE8C7BEBFC60F131 FOREIGN KEY (carrito_detalle_id) REFERENCES carrito_detalle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carrito_detalle_producto ADD CONSTRAINT FK_FE8C7BEB7645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producto ADD categoria_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE producto ADD CONSTRAINT FK_A7BB06153397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('CREATE INDEX IDX_A7BB06153397707A ON producto (categoria_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carrito_detalle DROP FOREIGN KEY FK_41291588DE2CF6E7');
        $this->addSql('ALTER TABLE carrito_detalle_producto DROP FOREIGN KEY FK_FE8C7BEBFC60F131');
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB06153397707A');
        $this->addSql('DROP TABLE carrito');
        $this->addSql('DROP TABLE carrito_detalle');
        $this->addSql('DROP TABLE carrito_detalle_producto');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP INDEX IDX_A7BB06153397707A ON producto');
        $this->addSql('ALTER TABLE producto DROP categoria_id');
    }
}
