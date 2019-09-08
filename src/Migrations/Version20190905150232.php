<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190905150232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE reviews (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, pseudo VARCHAR(80) DEFAULT NULL, commentary CLOB NOT NULL, score INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6970EB0F59D8A214 ON reviews (recipe_id)');
        $this->addSql('CREATE TABLE ingredient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, quantity INTEGER NOT NULL, picture VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE ingredient_unit (ingredient_id INTEGER NOT NULL, unit_id INTEGER NOT NULL, PRIMARY KEY(ingredient_id, unit_id))');
        $this->addSql('CREATE INDEX IDX_82EABCC5933FE08C ON ingredient_unit (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_82EABCC5F8BD700D ON ingredient_unit (unit_id)');
        $this->addSql('CREATE TABLE kitchen_tools (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(80) DEFAULT NULL)');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL)');
        $this->addSql('CREATE TABLE steps (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recipe_id INTEGER DEFAULT NULL, spot INTEGER NOT NULL, description CLOB NOT NULL)');
        $this->addSql('CREATE INDEX IDX_34220A7259D8A214 ON steps (recipe_id)');
        $this->addSql('CREATE TABLE unit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(80) NOT NULL)');
        $this->addSql('CREATE TABLE recipe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(130) NOT NULL, image VARCHAR(255) NOT NULL, preparation_time INTEGER NOT NULL, price_range INTEGER NOT NULL, difficulty_level INTEGER NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE recipe_tag (recipe_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_72DED3CF59D8A214 ON recipe_tag (recipe_id)');
        $this->addSql('CREATE INDEX IDX_72DED3CFBAD26311 ON recipe_tag (tag_id)');
        $this->addSql('CREATE TABLE recipe_ingredient (recipe_id INTEGER NOT NULL, ingredient_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_22D1FE1359D8A214 ON recipe_ingredient (recipe_id)');
        $this->addSql('CREATE INDEX IDX_22D1FE13933FE08C ON recipe_ingredient (ingredient_id)');
        $this->addSql('CREATE TABLE recipe_kitchen_tools (recipe_id INTEGER NOT NULL, kitchen_tools_id INTEGER NOT NULL, PRIMARY KEY(recipe_id, kitchen_tools_id))');
        $this->addSql('CREATE INDEX IDX_C252D4B159D8A214 ON recipe_kitchen_tools (recipe_id)');
        $this->addSql('CREATE INDEX IDX_C252D4B186813830 ON recipe_kitchen_tools (kitchen_tools_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_unit');
        $this->addSql('DROP TABLE kitchen_tools');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE steps');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_tag');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('DROP TABLE recipe_kitchen_tools');
    }
}
