<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260304130809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, agree_terms BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_EMAIL ON user (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__admin AS SELECT id, email, roles, password, nom, prenom FROM admin');
        $this->addSql('DROP TABLE admin');
        $this->addSql('CREATE TABLE admin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO admin (id, email, roles, password, nom, prenom) SELECT id, email, roles, password, nom, prenom FROM __temp__admin');
        $this->addSql('DROP TABLE __temp__admin');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ADMIN_EMAIL ON admin (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__nutriscore AS SELECT id, score FROM nutriscore');
        $this->addSql('DROP TABLE nutriscore');
        $this->addSql('CREATE TABLE nutriscore (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, score VARCHAR(1) NOT NULL)');
        $this->addSql('INSERT INTO nutriscore (id, score) SELECT id, score FROM __temp__nutriscore');
        $this->addSql('DROP TABLE __temp__nutriscore');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producteur AS SELECT id, siret, roles, password, email, nom, prenom, marque, logo, adresse, is_verified, resiliation, archiver FROM producteur');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('CREATE TABLE producteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, siret VARCHAR(20) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, resiliation DATE DEFAULT NULL, archiver DATE DEFAULT NULL, agree_terms BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO producteur (id, siret, roles, password, email, nom, prenom, marque, logo, adresse, is_verified, resiliation, archiver) SELECT id, siret, roles, password, email, nom, prenom, marque, logo, adresse, is_verified, resiliation, archiver FROM __temp__producteur');
        $this->addSql('DROP TABLE __temp__producteur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PRODUCTEUR_SIRET ON producteur (siret)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__admin AS SELECT id, email, nom, prenom, roles, password FROM admin');
        $this->addSql('DROP TABLE admin');
        $this->addSql('CREATE TABLE admin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO admin (id, email, nom, prenom, roles, password) SELECT id, email, nom, prenom, roles, password FROM __temp__admin');
        $this->addSql('DROP TABLE __temp__admin');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON admin (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__nutriscore AS SELECT id, score FROM nutriscore');
        $this->addSql('DROP TABLE nutriscore');
        $this->addSql('CREATE TABLE nutriscore (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, score VARCHAR(1) DEFAULT NULL)');
        $this->addSql('INSERT INTO nutriscore (id, score) SELECT id, score FROM __temp__nutriscore');
        $this->addSql('DROP TABLE __temp__nutriscore');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producteur AS SELECT id, siret, email, nom, prenom, marque, adresse, logo, roles, is_verified, password, archiver, resiliation FROM producteur');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('CREATE TABLE producteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, siret VARCHAR(180) NOT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, roles CLOB NOT NULL, is_verified BOOLEAN NOT NULL, password VARCHAR(255) NOT NULL, archiver DATE DEFAULT NULL, resiliation DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO producteur (id, siret, email, nom, prenom, marque, adresse, logo, roles, is_verified, password, archiver, resiliation) SELECT id, siret, email, nom, prenom, marque, adresse, logo, roles, is_verified, password, archiver, resiliation FROM __temp__producteur');
        $this->addSql('DROP TABLE __temp__producteur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_SIRET ON producteur (siret)');
    }
}
