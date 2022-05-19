<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518183120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detalle_pedido (id INT AUTO_INCREMENT NOT NULL, pedido_id INT NOT NULL, cantidad INT NOT NULL, total INT NOT NULL, INDEX IDX_A834F5694854653A (pedido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detalle_pedido_producto (detalle_pedido_id INT NOT NULL, producto_id INT NOT NULL, INDEX IDX_67537C666938D47A (detalle_pedido_id), INDEX IDX_67537C667645698E (producto_id), PRIMARY KEY(detalle_pedido_id, producto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedido (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha DATETIME NOT NULL, estado VARCHAR(255) DEFAULT NULL, transaccionid VARCHAR(255) NOT NULL, INDEX IDX_C4EC16CEDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detalle_pedido ADD CONSTRAINT FK_A834F5694854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id)');
        $this->addSql('ALTER TABLE detalle_pedido_producto ADD CONSTRAINT FK_67537C666938D47A FOREIGN KEY (detalle_pedido_id) REFERENCES detalle_pedido (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE detalle_pedido_producto ADD CONSTRAINT FK_67537C667645698E FOREIGN KEY (producto_id) REFERENCES producto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pedido ADD CONSTRAINT FK_C4EC16CEDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_pedido_producto DROP FOREIGN KEY FK_67537C666938D47A');
        $this->addSql('ALTER TABLE detalle_pedido DROP FOREIGN KEY FK_A834F5694854653A');
        $this->addSql('DROP TABLE detalle_pedido');
        $this->addSql('DROP TABLE detalle_pedido_producto');
        $this->addSql('DROP TABLE pedido');
    }
}
