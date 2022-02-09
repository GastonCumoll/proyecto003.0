<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201144438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE edicion (id INT AUTO_INCREMENT NOT NULL, publicacion_id INT NOT NULL, usuario_creador_id INT NOT NULL, fecha_de_edicion DATETIME DEFAULT NULL, cantidad_impresiones INT NOT NULL, fecha_yhora_creacion DATETIME DEFAULT NULL, INDEX IDX_655F77399ACBB5E7 (publicacion_id), INDEX IDX_655F773991C4469F (usuario_creador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, tipo_operacion VARCHAR(255) NOT NULL, fecha_yhora DATETIME NOT NULL, tipo_publicacion VARCHAR(255) NOT NULL, usuario VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publicacion (id INT AUTO_INCREMENT NOT NULL, usuario_creador_id INT NOT NULL, tipo_publicacion_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, fecha_yhora DATETIME DEFAULT NULL, INDEX IDX_62F2085F91C4469F (usuario_creador_id), INDEX IDX_62F2085F16187A47 (tipo_publicacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suscripcion (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, tipo_publicacion_id INT NOT NULL, fecha_suscripcion DATETIME DEFAULT NULL, INDEX IDX_497FA0DB38439E (usuario_id), INDEX IDX_497FA016187A47 (tipo_publicacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_publicacion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE edicion ADD CONSTRAINT FK_655F77399ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id)');
        $this->addSql('ALTER TABLE edicion ADD CONSTRAINT FK_655F773991C4469F FOREIGN KEY (usuario_creador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085F91C4469F FOREIGN KEY (usuario_creador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085F16187A47 FOREIGN KEY (tipo_publicacion_id) REFERENCES tipo_publicacion (id)');
        $this->addSql('ALTER TABLE suscripcion ADD CONSTRAINT FK_497FA0DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suscripcion ADD CONSTRAINT FK_497FA016187A47 FOREIGN KEY (tipo_publicacion_id) REFERENCES tipo_publicacion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edicion DROP FOREIGN KEY FK_655F77399ACBB5E7');
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085F16187A47');
        $this->addSql('ALTER TABLE suscripcion DROP FOREIGN KEY FK_497FA016187A47');
        $this->addSql('ALTER TABLE edicion DROP FOREIGN KEY FK_655F773991C4469F');
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085F91C4469F');
        $this->addSql('ALTER TABLE suscripcion DROP FOREIGN KEY FK_497FA0DB38439E');
        $this->addSql('DROP TABLE edicion');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE publicacion');
        $this->addSql('DROP TABLE suscripcion');
        $this->addSql('DROP TABLE tipo_publicacion');
        $this->addSql('DROP TABLE user');
    }
}
