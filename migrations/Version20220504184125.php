<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504184125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carrito (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, total INT NOT NULL, UNIQUE INDEX UNIQ_77E6BED5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detalle_carrito (id INT AUTO_INCREMENT NOT NULL, carrito_id INT NOT NULL, producto_id INT NOT NULL, total INT NOT NULL, cantidad INT NOT NULL, INDEX IDX_3F38507DDE2CF6E7 (carrito_id), INDEX IDX_3F38507D7645698E (producto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carrito ADD CONSTRAINT FK_77E6BED5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE detalle_carrito ADD CONSTRAINT FK_3F38507DDE2CF6E7 FOREIGN KEY (carrito_id) REFERENCES carrito (id)');
        $this->addSql('ALTER TABLE detalle_carrito ADD CONSTRAINT FK_3F38507D7645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_carrito DROP FOREIGN KEY FK_3F38507DDE2CF6E7');
        $this->addSql('DROP TABLE carrito');
        $this->addSql('DROP TABLE detalle_carrito');
    }
}
