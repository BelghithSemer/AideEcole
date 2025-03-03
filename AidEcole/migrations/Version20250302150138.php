<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250302150138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce_participants (annonce_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_977E83358805AB2F (annonce_id), INDEX IDX_977E8335A76ED395 (user_id), PRIMARY KEY(annonce_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce_participants ADD CONSTRAINT FK_977E83358805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_participants ADD CONSTRAINT FK_977E8335A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce_participants DROP FOREIGN KEY FK_977E83358805AB2F');
        $this->addSql('ALTER TABLE annonce_participants DROP FOREIGN KEY FK_977E8335A76ED395');
        $this->addSql('DROP TABLE annonce_participants');
    }
}
