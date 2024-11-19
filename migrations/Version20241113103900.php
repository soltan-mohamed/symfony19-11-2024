<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113103900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, foyer_id INT NOT NULL, numero VARCHAR(50) NOT NULL, capacite INT NOT NULL, INDEX IDX_C509E4FF2B919A58 (foyer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE foyer (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resident (id INT NOT NULL, foyer_id INT NOT NULL, date_entree DATE NOT NULL, date_sortie DATE DEFAULT NULL, INDEX IDX_1D03DA062B919A58 (foyer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF2B919A58 FOREIGN KEY (foyer_id) REFERENCES foyer (id)');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA062B919A58 FOREIGN KEY (foyer_id) REFERENCES foyer (id)');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA06BF396750 FOREIGN KEY (id) REFERENCES etudiant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FF2B919A58');
        $this->addSql('ALTER TABLE resident DROP FOREIGN KEY FK_1D03DA062B919A58');
        $this->addSql('ALTER TABLE resident DROP FOREIGN KEY FK_1D03DA06BF396750');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE foyer');
        $this->addSql('DROP TABLE resident');
    }
}
