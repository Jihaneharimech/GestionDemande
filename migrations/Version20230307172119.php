<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307172119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande ADD type_appareil_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5F473D7A3 FOREIGN KEY (type_appareil_id) REFERENCES appareil (id)');
        $this->addSql('CREATE INDEX IDX_2694D7A5F473D7A3 ON demande (type_appareil_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5F473D7A3');
        $this->addSql('DROP INDEX IDX_2694D7A5F473D7A3 ON demande');
        $this->addSql('ALTER TABLE demande DROP type_appareil_id');
    }
}
