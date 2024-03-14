<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240311081100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_student ADD name VARCHAR(255) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, DROP name_student, DROP address_student, DROP phone_student, CHANGE email_student email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE info_student ADD name_student VARCHAR(255) DEFAULT NULL, ADD address_student VARCHAR(255) DEFAULT NULL, ADD phone_student VARCHAR(255) DEFAULT NULL, DROP name, DROP address, DROP phone, CHANGE email email_student VARCHAR(255) NOT NULL');
    }
}
