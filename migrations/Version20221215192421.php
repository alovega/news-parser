<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215192421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX search_idx ON news');
        $this->addSql('ALTER TABLE news DROP date_added');
        $this->addSql('CREATE INDEX search_idx ON news (title, created_at, updated_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX search_idx ON news');
        $this->addSql('ALTER TABLE news ADD date_added DATETIME NOT NULL');
        $this->addSql('CREATE INDEX search_idx ON news (title, created_at, updated_at, date_added)');
    }
}
