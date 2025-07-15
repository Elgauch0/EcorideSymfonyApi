<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250715160338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE itinerary (id INT AUTO_INCREMENT NOT NULL, vehicule_id INT NOT NULL, duration SMALLINT NOT NULL, price SMALLINT NOT NULL, datetime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_started TINYINT(1) NOT NULL, is_finished TINYINT(1) NOT NULL, is_cancelled TINYINT(1) NOT NULL, places SMALLINT NOT NULL, INDEX IDX_FF2238F64A4A3511 (vehicule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F64A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE reservation ADD itinerary_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495515F737B2 FOREIGN KEY (itinerary_id) REFERENCES itinerary (id)');
        $this->addSql('CREATE INDEX IDX_42C8495515F737B2 ON reservation (itinerary_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495515F737B2');
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F64A4A3511');
        $this->addSql('DROP TABLE itinerary');
        $this->addSql('DROP INDEX IDX_42C8495515F737B2 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP itinerary_id');
    }
}
