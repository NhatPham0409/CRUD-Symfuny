<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318145456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE movie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE class_room_student (class_room_id INT NOT NULL, student_id INT NOT NULL, PRIMARY KEY(class_room_id, student_id))');
        $this->addSql('CREATE INDEX IDX_9483E8859162176F ON class_room_student (class_room_id)');
        $this->addSql('CREATE INDEX IDX_9483E885CB944F1A ON class_room_student (student_id)');
        $this->addSql('CREATE TABLE movie (id INT NOT NULL, title VARCHAR(255) NOT NULL, plot TEXT DEFAULT NULL, release_year SMALLINT NOT NULL, rating_star DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE class_room_student ADD CONSTRAINT FK_9483E8859162176F FOREIGN KEY (class_room_id) REFERENCES class_room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE class_room_student ADD CONSTRAINT FK_9483E885CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE class_room ADD class_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE class_room DROP room_name');
        $this->addSql('ALTER TABLE class_room ALTER teacher_name DROP NOT NULL');
        $this->addSql('ALTER TABLE class_room ALTER teacher_name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE student DROP CONSTRAINT fk_b723af339162176f');
        $this->addSql('DROP INDEX idx_b723af339162176f');
        $this->addSql('ALTER TABLE student DROP class_room_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE movie_id_seq CASCADE');
        $this->addSql('ALTER TABLE class_room_student DROP CONSTRAINT FK_9483E8859162176F');
        $this->addSql('ALTER TABLE class_room_student DROP CONSTRAINT FK_9483E885CB944F1A');
        $this->addSql('DROP TABLE class_room_student');
        $this->addSql('DROP TABLE movie');
        $this->addSql('ALTER TABLE class_room ADD room_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE class_room DROP class_name');
        $this->addSql('ALTER TABLE class_room ALTER teacher_name SET NOT NULL');
        $this->addSql('ALTER TABLE class_room ALTER teacher_name TYPE VARCHAR(200)');
        $this->addSql('ALTER TABLE student ADD class_room_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT fk_b723af339162176f FOREIGN KEY (class_room_id) REFERENCES class_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b723af339162176f ON student (class_room_id)');
    }
}
