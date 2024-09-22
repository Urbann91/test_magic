<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922173328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answer_options_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE attempts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE questions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tests_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer_options (id INT NOT NULL, question_id INT NOT NULL, text VARCHAR(255) NOT NULL, is_correct BOOLEAN NOT NULL, bit_mask INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_987535F61E27F6BF ON answer_options (question_id)');
        $this->addSql('CREATE TABLE answers (id INT NOT NULL, attempt_id INT DEFAULT NULL, answer_option_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_50D0C606B191BE6B ON answers (attempt_id)');
        $this->addSql('CREATE INDEX IDX_50D0C6069A3BC2B9 ON answers (answer_option_id)');
        $this->addSql('CREATE TABLE app_users (id INT NOT NULL, username VARCHAR(180) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2502824F85E0677 ON app_users (username)');
        $this->addSql('CREATE TABLE attempts (id INT NOT NULL, user_id INT DEFAULT NULL, test_id INT DEFAULT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BFC7E764A76ED395 ON attempts (user_id)');
        $this->addSql('CREATE INDEX IDX_BFC7E7641E5D0459 ON attempts (test_id)');
        $this->addSql('CREATE TABLE questions (id INT NOT NULL, text VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tests (id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE test_questions (test_id INT NOT NULL, question_id INT NOT NULL, PRIMARY KEY(test_id, question_id))');
        $this->addSql('CREATE INDEX IDX_841C31F1E5D0459 ON test_questions (test_id)');
        $this->addSql('CREATE INDEX IDX_841C31F1E27F6BF ON test_questions (question_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE answer_options ADD CONSTRAINT FK_987535F61E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606B191BE6B FOREIGN KEY (attempt_id) REFERENCES attempts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6069A3BC2B9 FOREIGN KEY (answer_option_id) REFERENCES answer_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attempts ADD CONSTRAINT FK_BFC7E764A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attempts ADD CONSTRAINT FK_BFC7E7641E5D0459 FOREIGN KEY (test_id) REFERENCES tests (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_questions ADD CONSTRAINT FK_841C31F1E5D0459 FOREIGN KEY (test_id) REFERENCES tests (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_questions ADD CONSTRAINT FK_841C31F1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE answer_options_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE answers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_users_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE attempts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE questions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tests_id_seq CASCADE');
        $this->addSql('ALTER TABLE answer_options DROP CONSTRAINT FK_987535F61E27F6BF');
        $this->addSql('ALTER TABLE answers DROP CONSTRAINT FK_50D0C606B191BE6B');
        $this->addSql('ALTER TABLE answers DROP CONSTRAINT FK_50D0C6069A3BC2B9');
        $this->addSql('ALTER TABLE attempts DROP CONSTRAINT FK_BFC7E764A76ED395');
        $this->addSql('ALTER TABLE attempts DROP CONSTRAINT FK_BFC7E7641E5D0459');
        $this->addSql('ALTER TABLE test_questions DROP CONSTRAINT FK_841C31F1E5D0459');
        $this->addSql('ALTER TABLE test_questions DROP CONSTRAINT FK_841C31F1E27F6BF');
        $this->addSql('DROP TABLE answer_options');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE attempts');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE tests');
        $this->addSql('DROP TABLE test_questions');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
