<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407190954 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE url (id INT AUTO_INCREMENT NOT NULL, full VARCHAR(255) NOT NULL, short VARCHAR(255) NOT NULL, created_at INT NOT NULL, UNIQUE INDEX UNIQ_F47645AE8F2890A2 (short), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE url_stat (id INT AUTO_INCREMENT NOT NULL, url_id INT NOT NULL, accessed_at INT NOT NULL, INDEX IDX_3EEC369981CFDAE7 (url_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE url_stat ADD CONSTRAINT FK_3EEC369981CFDAE7 FOREIGN KEY (url_id) REFERENCES url (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE url_stat DROP FOREIGN KEY FK_3EEC369981CFDAE7');
        $this->addSql('DROP TABLE url');
        $this->addSql('DROP TABLE url_stat');
    }
}
