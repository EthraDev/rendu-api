<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716113410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boisson (id INT AUTO_INCREMENT NOT NULL, photo_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_8B97C84D7E9E4C8C (photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, serveur_id INT DEFAULT NULL, barman_id INT DEFAULT NULL, created_date DATETIME NOT NULL, table_number INT NOT NULL, status VARCHAR(100) NOT NULL, INDEX IDX_6EEAA67DB8F06499 (serveur_id), INDEX IDX_6EEAA67DA1EB02C0 (barman_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_boisson (commande_id INT NOT NULL, boisson_id INT NOT NULL, INDEX IDX_7D2CBAED82EA2E54 (commande_id), INDEX IDX_7D2CBAED734B8089 (boisson_id), PRIMARY KEY(commande_id, boisson_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, boisson_id INT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_6A2CA10C734B8089 (boisson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, password VARCHAR(100) NOT NULL, role VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boisson ADD CONSTRAINT FK_8B97C84D7E9E4C8C FOREIGN KEY (photo_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DB8F06499 FOREIGN KEY (serveur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA1EB02C0 FOREIGN KEY (barman_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commande_boisson ADD CONSTRAINT FK_7D2CBAED82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_boisson ADD CONSTRAINT FK_7D2CBAED734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boisson DROP FOREIGN KEY FK_8B97C84D7E9E4C8C');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DB8F06499');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA1EB02C0');
        $this->addSql('ALTER TABLE commande_boisson DROP FOREIGN KEY FK_7D2CBAED82EA2E54');
        $this->addSql('ALTER TABLE commande_boisson DROP FOREIGN KEY FK_7D2CBAED734B8089');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C734B8089');
        $this->addSql('DROP TABLE boisson');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_boisson');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE `user`');
    }
}
