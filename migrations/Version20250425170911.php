<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425170911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE dahiras (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE encadreur (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, dahiras_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_43B1C5B9A76ED395 (user_id), INDEX IDX_43B1C5B96F8C0AC4 (dahiras_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE intervenant (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, reunion_id INT DEFAULT NULL, dahira_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, INDEX IDX_73D0145C6A99F74A (membre_id), INDEX IDX_73D0145C4E9B7368 (reunion_id), INDEX IDX_73D0145CF5ED07D8 (dahira_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE membres (id INT AUTO_INCREMENT NOT NULL, dahiras_id INT DEFAULT NULL, encadreur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, numero VARCHAR(255) NOT NULL, poste VARCHAR(255) DEFAULT NULL, INDEX IDX_594AE39C6F8C0AC4 (dahiras_id), INDEX IDX_594AE39CA625A0FD (encadreur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE membres_specialites (membres_id INT NOT NULL, specialites_id INT NOT NULL, INDEX IDX_295C335071128C5C (membres_id), INDEX IDX_295C33505AEDDAD9 (specialites_id), PRIMARY KEY(membres_id, specialites_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE presence (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, reunion_id INT DEFAULT NULL, present TINYINT(1) NOT NULL, date DATETIME NOT NULL, INDEX IDX_6977C7A56A99F74A (membre_id), INDEX IDX_6977C7A54E9B7368 (reunion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reunion (id INT AUTO_INCREMENT NOT NULL, encadreur_id INT DEFAULT NULL, theme_id INT DEFAULT NULL, dahiras_id INT DEFAULT NULL, date DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, sujetaborde LONGTEXT NOT NULL, decisionprise LONGTEXT NOT NULL, numero VARCHAR(255) NOT NULL, INDEX IDX_5B00A482A625A0FD (encadreur_id), INDEX IDX_5B00A48259027487 (theme_id), INDEX IDX_5B00A4826F8C0AC4 (dahiras_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reunion_membres (reunion_id INT NOT NULL, membres_id INT NOT NULL, INDEX IDX_C03B89E14E9B7368 (reunion_id), INDEX IDX_C03B89E171128C5C (membres_id), PRIMARY KEY(reunion_id, membres_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE specialites (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE themes (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE encadreur ADD CONSTRAINT FK_43B1C5B9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE encadreur ADD CONSTRAINT FK_43B1C5B96F8C0AC4 FOREIGN KEY (dahiras_id) REFERENCES dahiras (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145C6A99F74A FOREIGN KEY (membre_id) REFERENCES membres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145C4E9B7368 FOREIGN KEY (reunion_id) REFERENCES reunion (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145CF5ED07D8 FOREIGN KEY (dahira_id) REFERENCES dahiras (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membres ADD CONSTRAINT FK_594AE39C6F8C0AC4 FOREIGN KEY (dahiras_id) REFERENCES dahiras (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membres ADD CONSTRAINT FK_594AE39CA625A0FD FOREIGN KEY (encadreur_id) REFERENCES encadreur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membres_specialites ADD CONSTRAINT FK_295C335071128C5C FOREIGN KEY (membres_id) REFERENCES membres (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membres_specialites ADD CONSTRAINT FK_295C33505AEDDAD9 FOREIGN KEY (specialites_id) REFERENCES specialites (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence ADD CONSTRAINT FK_6977C7A56A99F74A FOREIGN KEY (membre_id) REFERENCES membres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence ADD CONSTRAINT FK_6977C7A54E9B7368 FOREIGN KEY (reunion_id) REFERENCES reunion (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion ADD CONSTRAINT FK_5B00A482A625A0FD FOREIGN KEY (encadreur_id) REFERENCES encadreur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion ADD CONSTRAINT FK_5B00A48259027487 FOREIGN KEY (theme_id) REFERENCES themes (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion ADD CONSTRAINT FK_5B00A4826F8C0AC4 FOREIGN KEY (dahiras_id) REFERENCES dahiras (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion_membres ADD CONSTRAINT FK_C03B89E14E9B7368 FOREIGN KEY (reunion_id) REFERENCES reunion (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion_membres ADD CONSTRAINT FK_C03B89E171128C5C FOREIGN KEY (membres_id) REFERENCES membres (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE encadreur DROP FOREIGN KEY FK_43B1C5B9A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE encadreur DROP FOREIGN KEY FK_43B1C5B96F8C0AC4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145C6A99F74A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145C4E9B7368
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145CF5ED07D8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membres DROP FOREIGN KEY FK_594AE39C6F8C0AC4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membres DROP FOREIGN KEY FK_594AE39CA625A0FD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membres_specialites DROP FOREIGN KEY FK_295C335071128C5C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membres_specialites DROP FOREIGN KEY FK_295C33505AEDDAD9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A56A99F74A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A54E9B7368
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion DROP FOREIGN KEY FK_5B00A482A625A0FD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion DROP FOREIGN KEY FK_5B00A48259027487
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion DROP FOREIGN KEY FK_5B00A4826F8C0AC4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion_membres DROP FOREIGN KEY FK_C03B89E14E9B7368
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion_membres DROP FOREIGN KEY FK_C03B89E171128C5C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE dahiras
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE encadreur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE intervenant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE membres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE membres_specialites
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE presence
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reunion
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reunion_membres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE specialites
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE themes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
