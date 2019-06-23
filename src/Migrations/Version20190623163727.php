<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190623163727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitation_code ADD CONSTRAINT FK_BA14FCCC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitation_code ADD CONSTRAINT FK_BA14FCCC7A512022 FOREIGN KEY (invitee_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BA14FCCC7E3C61F9 ON invitation_code (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA14FCCC7A512022 ON invitation_code (invitee_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invitation_code DROP FOREIGN KEY FK_BA14FCCC7E3C61F9');
        $this->addSql('ALTER TABLE invitation_code DROP FOREIGN KEY FK_BA14FCCC7A512022');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_BA14FCCC7E3C61F9 ON invitation_code');
        $this->addSql('DROP INDEX UNIQ_BA14FCCC7A512022 ON invitation_code');
    }
}
