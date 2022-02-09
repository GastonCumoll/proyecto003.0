<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214130749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edicion ADD usuario_creador_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE edicion ADD CONSTRAINT FK_655F773991C4469F FOREIGN KEY (usuario_creador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE edicion ADD CONSTRAINT FK_655F7739A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_655F773991C4469F ON edicion (usuario_creador_id)');
        $this->addSql('CREATE INDEX IDX_655F7739A76ED395 ON edicion (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64952C9A8CD');
        $this->addSql('DROP INDEX IDX_8D93D64952C9A8CD ON user');
        $this->addSql('ALTER TABLE user DROP ediciones_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edicion DROP FOREIGN KEY FK_655F773991C4469F');
        $this->addSql('ALTER TABLE edicion DROP FOREIGN KEY FK_655F7739A76ED395');
        $this->addSql('DROP INDEX IDX_655F773991C4469F ON edicion');
        $this->addSql('DROP INDEX IDX_655F7739A76ED395 ON edicion');
        $this->addSql('ALTER TABLE edicion DROP usuario_creador_id, DROP user_id');
        $this->addSql('ALTER TABLE user ADD ediciones_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64952C9A8CD FOREIGN KEY (ediciones_id) REFERENCES edicion (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64952C9A8CD ON user (ediciones_id)');
    }
}
