<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214115452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edicion ADD publicacion_id INT NOT NULL');
        $this->addSql('ALTER TABLE edicion ADD CONSTRAINT FK_655F77399ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id)');
        $this->addSql('CREATE INDEX IDX_655F77399ACBB5E7 ON edicion (publicacion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edicion DROP FOREIGN KEY FK_655F77399ACBB5E7');
        $this->addSql('DROP INDEX IDX_655F77399ACBB5E7 ON edicion');
        $this->addSql('ALTER TABLE edicion DROP publicacion_id');
    }
}
