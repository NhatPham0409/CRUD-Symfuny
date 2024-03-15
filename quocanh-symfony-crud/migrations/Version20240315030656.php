<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315030656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE class_room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE student_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE class_room (id INT NOT NULL, class_name VARCHAR(255) NOT NULL, teacher_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE class_room_student (class_room_id INT NOT NULL, student_id INT NOT NULL, PRIMARY KEY(class_room_id, student_id))');
        $this->addSql('CREATE INDEX IDX_9483E8859162176F ON class_room_student (class_room_id)');
        $this->addSql('CREATE INDEX IDX_9483E885CB944F1A ON class_room_student (student_id)');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, gender VARCHAR(50) NOT NULL, dob TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, phone VARCHAR(15) NOT NULL, email VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE class_room_student ADD CONSTRAINT FK_9483E8859162176F FOREIGN KEY (class_room_id) REFERENCES class_room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE class_room_student ADD CONSTRAINT FK_9483E885CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE class_room_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE student_id_seq CASCADE');
        $this->addSql('ALTER TABLE class_room_student DROP CONSTRAINT FK_9483E8859162176F');
        $this->addSql('ALTER TABLE class_room_student DROP CONSTRAINT FK_9483E885CB944F1A');
        $this->addSql('DROP TABLE class_room');
        $this->addSql('DROP TABLE class_room_student');
        $this->addSql('DROP TABLE student');
    }
}
