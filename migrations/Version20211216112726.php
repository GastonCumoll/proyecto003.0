<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216112726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085F189E045D');
        $this->addSql('DROP INDEX IDX_62F2085F189E045D ON publicacion');
        $this->addSql('ALTER TABLE publicacion DROP suscripcion_id');
        $this->addSql('ALTER TABLE suscripcion ADD usuario_id INT DEFAULT NULL, ADD publicacion_id INT NOT NULL');
        $this->addSql('ALTER TABLE suscripcion ADD CONSTRAINT FK_497FA0DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suscripcion ADD CONSTRAINT FK_497FA09ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id)');
        $this->addSql('CREATE INDEX IDX_497FA0DB38439E ON suscripcion (usuario_id)');
        $this->addSql('CREATE INDEX IDX_497FA09ACBB5E7 ON suscripcion (publicacion_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649189E045D');
        $this->addSql('DROP INDEX IDX_8D93D649189E045D ON user');
        $this->addSql('ALTER TABLE user DROP suscripcion_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion ADD suscripcion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085F189E045D FOREIGN KEY (suscripcion_id) REFERENCES suscripcion (id)');
        $this->addSql('CREATE INDEX IDX_62F2085F189E045D ON publicacion (suscripcion_id)');
        $this->addSql('ALTER TABLE suscripcion DROP FOREIGN KEY FK_497FA0DB38439E');
        $this->addSql('ALTER TABLE suscripcion DROP FOREIGN KEY FK_497FA09ACBB5E7');
        $this->addSql('DROP INDEX IDX_497FA0DB38439E ON suscripcion');
        $this->addSql('DROP INDEX IDX_497FA09ACBB5E7 ON suscripcion');
        $this->addSql('ALTER TABLE suscripcion DROP usuario_id, DROP publicacion_id');
        $this->addSql('ALTER TABLE user ADD suscripcion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649189E045D FOREIGN KEY (suscripcion_id) REFERENCES suscripcion (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649189E045D ON user (suscripcion_id)');
    }
}
