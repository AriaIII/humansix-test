<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201221204441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1FCDAEAAA');
        $this->addSql('DROP INDEX IDX_9CE58EE1FCDAEAAA ON order_line');
        $this->addSql('ALTER TABLE order_line CHANGE order_id_id order_concerned_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1E4A1821D FOREIGN KEY (order_concerned_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE1E4A1821D ON order_line (order_concerned_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1E4A1821D');
        $this->addSql('DROP INDEX IDX_9CE58EE1E4A1821D ON order_line');
        $this->addSql('ALTER TABLE order_line CHANGE order_concerned_id order_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE1FCDAEAAA ON order_line (order_id_id)');
    }
}
