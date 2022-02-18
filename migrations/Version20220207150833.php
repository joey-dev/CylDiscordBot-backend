<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220207150833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE channel (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, name VARCHAR(255) NOT NULL, channel_id VARCHAR(255) NOT NULL, INDEX IDX_A2F98E471844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE component (id INT AUTO_INCREMENT NOT NULL, plugin_id INT DEFAULT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, data LONGTEXT NOT NULL, order_id INT NOT NULL, INDEX IDX_49FEA157EC942BCF (plugin_id), INDEX IDX_49FEA157C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE component_settings (id INT AUTO_INCREMENT NOT NULL, component_id INT NOT NULL, server_id INT NOT NULL, turned_on SMALLINT NOT NULL, data LONGTEXT NOT NULL, INDEX IDX_A7473428E2ABAFFF (component_id), INDEX IDX_A74734281844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE component_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emote (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, name VARCHAR(255) NOT NULL, emote_id VARCHAR(255) NOT NULL, INDEX IDX_6BBC85C61844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, small_name VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, order_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plugin (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, order_id INT NOT NULL, INDEX IDX_E96E2794AFC2B591 (module_id), INDEX IDX_E96E2794C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plugin_settings (id INT AUTO_INCREMENT NOT NULL, plugin_id INT NOT NULL, server_id INT NOT NULL, turned_on SMALLINT NOT NULL, INDEX IDX_458DE02AEC942BCF (plugin_id), INDEX IDX_458DE02A1844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plugin_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, name VARCHAR(255) NOT NULL, role_id VARCHAR(255) NOT NULL, INDEX IDX_57698A6A1844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, server_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, command_prefix VARCHAR(10) NOT NULL, INDEX IDX_5A6DD5F682F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, task LONGTEXT NOT NULL, complete_on VARCHAR(255) NOT NULL, INDEX IDX_527EDB251844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, user_id VARCHAR(25) NOT NULL, token_expires_in VARCHAR(255) NOT NULL, refresh_token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_server (user_id INT NOT NULL, server_id INT NOT NULL, INDEX IDX_3F3FCECBA76ED395 (user_id), INDEX IDX_3F3FCECB1844E6B7 (server_id), PRIMARY KEY(user_id, server_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E471844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157EC942BCF FOREIGN KEY (plugin_id) REFERENCES plugin (id)');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157C54C8C93 FOREIGN KEY (type_id) REFERENCES component_type (id)');
        $this->addSql('ALTER TABLE component_settings ADD CONSTRAINT FK_A7473428E2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id)');
        $this->addSql('ALTER TABLE component_settings ADD CONSTRAINT FK_A74734281844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE emote ADD CONSTRAINT FK_6BBC85C61844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE plugin ADD CONSTRAINT FK_E96E2794AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE plugin ADD CONSTRAINT FK_E96E2794C54C8C93 FOREIGN KEY (type_id) REFERENCES plugin_type (id)');
        $this->addSql('ALTER TABLE plugin_settings ADD CONSTRAINT FK_458DE02AEC942BCF FOREIGN KEY (plugin_id) REFERENCES plugin (id)');
        $this->addSql('ALTER TABLE plugin_settings ADD CONSTRAINT FK_458DE02A1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6A1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F682F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB251844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE user_server ADD CONSTRAINT FK_3F3FCECBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_server ADD CONSTRAINT FK_3F3FCECB1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE component_settings DROP FOREIGN KEY FK_A7473428E2ABAFFF');
        $this->addSql('ALTER TABLE component DROP FOREIGN KEY FK_49FEA157C54C8C93');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F682F1BAF4');
        $this->addSql('ALTER TABLE plugin DROP FOREIGN KEY FK_E96E2794AFC2B591');
        $this->addSql('ALTER TABLE component DROP FOREIGN KEY FK_49FEA157EC942BCF');
        $this->addSql('ALTER TABLE plugin_settings DROP FOREIGN KEY FK_458DE02AEC942BCF');
        $this->addSql('ALTER TABLE plugin DROP FOREIGN KEY FK_E96E2794C54C8C93');
        $this->addSql('ALTER TABLE channel DROP FOREIGN KEY FK_A2F98E471844E6B7');
        $this->addSql('ALTER TABLE component_settings DROP FOREIGN KEY FK_A74734281844E6B7');
        $this->addSql('ALTER TABLE emote DROP FOREIGN KEY FK_6BBC85C61844E6B7');
        $this->addSql('ALTER TABLE plugin_settings DROP FOREIGN KEY FK_458DE02A1844E6B7');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6A1844E6B7');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB251844E6B7');
        $this->addSql('ALTER TABLE user_server DROP FOREIGN KEY FK_3F3FCECB1844E6B7');
        $this->addSql('ALTER TABLE user_server DROP FOREIGN KEY FK_3F3FCECBA76ED395');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE component');
        $this->addSql('DROP TABLE component_settings');
        $this->addSql('DROP TABLE component_type');
        $this->addSql('DROP TABLE emote');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE plugin');
        $this->addSql('DROP TABLE plugin_settings');
        $this->addSql('DROP TABLE plugin_type');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_server');
    }
}
