<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310232937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande ADD manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2694D7A5783E3463 ON demande (manager_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5783E3463');
        $this->addSql('DROP INDEX IDX_2694D7A5783E3463 ON demande');
        $this->addSql('ALTER TABLE demande DROP manager_id');
    }
}
