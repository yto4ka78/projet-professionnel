<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240812133720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, club_id INT NOT NULL, approved TINYINT(1) NOT NULL, INDEX IDX_36DF2A1DA76ED395 (user_id), INDEX IDX_36DF2A1D61190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE club_request ADD CONSTRAINT FK_36DF2A1DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE club_request ADD CONSTRAINT FK_36DF2A1D61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club_request DROP FOREIGN KEY FK_36DF2A1DA76ED395');
        $this->addSql('ALTER TABLE club_request DROP FOREIGN KEY FK_36DF2A1D61190A32');
        $this->addSql('DROP TABLE club_request');
    }
}
