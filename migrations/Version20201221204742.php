<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201221204742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1DE18E50B');
        $this->addSql('DROP INDEX IDX_9CE58EE1DE18E50B ON order_line');
        $this->addSql('ALTER TABLE order_line CHANGE product_id_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE14584665A ON order_line (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE14584665A');
        $this->addSql('DROP INDEX IDX_9CE58EE14584665A ON order_line');
        $this->addSql('ALTER TABLE order_line CHANGE product_id product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE1DE18E50B ON order_line (product_id_id)');
    }
}
