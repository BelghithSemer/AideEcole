<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305122858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, nbre_place INT NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce_user (annonce_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B7E60AD78805AB2F (annonce_id), INDEX IDX_B7E60AD7A76ED395 (user_id), PRIMARY KEY(annonce_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bons (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, code VARCHAR(8) NOT NULL, date_deb DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_C1920A2679F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, commentaire_annonce_id INT DEFAULT NULL, user_id INT NOT NULL, description VARCHAR(255) NOT NULL, likes INT NOT NULL, dislikes INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_67F068BC303799AF (commentaire_annonce_id), INDEX IDX_67F068BCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire_user_likes (commentaire_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_133A87A5BA9CD190 (commentaire_id), INDEX IDX_133A87A5A76ED395 (user_id), PRIMARY KEY(commentaire_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire_user_dislikes (commentaire_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9EC44D8BA9CD190 (commentaire_id), INDEX IDX_9EC44D8A76ED395 (user_id), PRIMARY KEY(commentaire_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire_forum (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, forum_id INT NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A776D1A76ED395 (user_id), INDEX IDX_A776D129CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, enseignant_id INT DEFAULT NULL, matiere_id INT DEFAULT NULL, niveau_id INT DEFAULT NULL, paiement_cours_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', pdf_file_name VARCHAR(255) NOT NULL, INDEX IDX_FDCA8C9CE455FCC0 (enseignant_id), INDEX IDX_FDCA8C9CF46CD258 (matiere_id), INDEX IDX_FDCA8C9CB3E9C81 (niveau_id), INDEX IDX_FDCA8C9CB468808A (paiement_cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donation (code INT AUTO_INCREMENT NOT NULL, donnateur_id INT NOT NULL, signalement_id INT NOT NULL, date DATETIME NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_31E581A0FD794C6D (donnateur_id), INDEX IDX_31E581A065C5E57E (signalement_id), PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_8933C432A76ED395 (user_id), INDEX IDX_8933C432591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, avis1 VARCHAR(255) NOT NULL, avis2 VARCHAR(255) NOT NULL, avis3 VARCHAR(255) NOT NULL, avis4 VARCHAR(255) NOT NULL, avis5 VARCHAR(255) NOT NULL, avis6 VARCHAR(255) NOT NULL, avis7 VARCHAR(255) NOT NULL, avis8 VARCHAR(255) NOT NULL, avis9 VARCHAR(255) NOT NULL, avis10 VARCHAR(255) NOT NULL, avis11 VARCHAR(255) NOT NULL, avis12 VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, experience_level VARCHAR(255) NOT NULL, recommend_platform VARCHAR(255) NOT NULL, usage_frequency VARCHAR(255) NOT NULL, note INT NOT NULL, INDEX IDX_D22944589D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, forum_user_id INT DEFAULT NULL, date DATE NOT NULL, questions VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, images VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, INDEX IDX_852BBECD56A29EE4 (forum_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gouvernorat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere_niveau (matiere_id INT NOT NULL, niveau_id INT NOT NULL, INDEX IDX_6B3CD676F46CD258 (matiere_id), INDEX IDX_6B3CD676B3E9C81 (niveau_id), PRIMARY KEY(matiere_id, niveau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, note INT NOT NULL, UNIQUE INDEX UNIQ_CFBDFA14853CD175 (quiz_id), UNIQUE INDEX UNIQ_CFBDFA1479F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement_annonce (id INT AUTO_INCREMENT NOT NULL, paiement_annonce_id_id INT DEFAULT NULL, annonce_id_id INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, num_carte INT NOT NULL, date_validite DATE NOT NULL, INDEX IDX_EB6F23142DA8BF86 (paiement_annonce_id_id), UNIQUE INDEX UNIQ_EB6F231468C955C8 (annonce_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement_cours (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION NOT NULL, date_validité DATE NOT NULL, num_carte INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, options JSON NOT NULL COMMENT \'(DC2Type:json)\', correct_answer INT NOT NULL, INDEX IDX_B6F7494E853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_niveau_id INT DEFAULT NULL, id_matier_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A412FA9279F37AE5 (id_user_id), UNIQUE INDEX UNIQ_A412FA928B0B20A6 (id_niveau_id), UNIQUE INDEX UNIQ_A412FA92E96948FD (id_matier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, reclamation_id_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, statut VARCHAR(50) NOT NULL, INDEX IDX_CE606404A248AD19 (reclamation_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, responsable_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', etat VARCHAR(20) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, gravite INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, reste DOUBLE PRECISION NOT NULL, INDEX IDX_F4B5511453C59D72 (responsable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signalement_termine (id INT AUTO_INCREMENT NOT NULL, signalement_id INT NOT NULL, images_apres LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', description LONGTEXT DEFAULT NULL, INDEX IDX_E824427B65C5E57E (signalement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, gouvernorat_id INT DEFAULT NULL, annonce_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom_etabli VARCHAR(255) DEFAULT NULL, nom_form VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, local VARCHAR(255) DEFAULT NULL, code_postal INT DEFAULT NULL, rib VARCHAR(255) DEFAULT NULL, doc_verif VARCHAR(255) DEFAULT NULL, list_favoris_name VARCHAR(255) DEFAULT NULL, agree_terms TINYINT(1) NOT NULL, INDEX IDX_8D93D64975B74E2D (gouvernorat_id), INDEX IDX_8D93D6498805AB2F (annonce_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_cours_participe (user_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_4A2F6D89A76ED395 (user_id), INDEX IDX_4A2F6D897ECF78B0 (cours_id), PRIMARY KEY(user_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce_user ADD CONSTRAINT FK_B7E60AD78805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_user ADD CONSTRAINT FK_B7E60AD7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bons ADD CONSTRAINT FK_C1920A2679F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC303799AF FOREIGN KEY (commentaire_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire_user_likes ADD CONSTRAINT FK_133A87A5BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_user_likes ADD CONSTRAINT FK_133A87A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_user_dislikes ADD CONSTRAINT FK_9EC44D8BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_user_dislikes ADD CONSTRAINT FK_9EC44D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_forum ADD CONSTRAINT FK_A776D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire_forum ADD CONSTRAINT FK_A776D129CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CB468808A FOREIGN KEY (paiement_cours_id) REFERENCES paiement_cours (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0FD794C6D FOREIGN KEY (donnateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A065C5E57E FOREIGN KEY (signalement_id) REFERENCES signalement (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432591CC992 FOREIGN KEY (course_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD56A29EE4 FOREIGN KEY (forum_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE matiere_niveau ADD CONSTRAINT FK_6B3CD676F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE matiere_niveau ADD CONSTRAINT FK_6B3CD676B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paiement_annonce ADD CONSTRAINT FK_EB6F23142DA8BF86 FOREIGN KEY (paiement_annonce_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paiement_annonce ADD CONSTRAINT FK_EB6F231468C955C8 FOREIGN KEY (annonce_id_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA9279F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA928B0B20A6 FOREIGN KEY (id_niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92E96948FD FOREIGN KEY (id_matier_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A248AD19 FOREIGN KEY (reclamation_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511453C59D72 FOREIGN KEY (responsable_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement_termine ADD CONSTRAINT FK_E824427B65C5E57E FOREIGN KEY (signalement_id) REFERENCES signalement (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64975B74E2D FOREIGN KEY (gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE users_cours_participe ADD CONSTRAINT FK_4A2F6D89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_cours_participe ADD CONSTRAINT FK_4A2F6D897ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce_user DROP FOREIGN KEY FK_B7E60AD78805AB2F');
        $this->addSql('ALTER TABLE annonce_user DROP FOREIGN KEY FK_B7E60AD7A76ED395');
        $this->addSql('ALTER TABLE bons DROP FOREIGN KEY FK_C1920A2679F37AE5');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC303799AF');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire_user_likes DROP FOREIGN KEY FK_133A87A5BA9CD190');
        $this->addSql('ALTER TABLE commentaire_user_likes DROP FOREIGN KEY FK_133A87A5A76ED395');
        $this->addSql('ALTER TABLE commentaire_user_dislikes DROP FOREIGN KEY FK_9EC44D8BA9CD190');
        $this->addSql('ALTER TABLE commentaire_user_dislikes DROP FOREIGN KEY FK_9EC44D8A76ED395');
        $this->addSql('ALTER TABLE commentaire_forum DROP FOREIGN KEY FK_A776D1A76ED395');
        $this->addSql('ALTER TABLE commentaire_forum DROP FOREIGN KEY FK_A776D129CCBAD0');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CE455FCC0');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CF46CD258');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CB3E9C81');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CB468808A');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0FD794C6D');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A065C5E57E');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A76ED395');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432591CC992');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944589D86650F');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD56A29EE4');
        $this->addSql('ALTER TABLE matiere_niveau DROP FOREIGN KEY FK_6B3CD676F46CD258');
        $this->addSql('ALTER TABLE matiere_niveau DROP FOREIGN KEY FK_6B3CD676B3E9C81');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14853CD175');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1479F37AE5');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE paiement_annonce DROP FOREIGN KEY FK_EB6F23142DA8BF86');
        $this->addSql('ALTER TABLE paiement_annonce DROP FOREIGN KEY FK_EB6F231468C955C8');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA9279F37AE5');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA928B0B20A6');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92E96948FD');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A248AD19');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B5511453C59D72');
        $this->addSql('ALTER TABLE signalement_termine DROP FOREIGN KEY FK_E824427B65C5E57E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64975B74E2D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498805AB2F');
        $this->addSql('ALTER TABLE users_cours_participe DROP FOREIGN KEY FK_4A2F6D89A76ED395');
        $this->addSql('ALTER TABLE users_cours_participe DROP FOREIGN KEY FK_4A2F6D897ECF78B0');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE annonce_user');
        $this->addSql('DROP TABLE bons');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE commentaire_user_likes');
        $this->addSql('DROP TABLE commentaire_user_dislikes');
        $this->addSql('DROP TABLE commentaire_forum');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE donation');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE gouvernorat');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE matiere_niveau');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE paiement_annonce');
        $this->addSql('DROP TABLE paiement_cours');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE signalement');
        $this->addSql('DROP TABLE signalement_termine');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users_cours_participe');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
