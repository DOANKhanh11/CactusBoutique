<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260531171233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_favorite_cactus DROP FOREIGN KEY `FK_USER_FAVORITE_CACTUS`');
        $this->addSql('ALTER TABLE user_favorite_cactus DROP FOREIGN KEY `FK_USER_FAVORITE_USER`');
        $this->addSql('DROP TABLE user_favorite_cactus');
        $this->addSql('ALTER TABLE cactus DROP FOREIGN KEY `FK_7ADFB7FE858C065E`');
        $this->addSql('DROP INDEX IDX_7ADFB7FE858C065E ON cactus');
        $this->addSql('ALTER TABLE cactus DROP date_expiration, DROP vendeur_id');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY `FK_9474526CA96E5E09`');
        $this->addSql('DROP INDEX IDX_9474526CA96E5E09 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE contenu contenu VARCHAR(1000) NOT NULL, CHANGE cible_id vendeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C858C065E FOREIGN KEY (vendeur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526C858C065E ON comment (vendeur_id)');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY `FK_D8892622158E0B66`');
        $this->addSql('DROP INDEX IDX_D8892622158E0B66 ON rating');
        $this->addSql('ALTER TABLE rating CHANGE target_id vendeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622858C065E FOREIGN KEY (vendeur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D8892622858C065E ON rating (vendeur_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_rating ON rating (rater_id, vendeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_favorite_cactus (user_id INT NOT NULL, cactus_id INT NOT NULL, INDEX IDX_USER_FAVORITE_USER (user_id), INDEX IDX_USER_FAVORITE_CACTUS (cactus_id), PRIMARY KEY (user_id, cactus_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_favorite_cactus ADD CONSTRAINT `FK_USER_FAVORITE_CACTUS` FOREIGN KEY (cactus_id) REFERENCES cactus (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_favorite_cactus ADD CONSTRAINT `FK_USER_FAVORITE_USER` FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cactus ADD date_expiration DATETIME DEFAULT NULL, ADD vendeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE cactus ADD CONSTRAINT `FK_7ADFB7FE858C065E` FOREIGN KEY (vendeur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7ADFB7FE858C065E ON cactus (vendeur_id)');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C858C065E');
        $this->addSql('DROP INDEX IDX_9474526C858C065E ON comment');
        $this->addSql('ALTER TABLE comment CHANGE contenu contenu VARCHAR(255) NOT NULL, CHANGE vendeur_id cible_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT `FK_9474526CA96E5E09` FOREIGN KEY (cible_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9474526CA96E5E09 ON comment (cible_id)');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622858C065E');
        $this->addSql('DROP INDEX IDX_D8892622858C065E ON rating');
        $this->addSql('DROP INDEX unique_rating ON rating');
        $this->addSql('ALTER TABLE rating CHANGE vendeur_id target_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT `FK_D8892622158E0B66` FOREIGN KEY (target_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D8892622158E0B66 ON rating (target_id)');
    }
}
