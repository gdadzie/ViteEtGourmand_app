from pathlib import Path
from docx import Document
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_CELL_VERTICAL_ALIGNMENT
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
from docx.shared import Cm, Pt, RGBColor


ROOT = Path(__file__).resolve().parents[1]
OUTPUT = ROOT / 'output' / 'documentation' / 'Guide technique Vite et Gourmand pour le jury.docx'


def shade(cell, fill):
    tc_pr = cell._tc.get_or_add_tcPr()
    shading = OxmlElement('w:shd')
    shading.set(qn('w:fill'), fill)
    tc_pr.append(shading)


def set_cell_text(cell, text, bold=False, color=None):
    cell.text = ''
    p = cell.paragraphs[0]
    run = p.add_run(text)
    run.bold = bold
    run.font.size = Pt(9)
    if color:
        run.font.color.rgb = RGBColor(*color)
    cell.vertical_alignment = WD_CELL_VERTICAL_ALIGNMENT.CENTER


def add_bullet(doc, text, level=0):
    style = 'List Bullet' if level == 0 else 'List Bullet 2'
    p = doc.add_paragraph(style=style)
    p.add_run(text)
    return p


def add_number(doc, text):
    p = doc.add_paragraph(style='List Number')
    p.add_run(text)
    return p


def add_code(doc, text):
    p = doc.add_paragraph()
    p.paragraph_format.left_indent = Cm(.6)
    p.paragraph_format.right_indent = Cm(.6)
    p.paragraph_format.space_before = Pt(4)
    p.paragraph_format.space_after = Pt(7)
    run = p.add_run(text)
    run.font.name = 'Consolas'
    run._element.rPr.rFonts.set(qn('w:ascii'), 'Consolas')
    run._element.rPr.rFonts.set(qn('w:hAnsi'), 'Consolas')
    run.font.size = Pt(8.5)
    return p


def add_file_table(doc, rows):
    table = doc.add_table(rows=1, cols=2)
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.style = 'Table Grid'
    table.autofit = False
    table.columns[0].width = Cm(6.2)
    table.columns[1].width = Cm(10.5)
    for cell, label in zip(table.rows[0].cells, ['Fichier', 'Rôle et explication']):
        shade(cell, '7A4B19')
        set_cell_text(cell, label, True, (255, 255, 255))
    for index, (path, role) in enumerate(rows):
        cells = table.add_row().cells
        if index % 2 == 1:
            for cell in cells:
                shade(cell, 'F8F2EB')
        set_cell_text(cells[0], path, True)
        set_cell_text(cells[1], role)
    doc.add_paragraph()


def heading(doc, text, level=1):
    p = doc.add_heading(text, level=level)
    p.paragraph_format.space_before = Pt(13 if level == 1 else 8)
    p.paragraph_format.space_after = Pt(5)
    return p


def para(doc, text, bold_lead=None):
    p = doc.add_paragraph()
    p.paragraph_format.space_after = Pt(6)
    if bold_lead:
        p.add_run(bold_lead).bold = True
    p.add_run(text)
    return p


