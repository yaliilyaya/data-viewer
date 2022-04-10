<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215081311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD COLUMN label VARCHAR(255) DEFAULT \'\' NOT NULL');
        $this->addSql('DROP INDEX IDX_DA88B137126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, item_id, type, data FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, item_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, data CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , CONSTRAINT FK_DA88B137126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO recipe (id, item_id, type, data) SELECT id, item_id, type, data FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
        $this->addSql('CREATE INDEX IDX_DA88B137126F525E ON recipe (item_id)');
        $this->addSql('DROP INDEX IDX_7DF7A03D59D8A214');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe_stream AS SELECT id, recipe_id, type, data FROM recipe_stream');
        $this->addSql('DROP TABLE recipe_stream');
        $this->addSql('CREATE TABLE recipe_stream (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, item_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, data CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , CONSTRAINT FK_7DF7A03D59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7DF7A03D126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO recipe_stream (id, recipe_id, type, data) SELECT id, recipe_id, type, data FROM __temp__recipe_stream');
        $this->addSql('DROP TABLE __temp__recipe_stream');
        $this->addSql('CREATE INDEX IDX_7DF7A03D59D8A214 ON recipe_stream (recipe_id)');
        $this->addSql('CREATE INDEX IDX_7DF7A03D126F525E ON recipe_stream (item_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__item AS SELECT id, status, data FROM item');
        $this->addSql('DROP TABLE item');
        $this->addSql('CREATE TABLE item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, status VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO item (id, status, data) SELECT id, status, data FROM __temp__item');
        $this->addSql('DROP TABLE __temp__item');
        $this->addSql('DROP INDEX IDX_DA88B137126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, item_id, type, data FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, item_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO recipe (id, item_id, type, data) SELECT id, item_id, type, data FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
        $this->addSql('CREATE INDEX IDX_DA88B137126F525E ON recipe (item_id)');
        $this->addSql('DROP INDEX IDX_7DF7A03D59D8A214');
        $this->addSql('DROP INDEX IDX_7DF7A03D126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe_stream AS SELECT id, recipe_id, type, data FROM recipe_stream');
        $this->addSql('DROP TABLE recipe_stream');
        $this->addSql('CREATE TABLE recipe_stream (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO recipe_stream (id, recipe_id, type, data) SELECT id, recipe_id, type, data FROM __temp__recipe_stream');
        $this->addSql('DROP TABLE __temp__recipe_stream');
        $this->addSql('CREATE INDEX IDX_7DF7A03D59D8A214 ON recipe_stream (recipe_id)');
    }
}
