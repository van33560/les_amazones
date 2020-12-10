<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203153559 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone_book ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE phone_book ADD CONSTRAINT FK_28E1F123A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_28E1F123A76ED395 ON phone_book (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone_book DROP FOREIGN KEY FK_28E1F123A76ED395');
        $this->addSql('DROP INDEX IDX_28E1F123A76ED395 ON phone_book');
        $this->addSql('ALTER TABLE phone_book DROP user_id');
    }
}
