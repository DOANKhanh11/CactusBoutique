<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260531091517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cactus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, niveau_soin VARCHAR(255) NOT NULL, arrosage VARCHAR(255) NOT NULL, taille INT NOT NULL, date_expiration DATETIME DEFAULT NULL, categorie_id INT NOT NULL, vendeur_id INT NOT NULL, INDEX IDX_7ADFB7FEBCF5E72D (categorie_id), INDEX IDX_7ADFB7FE858C065E (vendeur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, environnement VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date_cree DATETIME NOT NULL, status VARCHAR(255) NOT NULL, prix_total DOUBLE PRECISION NOT NULL, adresse VARCHAR(255) NOT NULL, acheteur_id INT DEFAULT NULL, INDEX IDX_6EEAA67D96A7BB5F (acheteur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, date_cree DATETIME NOT NULL, auteur_id INT NOT NULL, cible_id INT NOT NULL, INDEX IDX_9474526C60BB6FE6 (auteur_id), INDEX IDX_9474526CA96E5E09 (cible_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE contenu_commande (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, commande_id INT DEFAULT NULL, INDEX IDX_D39F27AC82EA2E54 (commande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pot (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, material VARCHAR(255) NOT NULL, couleur VARCHAR(30) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, score INT NOT NULL, created_at DATETIME NOT NULL, rater_id INT NOT NULL, target_id INT NOT NULL, INDEX IDX_D88926223FC1CD0A (rater_id), INDEX IDX_D8892622158E0B66 (target_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE terreau (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, composition VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, pseudo VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_preference (id INT AUTO_INCREMENT NOT NULL, theme VARCHAR(10) DEFAULT \'light\' NOT NULL, language VARCHAR(5) DEFAULT \'fr\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_FA0E76BFA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cactus ADD CONSTRAINT FK_7ADFB7FEBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE cactus ADD CONSTRAINT FK_7ADFB7FE858C065E FOREIGN KEY (vendeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D96A7BB5F FOREIGN KEY (acheteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA96E5E09 FOREIGN KEY (cible_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contenu_commande ADD CONSTRAINT FK_D39F27AC82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926223FC1CD0A FOREIGN KEY (rater_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622158E0B66 FOREIGN KEY (target_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_preference ADD CONSTRAINT FK_FA0E76BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cactus DROP FOREIGN KEY FK_7ADFB7FEBCF5E72D');
        $this->addSql('ALTER TABLE cactus DROP FOREIGN KEY FK_7ADFB7FE858C065E');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D96A7BB5F');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C60BB6FE6');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA96E5E09');
        $this->addSql('ALTER TABLE contenu_commande DROP FOREIGN KEY FK_D39F27AC82EA2E54');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926223FC1CD0A');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622158E0B66');
        $this->addSql('ALTER TABLE user_preference DROP FOREIGN KEY FK_FA0E76BFA76ED395');
        $this->addSql('DROP TABLE cactus');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE contenu_commande');
        $this->addSql('DROP TABLE pot');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE terreau');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_preference');
    }
}
