<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260301141105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // if the admin table already contains rows, adding NOT NULL columns would fail without a default
        $this->addSql('ALTER TABLE admin ADD COLUMN nom VARCHAR(255) NOT NULL DEFAULT ""');
        $this->addSql('ALTER TABLE admin ADD COLUMN prenom VARCHAR(255) NOT NULL DEFAULT ""');
        $this->addSql('ALTER TABLE admin ADD COLUMN agree_terms BOOLEAN NOT NULL DEFAULT 0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producteur AS SELECT id, siret, roles, password, email, nom, prenom, marque, logo, adresse, is_verified FROM producteur');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('CREATE TABLE producteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, siret VARCHAR(20) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, agree_terms BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO producteur (id, siret, roles, password, email, nom, prenom, marque, logo, adresse, agree_terms) SELECT id, siret, roles, password, email, nom, prenom, marque, logo, adresse, is_verified FROM __temp__producteur');
        $this->addSql('DROP TABLE __temp__producteur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PRODUCTEUR_SIRET ON producteur (siret)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, nom, prenom, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, agree_terms BOOLEAN NOT NULL)');
        // insert existing users with a safe default for the new agree_terms column
        $this->addSql('INSERT INTO user (id, email, nom, prenom, roles, password, agree_terms) SELECT id, email, nom, prenom, roles, password, 0 FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__admin AS SELECT id, email, roles, password FROM admin');
        $this->addSql('DROP TABLE admin');
        $this->addSql('CREATE TABLE admin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO admin (id, email, roles, password) SELECT id, email, roles, password FROM __temp__admin');
        $this->addSql('DROP TABLE __temp__admin');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ADMIN_EMAIL ON admin (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producteur AS SELECT id, siret, email, nom, prenom, marque, logo, adresse, roles, password, agree_terms FROM producteur');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('CREATE TABLE producteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, siret VARCHAR(180) NOT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, resiliation DATE DEFAULT NULL, archiver DATE DEFAULT NULL, produits_id INTEGER DEFAULT NULL, CONSTRAINT FK_7EDBEE10CD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO producteur (id, siret, email, nom, prenom, marque, logo, adresse, roles, password, is_verified) SELECT id, siret, email, nom, prenom, marque, logo, adresse, roles, password, agree_terms FROM __temp__producteur');
        $this->addSql('DROP TABLE __temp__producteur');
        $this->addSql('CREATE INDEX IDX_7EDBEE10CD11A2CF ON producteur (produits_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_SIRET ON producteur (siret)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, nom, prenom, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, siret VARCHAR(14) DEFAULT NULL, marque VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, email, nom, prenom, roles, password) SELECT id, email, nom, prenom, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_EMAIL ON user (email)');
    }
}
