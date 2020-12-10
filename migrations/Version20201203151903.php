<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203151903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_testimony (user_id INT NOT NULL, testimony_id INT NOT NULL, INDEX IDX_52DC1963A76ED395 (user_id), INDEX IDX_52DC1963B879FBFE (testimony_id), PRIMARY KEY(user_id, testimony_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_testimony ADD CONSTRAINT FK_52DC1963A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_testimony ADD CONSTRAINT FK_52DC1963B879FBFE FOREIGN KEY (testimony_id) REFERENCES testimony (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE testimony DROP testinomy');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_testimony');
        $this->addSql('ALTER TABLE testimony ADD testinomy VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
