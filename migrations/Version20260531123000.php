<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260531123000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user favorites relation for cactus listings';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_favorite_cactus (user_id INT NOT NULL, cactus_id INT NOT NULL, INDEX IDX_USER_FAVORITE_USER (user_id), INDEX IDX_USER_FAVORITE_CACTUS (cactus_id), PRIMARY KEY(user_id, cactus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_favorite_cactus ADD CONSTRAINT FK_USER_FAVORITE_USER FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_favorite_cactus ADD CONSTRAINT FK_USER_FAVORITE_CACTUS FOREIGN KEY (cactus_id) REFERENCES cactus (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_favorite_cactus DROP FOREIGN KEY FK_USER_FAVORITE_USER');
        $this->addSql('ALTER TABLE user_favorite_cactus DROP FOREIGN KEY FK_USER_FAVORITE_CACTUS');
        $this->addSql('DROP TABLE user_favorite_cactus');
    }
}
