<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402083948 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD status_like TINYINT(1) NOT NULL, ADD rating INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum ADD creat_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE post ADD status_like TINYINT(1) DEFAULT NULL, ADD req_user VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE role role ENUM(\'admin\', \'client\', \'prestataire\', \'entreprise\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP status_like, DROP rating');
        $this->addSql('ALTER TABLE forum DROP creat_at');
        $this->addSql('ALTER TABLE post DROP status_like, DROP req_user');
        $this->addSql('ALTER TABLE user CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
