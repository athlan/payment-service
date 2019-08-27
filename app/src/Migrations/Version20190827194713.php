<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827194713 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE payments (id UUID NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, source_system VARCHAR(255) NOT NULL, payment_type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, amount BIGINT NOT NULL, currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payments.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payments.created_at IS \'(DC2Type:datetime)\'');
        $this->addSql('CREATE TABLE payments_events (event_id UUID NOT NULL, payment_id VARCHAR(36) NOT NULL, event_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, event_type VARCHAR(255) NOT NULL, event_data JSON DEFAULT NULL, PRIMARY KEY(event_id))');
        $this->addSql('COMMENT ON COLUMN payments_events.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payments_events.event_date IS \'(DC2Type:datetime)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE payments_events');
    }
}
