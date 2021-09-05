<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210904192355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD filter_climat_id INT DEFAULT NULL, ADD filter_activities_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A103B070 FOREIGN KEY (filter_climat_id) REFERENCES filter (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6644D71E65 FOREIGN KEY (filter_activities_id) REFERENCES filter (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66A103B070 ON article (filter_climat_id)');
        $this->addSql('CREATE INDEX IDX_23A0E6644D71E65 ON article (filter_activities_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A103B070');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6644D71E65');
        $this->addSql('DROP INDEX IDX_23A0E66A103B070 ON article');
        $this->addSql('DROP INDEX IDX_23A0E6644D71E65 ON article');
        $this->addSql('ALTER TABLE article DROP filter_climat_id, DROP filter_activities_id');
    }
}
