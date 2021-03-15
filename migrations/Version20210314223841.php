<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314223841 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE31896F387');
        $this->addSql('DROP INDEX IDX_71BF8DE31896F387 ON following');
        $this->addSql('ALTER TABLE following DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE following CHANGE following_user_id follower_id INT NOT NULL');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_71BF8DE3AC24F853 ON following (follower_id)');
        $this->addSql('ALTER TABLE following ADD PRIMARY KEY (user_id, follower_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE3AC24F853');
        $this->addSql('DROP INDEX IDX_71BF8DE3AC24F853 ON following');
        $this->addSql('ALTER TABLE following DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE following CHANGE follower_id following_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE31896F387 FOREIGN KEY (following_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_71BF8DE31896F387 ON following (following_user_id)');
        $this->addSql('ALTER TABLE following ADD PRIMARY KEY (user_id, following_user_id)');
    }
}
