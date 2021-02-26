<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210226201132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(25) NOT NULL, prenom VARCHAR(25) NOT NULL, email VARCHAR(180) NOT NULL, role ENUM(\'admin\', \'client\', \'prestataire\', \'entreprise\'), password VARCHAR(255) NOT NULL, phone INT NOT NULL, photo VARCHAR(255) NOT NULL, bio VARCHAR(300) NOT NULL, nom_entreprise VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, secteur VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, specialisation VARCHAR(255) NOT NULL, site_web VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, taille VARCHAR(255) NOT NULL, montant_horaire INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
    }
}