def build():
    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    doc = Document()
    section = doc.sections[0]
    section.top_margin = Cm(2)
    section.bottom_margin = Cm(2)
    section.left_margin = Cm(2.05)
    section.right_margin = Cm(2.05)

    normal = doc.styles['Normal']
    normal.font.name = 'Aptos'
    normal._element.rPr.rFonts.set(qn('w:ascii'), 'Aptos')
    normal._element.rPr.rFonts.set(qn('w:hAnsi'), 'Aptos')
    normal.font.size = Pt(10.5)
    normal.paragraph_format.space_after = Pt(5)
    for name in ['Title', 'Heading 1', 'Heading 2', 'Heading 3']:
        style = doc.styles[name]
        style.font.name = 'Aptos Display'
        style._element.rPr.rFonts.set(qn('w:ascii'), 'Aptos Display')
        style._element.rPr.rFonts.set(qn('w:hAnsi'), 'Aptos Display')
        style.font.color.rgb = RGBColor(0, 0, 0)

    title = doc.add_paragraph(style='Title')
    title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    title.add_run('Guide technique Vite et Gourmand pour le jury')
    subtitle = doc.add_paragraph()
    subtitle.alignment = WD_ALIGN_PARAGRAPH.CENTER
    subtitle.add_run('Explication du développement de l application PHP MySQL MongoDB et Heroku').italic = True
    doc.add_paragraph()
    para(doc, 'Ce document sert à expliquer simplement mais avec précision comment l application a été construite. Le projet est une application de gestion de menus et de commandes pour un traiteur. Elle comprend un site public, des espaces client employé et administrateur, une base MySQL pour le métier, MongoDB pour les statistiques, une messagerie de contact, des e mails transactionnels et un déploiement Heroku.')
    para(doc, 'À l oral, le plus important est de toujours distinguer trois idées : ce que voit l utilisateur, ce que contrôle le serveur, et ce qui est enregistré en base. Les exemples ci dessous suivent ce fil conducteur.')

    heading(doc, '1 Vue globale de l architecture')
    para(doc, 'Le projet applique progressivement une architecture MVC. MVC signifie Modèle Vue Contrôleur. Le contrôleur reçoit une requête, vérifie les droits et les données, puis demande au dépôt de lire ou écrire en base. La vue affiche seulement le HTML. Les entités représentent les objets métier comme une commande, un menu ou un avis.')
    add_bullet(doc, 'public/index.php est le point d entrée. Il démarre la session, charge Composer, crée les dépendances et route page=... vers le contrôleur approprié.')
    add_bullet(doc, 'src/Controller contient les cas d usage : créer une commande, envoyer un contact, traiter un message, modifier un statut.')
    add_bullet(doc, 'src/Repository contient les requêtes PDO vers MySQL et les accès à MongoDB.')
    add_bullet(doc, 'src/Entity contient les classes métier avec leurs attributs et accesseurs.')
    add_bullet(doc, 'src/View contient les pages PHP et les composants partagés, notamment le menu et le footer.')
    add_bullet(doc, 'src/Service contient les règles transversales : authentification, e mails et logique métier.')
    add_bullet(doc, 'public/assets contient les feuilles CSS, le JavaScript et les images accessibles par le navigateur.')
    add_bullet(doc, 'database contient les migrations SQL versionnées. Elles font évoluer la structure sans mettre les mots de passe dans Git.')
    add_file_table(doc, [
        ('public/index.php', 'Routeur principal. Il instancie les repositories, services et contrôleurs, vérifie le jeton CSRF pour chaque POST, puis appelle la méthode associée à la page demandée.'),
        ('src/View/View.php', 'Moteur de rendu léger. Il injecte les données préparées par le contrôleur dans une vue et le layout principal. Il fournit aussi le retour dynamique vers le bon tableau de bord selon le rôle.'),
        ('src/View/Layout/main.php', 'Layout des pages publiques. Il charge Bootstrap, les CSS communs, le menu, le contenu de la vue puis le footer.'),
        ('config/Database.php', 'Connexion PDO sécurisée. En local il lit les variables DB_HOST etc. En production il lit JAWSDB_URL. PDO est configuré en exceptions et en requêtes préparées non émulées.'),
        ('composer.json', 'Déclare les dépendances PHP : PHPMailer, MongoDB, dotenv et les extensions nécessaires. Il définit aussi le chargement automatique PSR 4 des namespaces.')
    ])

    heading(doc, '2 Authentification rôles et sécurité')
    para(doc, 'Le service d authentification gère la connexion, le hachage des mots de passe et la session. Au moment de la connexion, le serveur recharge l utilisateur en base, vérifie password_verify et bloque explicitement les comptes dont est_actif vaut faux. Il régénère ensuite l identifiant de session pour limiter les risques de fixation de session.')
    add_file_table(doc, [
        ('src/Service/Authentification/AuthService.php', 'Gère login, inscription, déconnexion, réinitialisation et protections requireUtilisateur, requireEmploye, requireAdmin et requireAdminEmploye. Les rôles sont 1 client, 2 employé, 3 administrateur.'),
        ('src/Core/Security.php', 'Démarre une session HttpOnly avec SameSite Lax, crée un jeton CSRF aléatoire et vérifie ce jeton pour tous les formulaires POST. Il injecte automatiquement le champ caché _csrf dans les formulaires POST rendus.'),
        ('database/001_security.sql', 'Crée la table password_reset_tokens. Le jeton stocké est un hash SHA 256, il possède une date d expiration et une date d utilisation.'),
        ('.gitignore', 'Ignore .env, vendor, uploads et les logs. Les secrets SMTP, les identifiants MySQL et les URL privées ne sont donc jamais envoyés dans GitHub.')
    ])
    para(doc, 'Phrase jury : « Les contrôles d accès sont faits côté serveur. Un simple changement d URL dans le navigateur ne permet pas d accéder à une page réservée car le contrôleur vérifie la session et le rôle avant toute requête métier. »')

    heading(doc, '3 Commande calcul des prix et frais de livraison')
    para(doc, 'La création et la modification de commande sont centralisées dans CommandesController. Le serveur ne fait pas confiance au prix éventuellement affiché dans le navigateur. Il relit le menu et la ville depuis la base, contrôle le minimum de personnes, le stock, la date, le mode de réception et le mode de paiement, puis recalcule le montant sur le serveur.')
    heading(doc, 'Formule appliquée', 2)
    add_code(doc, 'prix menus = prix par personne × nombre de personnes\nréduction = 10 pour cent du prix menus si personnes ≥ minimum + 5\nfrais livraison = 0 pour Bordeaux ou retrait sur place\nfrais livraison = 5 + distance km × 0,59 pour les autres villes\ntotal = prix menus − réduction + frais livraison')
    para(doc, 'Les montants sont arrondis à deux décimales. Cette règle se trouve dans calculLivraison, calculerReduction et calculerPrixTotal. Le tarif par kilomètre dépend de la ville choisie dans la table villes. Le retrait sur place met les frais à zéro et remplace l adresse par « Retrait sur place ».')
    add_file_table(doc, [
        ('src/Controller/Commandes/CommandesController.php', 'Contrôle le formulaire, vérifie menu ville date stock et droits de l utilisateur. Il contient les trois méthodes privées de calcul et déclenche l e mail de confirmation après création.'),
        ('src/Repository/CommandesRepository.php', 'Insère, lit et met à jour les commandes avec des requêtes préparées. updateClientOrder autorise une modification seulement si la commande appartient au client et est encore au statut reçue.'),
        ('src/Entity/Commande.php', 'Objet métier Commande. Il porte notamment prix_total, frais_livraison, reduction_appliquee, mode_reception, mode_paiement et statut.'),
        ('database/003_order_lifecycle.sql', 'Migration du cycle de vie : reçue, acceptée, payée, en préparation, en livraison, livrée, attente retour, terminée et annulée. Elle ajuste aussi le statut de paiement.'),
        ('database/INSTALLATION.md', 'Indique l ordre des migrations et la commande Heroku prévue pour appliquer la migration du cycle de commande sur JawsDB.')
    ])
    para(doc, 'Phrase jury : « Le calcul est volontairement fait côté PHP et non seulement en JavaScript, car le navigateur est modifiable. MySQL ne reçoit que le total recalculé à partir des données fiables de la base. »')

    heading(doc, '4 E mails PHPMailer SMTP et accusé de réception')
    para(doc, 'PHPMailer est une bibliothèque PHP qui envoie des e mails par SMTP. SMTP est le protocole utilisé par un serveur de messagerie pour expédier les messages. Dans le projet, MailService centralise la configuration et évite de dupliquer le code d envoi dans chaque contrôleur.')
    add_bullet(doc, 'mailer crée une instance PHPMailer, règle le jeu de caractères UTF 8, active SMTP et STARTTLS, puis configure l hôte, le port, le compte et le mot de passe depuis les variables d environnement.')
    add_bullet(doc, 'SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASSWORD, SMTP_FROM, SMTP_NAME et APP_URL sont des Config Vars Heroku ou des variables dans .env.local. Elles ne doivent jamais être écrites dans le code.')
    add_bullet(doc, 'send encapsule addAddress, le sujet, le corps HTML et le try catch. En cas d erreur, il écrit un log serveur sans afficher de secret au client.')
    add_bullet(doc, 'Le formulaire contact envoie à l équipe et envoie un accusé de réception au client. CONTACT_RECIPIENT est prioritaire. Si elle est absente, SMTP_FROM puis SMTP_USER sont utilisés comme destination de secours.')
    add_bullet(doc, 'addReplyTo définit l e mail du client comme adresse de réponse dans le mail reçu par l équipe. Ainsi, un clic sur Répondre ouvre le bon destinataire.')
    add_file_table(doc, [
        ('src/Service/MailService.php', 'Point unique des e mails : création de compte, réinitialisation, confirmation de commande, notification de contact, accusé de réception et réponse de l équipe.'),
        ('src/Controller/Contact/ContactController.php', 'Valide e mail titre et message, enregistre la demande puis appelle envoyerMailContact et envoyerAccuseReceptionContact.'),
        ('src/View/Contact/contact.php', 'Formulaire public de contact avec les champs email, titre et message. Il bénéficie du jeton CSRF injecté par Security.'),
        ('composer.json', 'Installe phpmailer/phpmailer. Composer installe ensuite la bibliothèque dans vendor lors du déploiement.')
    ])
    para(doc, 'Phrase jury : « L enregistrement en base est séparé de l e mail. Même si le serveur SMTP est indisponible, la demande de contact reste conservée et l équipe peut la traiter depuis la messagerie. »')

    heading(doc, '5 Messagerie contact équipe et client')
    para(doc, 'La messagerie est construite autour du message de contact initial. L équipe voit tous les messages. Le client ne voit que les conversations liées à son adresse e mail de session. Les réponses deviennent des échanges attachés au message initial, ce qui permet un affichage de type chat sans donner accès aux conversations des autres utilisateurs.')
    heading(doc, 'Flux côté visiteur puis équipe', 2)
    add_number(doc, 'Le client envoie le formulaire contact. ContactRepository crée une ligne dans messages_contact.')
    add_number(doc, 'ContactController déclenche la notification pour l équipe et l accusé de réception pour le client.')
    add_number(doc, 'Un employé ou administrateur ouvre la route messagerie_contact. AuthService::requireAdminEmploye protège la page.')
    add_number(doc, 'Le repository liste les messages, les trie avec les demandes non traitées en premier et affiche le contenu.')
    add_number(doc, 'L équipe répond. MailService expédie la réponse, ContactRepository ajoute un échange auteur equipe et marque le message traité.')
    add_number(doc, 'Le client ouvre ma_messagerie. La page filtre les conversations sur $_SESSION email puis affiche les bulles client et équipe.')
    add_number(doc, 'Quand le client répond, un échange auteur client est ajouté, le message repasse en attente et l équipe reçoit une notification e mail.')
    add_file_table(doc, [
        ('src/Repository/ContactRepository.php', 'Contient la persistance de contact. Il insère les demandes, détecte la présence de id_utilisateur pour rester compatible entre la base locale et JawsDB, liste les messages, marque traité ou en attente et stocke les échanges.'),
        ('src/Controller/Contact/ContactController.php', 'Contient trois parcours : index pour le formulaire public, inbox pour les employés et admins, clientInbox pour le client. Il valide la taille des messages et vérifie le propriétaire avant d accepter une réponse client.'),
        ('src/View/Contact/inbox.php', 'Boîte de réception équipe. Elle montre l objet, l expéditeur, le contenu, un badge traité ou à traiter, un champ de réponse et le bouton de traitement.'),
        ('src/View/Contact/client-inbox.php', 'Messagerie client. Elle affiche le message initial puis les échanges sous forme de bulles. Une bulle beige représente le client, une bulle grise représente Vite et Gourmand.'),
        ('public/assets/css/contact-inbox.css', 'Style commun de la messagerie. Il gère les cartes, les états, les bulles de chat, les zones de saisie et l adaptation mobile.'),
        ('src/View/Admin/espace_administrateur.php', 'Ajoute une carte Messages clients dans le tableau de bord administrateur.'),
        ('src/View/Authentication/espace_employe.php', 'Ajoute une carte Messages clients dans le tableau de bord employé.'),
        ('src/View/Client/espace_client.php', 'Ajoute la carte Ma messagerie dans le tableau de bord utilisateur.'),
        ('src/View/partials/menu.php', 'Ajoute les liens Ma messagerie pour le client et Messages clients pour les rôles équipe.'),
        ('public/index.php', 'Déclare les routes messagerie_contact et ma_messagerie vers le contrôleur de contact.')
    ])
    para(doc, 'Au premier accès à la messagerie, ContactRepository vérifie la structure MySQL et ajoute si besoin les colonnes est_traite, date_traitement, reponse ainsi que la table messages_contact_echanges. Cette compatibilité a été nécessaire car la structure de la base Heroku ne contenait pas exactement toutes les colonnes de la base locale. Les anciennes demandes restent conservées.')
    para(doc, 'Phrase jury : « La sécurité de la conversation ne repose pas sur un identifiant passé dans l URL. Avant de lire ou écrire, je compare l adresse du message avec l adresse stockée dans la session du client. »')

    heading(doc, '6 Avis et identité de leur auteur')
    para(doc, 'Un avis est lié à un utilisateur et à une commande. Pour éviter un nom inventé ou un simple identifiant, la requête validée joint avis avec utilisateurs sur id_utilisateur. Elle construit le libellé affiché avec CONCAT du prénom et du nom. La page accueil lit ensuite ce nom dans l entité Avis et fabrique une initiale pour l avatar.')
    add_file_table(doc, [
        ('src/Repository/AvisRepository.php', 'findByAvisValide joint avis et utilisateurs. La colonne nom_utilisateur correspond maintenant à TRIM CONCAT prenom espace nom.'),
        ('src/Entity/Avis.php', 'Représente un avis : note, commentaire, validation, commande, utilisateur et nom d affichage.'),
        ('src/Controller/Home/HomeController.php', 'Récupère les avis validés avant de rendre la page accueil.'),
        ('src/View/home/home.php', 'Affiche les avis validés, le nom de leur propriétaire, les étoiles et le commentaire échappé avec htmlspecialchars.')
    ])

    heading(doc, '7 Responsive design Bootstrap CSS et JavaScript')
    para(doc, 'Le responsive consiste à faire évoluer la mise en page selon la largeur d écran. Bootstrap apporte une grille en colonnes et des points de rupture comme col 12 col md 6 col lg 4. Ainsi, une carte occupe toute la largeur sur téléphone, deux colonnes sur tablette et trois colonnes sur ordinateur.')
    add_bullet(doc, 'Le menu est une navbar Bootstrap navbar expand lg. Sous 992 pixels, il devient un menu hamburger. Les liens s affichent dans un panneau vertical avec une zone tactile minimale de 44 pixels.')
    add_bullet(doc, 'Le logo utilise une largeur CSS clamp. clamp donne une taille minimale, une taille fluide et une taille maximale. Cela évite le logo étiré ou trop grand.')
    add_bullet(doc, 'Les tableaux utilisent table responsive et le script responsive tables. Sur téléphone, les en têtes sont masquées et chaque ligne devient une carte avec son libellé data label.')
    add_bullet(doc, 'Les formulaires de commande et de contact utilisent width 100 pour les champs et les boutons. Les cartes réduisent leurs marges internes sur petits écrans.')
    add_bullet(doc, 'Le footer ne possède plus de marge top Bootstrap mt 5. La règle CSS force la fin du contenu contre le footer afin de supprimer la bande blanche avant celui ci.')
    add_file_table(doc, [
        ('src/View/partials/menu.php', 'Structure HTML responsive de la navigation : marque, bouton hamburger Bootstrap, liens et menus déroulants selon le rôle.'),
        ('public/assets/css/menu/menu.css', 'Style du menu. Définit la taille fluide du logo, le hamburger blanc, le panneau mobile, les liens actifs et les menus déroulants.'),
        ('public/assets/css/responsive.css', 'Règles globales mobiles. Transforme les tableaux en cartes et garantit des contrôles tactiles d au moins 44 pixels.'),
        ('public/assets/js/responsive-tables.js', 'Ajoute les attributs data label aux cellules de tableau afin que le CSS mobile puisse afficher le nom de chaque colonne.'),
        ('public/assets/css/contact.css', 'Mise en page mobile de la page contact et des horaires.'),
        ('public/assets/css/contact-inbox.css', 'Mise en page de messagerie sur desktop, tablette et téléphone.'),
        ('public/assets/css/footer/footer.css', 'Style du footer et suppression de la marge blanche entre la dernière zone de contenu et le footer.')
    ])

    heading(doc, '8 Menus plats et base MySQL')
    para(doc, 'Un menu peut contenir plusieurs plats et un plat peut appartenir à plusieurs menus. Cette relation plusieurs à plusieurs est modélisée par une table pivot menus_plats. Cette solution est plus correcte que de mettre un seul id_menu dans la table plats car elle laisse un plat apparaître dans plusieurs compositions.')
    add_file_table(doc, [
        ('database/002_menu_plat.sql', 'Crée menus_plats avec id_menu_plat, id_menu et id_plat. Une requête INSERT SELECT récupère aussi les associations créées avec l ancien champ plats.id_menu.'),
        ('src/Service/Menus/MenusService.php', 'Centralise la logique de gestion des menus et de leur composition avec les plats.'),
        ('src/Repository/MenusRepository.php', 'Lit et persiste les données des menus ainsi que leurs associations métier.'),
        ('src/Repository/PlatsRepository.php', 'Gère les plats existants qui peuvent être sélectionnés lors de la création ou modification d un menu.')
    ])

    heading(doc, '9 MongoDB statistiques et historique')
    para(doc, 'MySQL est la source de vérité pour les commandes. MongoDB sert de projection analytique. Après une création ou modification importante, les commandes sont synchronisées vers une collection dédiée. Cela évite de modifier les tables transactionnelles pour les statistiques et illustre une séparation entre opérationnel et analytique.')
    add_file_table(doc, [
        ('src/Repository/CommandeStatutMongoRepository.php', 'Crée les collections commande_statut_historique et commande_analytics. Il enregistre les changements de statut, synchronise les commandes et lance des agrégations MongoDB pour les statistiques.'),
        ('src/Controller/Admin/AdminController.php', 'Synchronise les données puis lit les statistiques globales et par menu pour la page Statistiques.'),
        ('src/View/Admin/statistiques.php', 'Présente les filtres, indicateurs et informations statistiques au tableau de bord administrateur.')
    ])
    para(doc, 'Les variables MONGODB_URI et MONGODB_DATABASE sont réglées en local dans .env.local et en production dans Heroku Config Vars. Si MongoDB est indisponible, le repository renvoie des statistiques vides sans empêcher MySQL de continuer à gérer les commandes.')

    heading(doc, '10 Git GitHub Heroku et configuration')
    para(doc, 'Git conserve l historique des modifications sous forme de commits. La branche main correspond à la version publiée. Chaque correction a été vérifiée avec php -l avant un commit, puis poussée vers GitHub et Heroku. Heroku construit l application en lisant composer.json, installe les dépendances et démarre Apache avec le dossier public comme racine web.')
    add_bullet(doc, 'Commande de vérification syntaxique : php -l chemin du fichier.php.')
    add_bullet(doc, 'Commande Git de publication : git push origin main.')
    add_bullet(doc, 'Commande de déploiement : git push heroku main.')
    add_bullet(doc, 'Dans Heroku Settings Config Vars, configurer JAWSDB_URL ou les paramètres MySQL, les variables SMTP, APP_URL et les variables MongoDB.')
    add_bullet(doc, 'Ne pas afficher les valeurs des Config Vars durant la soutenance : présenter seulement leurs noms et leur rôle.')
    para(doc, 'Phrase jury : « Les secrets ne sont pas dans le dépôt. Le fichier .gitignore exclut les fichiers .env et Heroku injecte les valeurs en production via Config Vars. Cela permet de changer de serveur sans modifier le code source. »')

    heading(doc, '11 Parcours de démonstration conseillé')
    add_number(doc, 'Montrer la page accueil et la navigation responsive avec la barre hamburger sur un écran étroit.')
    add_number(doc, 'Afficher la liste des menus, un détail de menu, puis expliquer la composition menu plats.')
    add_number(doc, 'Se connecter comme client, créer une commande et commenter le calcul serveur du total et des frais de livraison.')
    add_number(doc, 'Montrer Mes commandes, les statuts, la possibilité de modifier ou annuler avant acceptation et l historique MongoDB.')
    add_number(doc, 'Envoyer un message depuis Contact, puis se connecter comme employé ou administrateur et ouvrir Messages clients.')
    add_number(doc, 'Répondre au client, marquer la demande traitée, puis revenir dans Ma messagerie client pour montrer la bulle de réponse et la relance.')
    add_number(doc, 'Montrer la gestion des avis et souligner que les avis validés affichent réellement le prénom et le nom de leur auteur.')
    add_number(doc, 'Terminer par Statistiques, GitHub, Heroku et la protection des secrets.')

    heading(doc, '12 Réponses courtes aux questions fréquentes')
    para(doc, 'Pourquoi PDO ? PDO permet des requêtes préparées avec des paramètres séparés de la requête SQL. Cela réduit le risque d injection SQL et rend le code compatible avec une configuration de base propre.')
    para(doc, 'Pourquoi PHPMailer plutôt que mail ? PHPMailer gère proprement SMTP, TLS, les destinataires, les e mails HTML et les erreurs. La configuration ne dépend pas de la fonction mail du serveur.')
    para(doc, 'Pourquoi MySQL et MongoDB ? MySQL gère les données liées et transactionnelles comme les utilisateurs, commandes et menus. MongoDB est utilisé pour l historique et les agrégations statistiques, ce qui répond au besoin analytique sans remplacer la base métier.')
    para(doc, 'Comment le responsive est vérifié ? Je vérifie les points de rupture téléphone tablette ordinateur, les boutons tactiles, les tableaux, les formulaires, le menu et le footer. Bootstrap fournit la grille, puis du CSS spécifique ajuste les composants du projet.')
    para(doc, 'Comment la messagerie protège les données ? Les employés et administrateurs passent par une protection de rôle. Pour le client, les conversations sont filtrées sur l e mail de sa session et chaque réponse vérifie que le message lui appartient avant insertion.')

    footer = section.footer.paragraphs[0]
    footer.alignment = WD_ALIGN_PARAGRAPH.CENTER
    footer.add_run('Vite et Gourmand  Guide technique de soutenance')
    doc.save(OUTPUT)


if __name__ == '__main__':
    build()
