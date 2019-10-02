<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190930221645 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE recipe_search (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, tags VARCHAR(255) NOT NULL, preparation_time INTEGER NOT NULL, difficulty_level INTEGER NOT NULL, ingredients VARCHAR(255) NOT NULL)');
        $this->addSql('DROP INDEX IDX_6970EB0F59D8A214');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reviews AS SELECT id, recipe_id, pseudo, commentary, score FROM reviews');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('CREATE TABLE reviews (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, pseudo VARCHAR(80) DEFAULT NULL COLLATE BINARY, commentary CLOB NOT NULL COLLATE BINARY, score INTEGER NOT NULL, CONSTRAINT FK_6970EB0F59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reviews (id, recipe_id, pseudo, commentary, score) SELECT id, recipe_id, pseudo, commentary, score FROM __temp__reviews');
        $this->addSql('DROP TABLE __temp__reviews');
        $this->addSql('CREATE INDEX IDX_6970EB0F59D8A214 ON reviews (recipe_id)');
        $this->addSql('DROP INDEX IDX_82EABCC5933FE08C');
        $this->addSql('DROP INDEX IDX_82EABCC5F8BD700D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ingredient_unit AS SELECT ingredient_id, unit_id FROM ingredient_unit');
        $this->addSql('DROP TABLE ingredient_unit');
        $this->addSql('CREATE TABLE ingredient_unit (ingredient_id INTEGER NOT NULL, unit_id INTEGER NOT NULL, PRIMARY KEY(ingredient_id, unit_id), CONSTRAINT FK_82EABCC5933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_82EABCC5F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ingredient_unit (ingredient_id, unit_id) SELECT ingredient_id, unit_id FROM __temp__ingredient_unit');
        $this->addSql('DROP TABLE __temp__ingredient_unit');
        $this->addSql('CREATE INDEX IDX_82EABCC5933FE08C ON ingredient_unit (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_82EABCC5F8BD700D ON ingredient_unit (unit_id)');
        $this->addSql('DROP INDEX IDX_5F9E962A59D8A214');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comments AS SELECT id, recipe_id, author, content, picture, created_at, email FROM comments');
        $this->addSql('DROP TABLE comments');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, author VARCHAR(30) NOT NULL COLLATE BINARY, content CLOB NOT NULL COLLATE BINARY, picture VARCHAR(255) DEFAULT NULL COLLATE BINARY, created_at DATETIME NOT NULL, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_5F9E962A59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comments (id, recipe_id, author, content, picture, created_at, email) SELECT id, recipe_id, author, content, picture, created_at, email FROM __temp__comments');
        $this->addSql('DROP TABLE __temp__comments');
        $this->addSql('CREATE INDEX IDX_5F9E962A59D8A214 ON comments (recipe_id)');
        $this->addSql('DROP INDEX IDX_34220A7259D8A214');
        $this->addSql('CREATE TEMPORARY TABLE __temp__steps AS SELECT id, recipe_id, spot, description FROM steps');
        $this->addSql('DROP TABLE steps');
        $this->addSql('CREATE TABLE steps (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, spot INTEGER NOT NULL, description CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_34220A7259D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO steps (id, recipe_id, spot, description) SELECT id, recipe_id, spot, description FROM __temp__steps');
        $this->addSql('DROP TABLE __temp__steps');
        $this->addSql('CREATE INDEX IDX_34220A7259D8A214 ON steps (recipe_id)');
        $this->addSql('DROP INDEX IDX_72DED3CF59D8A214');
        $this->addSql('DROP INDEX IDX_72DED3CFBAD26311');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe_tag AS SELECT recipe_id, tag_id FROM recipe_tag');
        $this->addSql('DROP TABLE recipe_tag');
        $this->addSql('CREATE TABLE recipe_tag (recipe_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, tag_id), CONSTRAINT FK_72DED3CF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_72DED3CFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO recipe_tag (recipe_id, tag_id) SELECT recipe_id, tag_id FROM __temp__recipe_tag');
        $this->addSql('DROP TABLE __temp__recipe_tag');
        $this->addSql('CREATE INDEX IDX_72DED3CF59D8A214 ON recipe_tag (recipe_id)');
        $this->addSql('CREATE INDEX IDX_72DED3CFBAD26311 ON recipe_tag (tag_id)');
        $this->addSql('DROP INDEX IDX_22D1FE1359D8A214');
        $this->addSql('DROP INDEX IDX_22D1FE13933FE08C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe_ingredient AS SELECT recipe_id, ingredient_id FROM recipe_ingredient');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('CREATE TABLE recipe_ingredient (recipe_id INTEGER NOT NULL, ingredient_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, ingredient_id), CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_22D1FE13933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO recipe_ingredient (recipe_id, ingredient_id) SELECT recipe_id, ingredient_id FROM __temp__recipe_ingredient');
        $this->addSql('DROP TABLE __temp__recipe_ingredient');
        $this->addSql('CREATE INDEX IDX_22D1FE1359D8A214 ON recipe_ingredient (recipe_id)');
        $this->addSql('CREATE INDEX IDX_22D1FE13933FE08C ON recipe_ingredient (ingredient_id)');
        $this->addSql('DROP INDEX IDX_C252D4B159D8A214');
        $this->addSql('DROP INDEX IDX_C252D4B186813830');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe_kitchen_tools AS SELECT recipe_id, kitchen_tools_id FROM recipe_kitchen_tools');
        $this->addSql('DROP TABLE recipe_kitchen_tools');
        $this->addSql('CREATE TABLE recipe_kitchen_tools (recipe_id INTEGER NOT NULL, kitchen_tools_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, kitchen_tools_id), CONSTRAINT FK_C252D4B159D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C252D4B186813830 FOREIGN KEY (kitchen_tools_id) REFERENCES kitchen_tools (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO recipe_kitchen_tools (recipe_id, kitchen_tools_id) SELECT recipe_id, kitchen_tools_id FROM __temp__recipe_kitchen_tools');
        $this->addSql('DROP TABLE __temp__recipe_kitchen_tools');
        $this->addSql('CREATE INDEX IDX_C252D4B159D8A214 ON recipe_kitchen_tools (recipe_id)');
        $this->addSql('CREATE INDEX IDX_C252D4B186813830 ON recipe_kitchen_tools (kitchen_tools_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE recipe_search');
        $this->addSql('DROP INDEX IDX_5F9E962A59D8A214');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comments AS SELECT id, recipe_id, author, content, picture, created_at, email FROM comments');
        $this->addSql('DROP TABLE comments');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, author VARCHAR(30) NOT NULL, content CLOB NOT NULL, picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, email VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO comments (id, recipe_id, author, content, picture, created_at, email) SELECT id, recipe_id, author, content, picture, created_at, email FROM __temp__comments');
        $this->addSql('DROP TABLE __temp__comments');
        $this->addSql('CREATE INDEX IDX_5F9E962A59D8A214 ON comments (recipe_id)');
        $this->addSql('DROP INDEX IDX_82EABCC5933FE08C');
        $this->addSql('DROP INDEX IDX_82EABCC5F8BD700D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ingredient_unit AS SELECT ingredient_id, unit_id FROM ingredient_unit');
        $this->addSql('DROP TABLE ingredient_unit');
        $this->addSql('CREATE TABLE ingredient_unit (ingredient_id INTEGER NOT NULL, unit_id INTEGER NOT NULL, PRIMARY KEY(ingredient_id, unit_id))');
        $this->addSql('INSERT INTO ingredient_unit (ingredient_id, unit_id) SELECT ingredient_id, unit_id FROM __temp__ingredient_unit');
        $this->addSql('DROP TABLE __temp__ingredient_unit');
        $this->addSql('CREATE INDEX IDX_82EABCC5933FE08C ON ingredient_unit (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_82EABCC5F8BD700D ON ingredient_unit (unit_id)');
        $this->addSql('DROP INDEX IDX_22D1FE1359D8A214');
        $this->addSql('DROP INDEX IDX_22D1FE13933FE08C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe_ingredient AS SELECT recipe_id, ingredient_id FROM recipe_ingredient');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('CREATE TABLE recipe_ingredient (recipe_id INTEGER NOT NULL, ingredient_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, ingredient_id))');
        $this->addSql('INSERT INTO recipe_ingredient (recipe_id, ingredient_id) SELECT recipe_id, ingredient_id FROM __temp__recipe_ingredient');
        $this->addSql('DROP TABLE __temp__recipe_ingredient');
        $this->addSql('CREATE INDEX IDX_22D1FE1359D8A214 ON recipe_ingredient (recipe_id)');
        $this->addSql('CREATE INDEX IDX_22D1FE13933FE08C ON recipe_ingredient (ingredient_id)');
        $this->addSql('DROP INDEX IDX_C252D4B159D8A214');
        $this->addSql('DROP INDEX IDX_C252D4B186813830');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe_kitchen_tools AS SELECT recipe_id, kitchen_tools_id FROM recipe_kitchen_tools');
        $this->addSql('DROP TABLE recipe_kitchen_tools');
        $this->addSql('CREATE TABLE recipe_kitchen_tools (recipe_id INTEGER NOT NULL, kitchen_tools_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, kitchen_tools_id))');
        $this->addSql('INSERT INTO recipe_kitchen_tools (recipe_id, kitchen_tools_id) SELECT recipe_id, kitchen_tools_id FROM __temp__recipe_kitchen_tools');
        $this->addSql('DROP TABLE __temp__recipe_kitchen_tools');
        $this->addSql('CREATE INDEX IDX_C252D4B159D8A214 ON recipe_kitchen_tools (recipe_id)');
        $this->addSql('CREATE INDEX IDX_C252D4B186813830 ON recipe_kitchen_tools (kitchen_tools_id)');
        $this->addSql('DROP INDEX IDX_72DED3CF59D8A214');
        $this->addSql('DROP INDEX IDX_72DED3CFBAD26311');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe_tag AS SELECT recipe_id, tag_id FROM recipe_tag');
        $this->addSql('DROP TABLE recipe_tag');
        $this->addSql('CREATE TABLE recipe_tag (recipe_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, tag_id))');
        $this->addSql('INSERT INTO recipe_tag (recipe_id, tag_id) SELECT recipe_id, tag_id FROM __temp__recipe_tag');
        $this->addSql('DROP TABLE __temp__recipe_tag');
        $this->addSql('CREATE INDEX IDX_72DED3CF59D8A214 ON recipe_tag (recipe_id)');
        $this->addSql('CREATE INDEX IDX_72DED3CFBAD26311 ON recipe_tag (tag_id)');
        $this->addSql('DROP INDEX IDX_6970EB0F59D8A214');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reviews AS SELECT id, recipe_id, pseudo, commentary, score FROM reviews');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('CREATE TABLE reviews (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, pseudo VARCHAR(80) DEFAULT NULL, commentary CLOB NOT NULL, score INTEGER NOT NULL)');
        $this->addSql('INSERT INTO reviews (id, recipe_id, pseudo, commentary, score) SELECT id, recipe_id, pseudo, commentary, score FROM __temp__reviews');
        $this->addSql('DROP TABLE __temp__reviews');
        $this->addSql('CREATE INDEX IDX_6970EB0F59D8A214 ON reviews (recipe_id)');
        $this->addSql('DROP INDEX IDX_34220A7259D8A214');
        $this->addSql('CREATE TEMPORARY TABLE __temp__steps AS SELECT id, recipe_id, spot, description FROM steps');
        $this->addSql('DROP TABLE steps');
        $this->addSql('CREATE TABLE steps (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, spot INTEGER NOT NULL, description CLOB NOT NULL)');
        $this->addSql('INSERT INTO steps (id, recipe_id, spot, description) SELECT id, recipe_id, spot, description FROM __temp__steps');
        $this->addSql('DROP TABLE __temp__steps');
        $this->addSql('CREATE INDEX IDX_34220A7259D8A214 ON steps (recipe_id)');
    }
}
