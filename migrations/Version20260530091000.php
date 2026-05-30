<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260530091000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user_preference table for theme and language preferences';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_preference (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            theme VARCHAR(10) NOT NULL DEFAULT \'light\',
            language VARCHAR(5) NOT NULL DEFAULT \'fr\',
            created_at DATETIME IMMUTABLE NOT NULL,
            updated_at DATETIME IMMUTABLE NOT NULL,
            UNIQUE INDEX UNIQ_EC04BA6A76ED395Q (user_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_EC04BA6A76ED395Q FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_preference DROP FOREIGN KEY FK_EC04BA6A76ED395Q');
        $this->addSql('DROP TABLE user_preference');
    }
}
