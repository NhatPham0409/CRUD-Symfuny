<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318030628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classes_student (classes_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_9D60F7579E225B24 (classes_id), INDEX IDX_9D60F757CB944F1A (student_id), PRIMARY KEY(classes_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classes_student ADD CONSTRAINT FK_9D60F7579E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classes_student ADD CONSTRAINT FK_9D60F757CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student CHANGE do_b dob DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classes_student DROP FOREIGN KEY FK_9D60F7579E225B24');
        $this->addSql('ALTER TABLE classes_student DROP FOREIGN KEY FK_9D60F757CB944F1A');
        $this->addSql('DROP TABLE classes_student');
        $this->addSql('ALTER TABLE student CHANGE dob do_b DATE DEFAULT NULL');
    }
}
