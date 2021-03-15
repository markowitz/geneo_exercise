<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314232416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE3AC24F853');
        $this->addSql('DROP INDEX IDX_71BF8DE3AC24F853 ON following');
        $this->addSql('ALTER TABLE following DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE following CHANGE follower_id following_id INT NOT NULL');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE31816E3A3 FOREIGN KEY (following_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_71BF8DE31816E3A3 ON following (following_id)');
        $this->addSql('ALTER TABLE following ADD PRIMARY KEY (user_id, following_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE31816E3A3');
        $this->addSql('DROP INDEX IDX_71BF8DE31816E3A3 ON following');
        $this->addSql('ALTER TABLE following DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE following CHANGE following_id follower_id INT NOT NULL');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_71BF8DE3AC24F853 ON following (follower_id)');
        $this->addSql('ALTER TABLE following ADD PRIMARY KEY (user_id, follower_id)');
    }
}
