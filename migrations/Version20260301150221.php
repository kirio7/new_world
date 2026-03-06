<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260301150221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS alergene_produit (alergene_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, PRIMARY KEY (alergene_id, produit_id), CONSTRAINT FK_32D30E296C557051 FOREIGN KEY (alergene_id) REFERENCES alergene (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_32D30E29F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_32D30E296C557051 ON alergene_produit (alergene_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_32D30E29F347EFB ON alergene_produit (produit_id)');
        $this->addSql('CREATE TABLE IF NOT EXISTS categorie_produit (categorie_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, PRIMARY KEY (categorie_id, produit_id), CONSTRAINT FK_76264285BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_76264285F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_76264285BCF5E72D ON categorie_produit (categorie_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_76264285F347EFB ON categorie_produit (produit_id)');
        $this->addSql('DROP TABLE IF EXISTS produit_alergene');
        $this->addSql('DROP TABLE IF EXISTS produit_categorie');
        $this->addSql('DROP TABLE IF EXISTS user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__admin AS SELECT id, email, roles, password FROM admin');
        $this->addSql('DROP TABLE admin');
        $this->addSql('CREATE TABLE admin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO admin (id, email, roles, password) SELECT id, email, roles, password FROM __temp__admin');
        $this->addSql('DROP TABLE __temp__admin');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON admin (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__nutriscore AS SELECT id FROM nutriscore');
        $this->addSql('DROP TABLE nutriscore');
        $this->addSql('CREATE TABLE nutriscore (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, score VARCHAR(1) DEFAULT NULL)');
        $this->addSql('INSERT INTO nutriscore (id) SELECT id FROM __temp__nutriscore');
        $this->addSql('DROP TABLE __temp__nutriscore');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producteur AS SELECT id, siret, roles, password, email, nom, prenom, marque, logo, adresse, is_verified, resiliation, archiver FROM producteur');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('CREATE TABLE producteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, siret VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, resiliation DATE DEFAULT NULL, archiver DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO producteur (id, siret, roles, password, email, nom, prenom, marque, logo, adresse, is_verified, resiliation, archiver) SELECT id, siret, roles, password, email, nom, prenom, marque, logo, adresse, is_verified, resiliation, archiver FROM __temp__producteur');
        $this->addSql('DROP TABLE __temp__producteur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_SIRET ON producteur (siret)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__produit AS SELECT id, nom, quantite, prix, pourcentage, description, est_bio, image FROM produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, quantite VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, pourcentage DOUBLE PRECISION NOT NULL, description CLOB NOT NULL, est_bio BOOLEAN NOT NULL, image VARCHAR(255) DEFAULT NULL, ingredients CLOB DEFAULT NULL, producteur_id INTEGER NOT NULL, nutriscore_id INTEGER DEFAULT NULL, CONSTRAINT FK_29A5EC27AB9BB300 FOREIGN KEY (producteur_id) REFERENCES producteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_29A5EC277CC6F09B FOREIGN KEY (nutriscore_id) REFERENCES nutriscore (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO produit (id, nom, quantite, prix, pourcentage, description, est_bio, image) SELECT id, nom, quantite, prix, pourcentage, description, est_bio, image FROM __temp__produit');
        $this->addSql('DROP TABLE __temp__produit');
        $this->addSql('CREATE INDEX IDX_29A5EC27AB9BB300 ON produit (producteur_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC277CC6F09B ON produit (nutriscore_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_alergene (produit_id INTEGER NOT NULL, alergene_id INTEGER NOT NULL, PRIMARY KEY (produit_id, alergene_id), CONSTRAINT FK_E90A5307F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E90A53076C557051 FOREIGN KEY (alergene_id) REFERENCES alergene (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E90A53076C557051 ON produit_alergene (alergene_id)');
        $this->addSql('CREATE INDEX IDX_E90A5307F347EFB ON produit_alergene (produit_id)');
        $this->addSql('CREATE TABLE produit_categorie (produit_id INTEGER NOT NULL, categorie_id INTEGER NOT NULL, PRIMARY KEY (produit_id, categorie_id), CONSTRAINT FK_CDEA88D8F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CDEA88D8BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CDEA88D8BCF5E72D ON produit_categorie (categorie_id)');
        $this->addSql('CREATE INDEX IDX_CDEA88D8F347EFB ON produit_categorie (produit_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL COLLATE "BINARY", siret VARCHAR(14) DEFAULT NULL COLLATE "BINARY", nom VARCHAR(255) DEFAULT NULL COLLATE "BINARY", prenom VARCHAR(255) DEFAULT NULL COLLATE "BINARY", marque VARCHAR(255) DEFAULT NULL COLLATE "BINARY", logo VARCHAR(255) DEFAULT NULL COLLATE "BINARY", adresse VARCHAR(255) DEFAULT NULL COLLATE "BINARY", roles CLOB NOT NULL COLLATE "BINARY", password VARCHAR(255) NOT NULL COLLATE "BINARY")');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_EMAIL ON user (email)');
        $this->addSql('DROP TABLE alergene_produit');
        $this->addSql('DROP TABLE categorie_produit');
        $this->addSql('CREATE TEMPORARY TABLE __temp__admin AS SELECT id, email, roles, password FROM admin');
        $this->addSql('DROP TABLE admin');
        $this->addSql('CREATE TABLE admin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO admin (id, email, roles, password) SELECT id, email, roles, password FROM __temp__admin');
        $this->addSql('DROP TABLE __temp__admin');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ADMIN_EMAIL ON admin (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__nutriscore AS SELECT id FROM nutriscore');
        $this->addSql('DROP TABLE nutriscore');
        $this->addSql('CREATE TABLE nutriscore (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, produits_id INTEGER DEFAULT NULL, CONSTRAINT FK_1E3D1A99CD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO nutriscore (id) SELECT id FROM __temp__nutriscore');
        $this->addSql('DROP TABLE __temp__nutriscore');
        $this->addSql('CREATE INDEX IDX_1E3D1A99CD11A2CF ON nutriscore (produits_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producteur AS SELECT id, siret, roles, password, email, nom, prenom, marque, adresse, logo, is_verified, resiliation, archiver FROM producteur');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('CREATE TABLE producteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, siret VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN NOT NULL, resiliation DATE DEFAULT NULL, archiver DATE DEFAULT NULL, produits_id INTEGER DEFAULT NULL, CONSTRAINT FK_7EDBEE10CD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO producteur (id, siret, roles, password, email, nom, prenom, marque, adresse, logo, is_verified, resiliation, archiver) SELECT id, siret, roles, password, email, nom, prenom, marque, adresse, logo, is_verified, resiliation, archiver FROM __temp__producteur');
        $this->addSql('DROP TABLE __temp__producteur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_SIRET ON producteur (siret)');
        $this->addSql('CREATE INDEX IDX_7EDBEE10CD11A2CF ON producteur (produits_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__produit AS SELECT id, nom, quantite, prix, pourcentage, description, est_bio, image FROM produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, quantite INTEGER NOT NULL, prix DOUBLE PRECISION NOT NULL, pourcentage DOUBLE PRECISION NOT NULL, description CLOB NOT NULL, est_bio BOOLEAN NOT NULL, image VARCHAR(255) DEFAULT NULL, ingredient CLOB NOT NULL)');
        $this->addSql('INSERT INTO produit (id, nom, quantite, prix, pourcentage, description, est_bio, image) SELECT id, nom, quantite, prix, pourcentage, description, est_bio, image FROM __temp__produit');
        $this->addSql('DROP TABLE __temp__produit');
    }
}
