<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240128191948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', original_name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_article (id INT NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_category (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_category_item (item_category_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_782CF782F22EC5D4 (item_category_id), INDEX IDX_782CF782126F525E (item_id), PRIMARY KEY(item_category_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_media (id INT NOT NULL, file_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_408BBADC93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_url (id INT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_vote (id INT NOT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_vote_proposition (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, score INT NOT NULL, INDEX IDX_4C22F618126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE screen (id INT AUTO_INCREMENT NOT NULL, qr_code_key VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE screen_item (screen_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_1FA5D09141A67722 (screen_id), INDEX IDX_1FA5D091126F525E (item_id), PRIMARY KEY(screen_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, INDEX IDX_5F37A13BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_article ADD CONSTRAINT FK_9672832BF396750 FOREIGN KEY (id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_category ADD CONSTRAINT FK_6A41D10ABF396750 FOREIGN KEY (id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_category_item ADD CONSTRAINT FK_782CF782F22EC5D4 FOREIGN KEY (item_category_id) REFERENCES item_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_category_item ADD CONSTRAINT FK_782CF782126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_media ADD CONSTRAINT FK_408BBADC93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE item_media ADD CONSTRAINT FK_408BBADCBF396750 FOREIGN KEY (id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_url ADD CONSTRAINT FK_13633E9CBF396750 FOREIGN KEY (id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_vote ADD CONSTRAINT FK_9220C19FBF396750 FOREIGN KEY (id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_vote_proposition ADD CONSTRAINT FK_4C22F618126F525E FOREIGN KEY (item_id) REFERENCES item_vote (id)');
        $this->addSql('ALTER TABLE screen_item ADD CONSTRAINT FK_1FA5D09141A67722 FOREIGN KEY (screen_id) REFERENCES screen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE screen_item ADD CONSTRAINT FK_1FA5D091126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_article DROP FOREIGN KEY FK_9672832BF396750');
        $this->addSql('ALTER TABLE item_category DROP FOREIGN KEY FK_6A41D10ABF396750');
        $this->addSql('ALTER TABLE item_category_item DROP FOREIGN KEY FK_782CF782F22EC5D4');
        $this->addSql('ALTER TABLE item_category_item DROP FOREIGN KEY FK_782CF782126F525E');
        $this->addSql('ALTER TABLE item_media DROP FOREIGN KEY FK_408BBADC93CB796C');
        $this->addSql('ALTER TABLE item_media DROP FOREIGN KEY FK_408BBADCBF396750');
        $this->addSql('ALTER TABLE item_url DROP FOREIGN KEY FK_13633E9CBF396750');
        $this->addSql('ALTER TABLE item_vote DROP FOREIGN KEY FK_9220C19FBF396750');
        $this->addSql('ALTER TABLE item_vote_proposition DROP FOREIGN KEY FK_4C22F618126F525E');
        $this->addSql('ALTER TABLE screen_item DROP FOREIGN KEY FK_1FA5D09141A67722');
        $this->addSql('ALTER TABLE screen_item DROP FOREIGN KEY FK_1FA5D091126F525E');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_article');
        $this->addSql('DROP TABLE item_category');
        $this->addSql('DROP TABLE item_category_item');
        $this->addSql('DROP TABLE item_media');
        $this->addSql('DROP TABLE item_url');
        $this->addSql('DROP TABLE item_vote');
        $this->addSql('DROP TABLE item_vote_proposition');
        $this->addSql('DROP TABLE screen');
        $this->addSql('DROP TABLE screen_item');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE user');
    }
}
