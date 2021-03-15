<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314222200 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE follower_user DROP FOREIGN KEY FK_C60E1842AC24F853');
        $this->addSql('ALTER TABLE user_follower DROP FOREIGN KEY FK_595BED46AC24F853');
        $this->addSql('DROP TABLE follower');
        $this->addSql('DROP TABLE follower_user');
        $this->addSql('DROP TABLE user_follower');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE follower (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE follower_user (follower_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C60E1842A76ED395 (user_id), INDEX IDX_C60E1842AC24F853 (follower_id), PRIMARY KEY(follower_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_follower (user_id INT NOT NULL, follower_id INT NOT NULL, INDEX IDX_595BED46A76ED395 (user_id), INDEX IDX_595BED46AC24F853 (follower_id), PRIMARY KEY(user_id, follower_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE follower_user ADD CONSTRAINT FK_C60E1842A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE follower_user ADD CONSTRAINT FK_C60E1842AC24F853 FOREIGN KEY (follower_id) REFERENCES follower (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_follower ADD CONSTRAINT FK_595BED46A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_follower ADD CONSTRAINT FK_595BED46AC24F853 FOREIGN KEY (follower_id) REFERENCES follower (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
