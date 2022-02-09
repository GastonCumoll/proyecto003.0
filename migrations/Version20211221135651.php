<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221135651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tipo_publicacion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publicacion ADD tipo_publicacion_id INT NOT NULL, DROP tipo_publicacion, CHANGE fecha_yhora fecha_yhora DATETIME NOT NULL');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085F16187A47 FOREIGN KEY (tipo_publicacion_id) REFERENCES tipo_publicacion (id)');
        $this->addSql('CREATE INDEX IDX_62F2085F16187A47 ON publicacion (tipo_publicacion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085F16187A47');
        $this->addSql('DROP TABLE tipo_publicacion');
        $this->addSql('DROP INDEX IDX_62F2085F16187A47 ON publicacion');
        $this->addSql('ALTER TABLE publicacion ADD tipo_publicacion VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP tipo_publicacion_id, CHANGE fecha_yhora fecha_yhora DATE NOT NULL');
    }
}
