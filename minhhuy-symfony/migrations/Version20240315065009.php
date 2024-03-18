<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315065009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student ADD address VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE student DROP address');

        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classes_student DROP FOREIGN KEY FK_9D60F7579E225B24');
        $this->addSql('ALTER TABLE classes_student DROP FOREIGN KEY FK_9D60F757CB944F1A');
        $this->addSql('DROP TABLE classes');
        $this->addSql('DROP TABLE classes_student');
        $this->addSql('DROP TABLE student');
    }
}
