<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260301145412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__admin AS SELECT id, email, roles, password, nom, prenom FROM admin');
        $this->addSql('DROP TABLE admin');
        $this->addSql('CREATE TABLE admin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO admin (id, email, roles, password, nom, prenom) SELECT id, email, roles, password, nom, prenom FROM __temp__admin');
        $this->addSql('DROP TABLE __temp__admin');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ADMIN_EMAIL ON admin (email)');
        $this->addSql('ALTER TABLE producteur ADD COLUMN is_verified BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__admin AS SELECT id, email, nom, prenom, roles, password FROM admin');
        $this->addSql('DROP TABLE admin');
        $this->addSql('CREATE TABLE admin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) DEFAULT \'""\' NOT NULL, prenom VARCHAR(255) DEFAULT \'""\' NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, agree_terms BOOLEAN DEFAULT 0 NOT NULL)');
        $this->addSql('INSERT INTO admin (id, email, nom, prenom, roles, password) SELECT id, email, nom, prenom, roles, password FROM __temp__admin');
        $this->addSql('DROP TABLE __temp__admin');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ADMIN_EMAIL ON admin (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__producteur AS SELECT id, siret, email, nom, prenom, marque, adresse, logo, roles, password, agree_terms FROM producteur');
        $this->addSql('DROP TABLE producteur');
        $this->addSql('CREATE TABLE producteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, siret VARCHAR(20) NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, agree_terms BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO producteur (id, siret, email, nom, prenom, marque, adresse, logo, roles, password, agree_terms) SELECT id, siret, email, nom, prenom, marque, adresse, logo, roles, password, agree_terms FROM __temp__producteur');
        $this->addSql('DROP TABLE __temp__producteur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PRODUCTEUR_SIRET ON producteur (siret)');
    }
}
