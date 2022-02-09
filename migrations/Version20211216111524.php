<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216111524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE suscripcion (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(255) NOT NULL, fecha_suscripcion DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publicacion ADD suscripcion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085F189E045D FOREIGN KEY (suscripcion_id) REFERENCES suscripcion (id)');
        $this->addSql('CREATE INDEX IDX_62F2085F189E045D ON publicacion (suscripcion_id)');
        $this->addSql('ALTER TABLE user ADD suscripcion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649189E045D FOREIGN KEY (suscripcion_id) REFERENCES suscripcion (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649189E045D ON user (suscripcion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085F189E045D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649189E045D');
        $this->addSql('DROP TABLE suscripcion');
        $this->addSql('DROP INDEX IDX_62F2085F189E045D ON publicacion');
        $this->addSql('ALTER TABLE publicacion DROP suscripcion_id');
        $this->addSql('DROP INDEX IDX_8D93D649189E045D ON user');
        $this->addSql('ALTER TABLE user DROP suscripcion_id');
    }
}
