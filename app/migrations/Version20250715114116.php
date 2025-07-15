<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250715114116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, client_id_id INT NOT NULL, date_reservation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(50) NOT NULL, seats_reserved SMALLINT NOT NULL, is_cancelled TINYINT(1) NOT NULL, INDEX IDX_42C84955DC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, driver_id INT NOT NULL, licence_plate VARCHAR(50) NOT NULL, registration_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', seats_available SMALLINT NOT NULL, is_smocking_alowed TINYINT(1) NOT NULL, is_pets_alowed TINYINT(1) NOT NULL, model VARCHAR(50) NOT NULL, is_green TINYINT(1) NOT NULL, INDEX IDX_1B80E486C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955DC2902E0 FOREIGN KEY (client_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C3423909 FOREIGN KEY (driver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(50) NOT NULL, ADD last_name VARCHAR(50) NOT NULL, ADD adress VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955DC2902E0');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C3423909');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('ALTER TABLE user DROP first_name, DROP last_name, DROP adress');
    }
}
