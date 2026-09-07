from pathlib import Path
from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import cm
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak,
    Image, KeepTogether, Flowable,
)


ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / "output" / "pdf"
OUT.mkdir(parents=True, exist_ok=True)
MCD = Path(r"D:\PROJETS\VITE ET GOURMAND\MCD\mcd_vite_et_gourmand.png.png")

GOLD = colors.HexColor("#B87829")
DARK = colors.HexColor("#24170F")
BEIGE = colors.HexColor("#F5F0EA")
PALE = colors.HexColor("#FFF9F2")
GREY = colors.HexColor("#606872")
LINE = colors.HexColor("#D8D0C8")
WHITE = colors.white


def styles():
    base = getSampleStyleSheet()
    return {
        "title": ParagraphStyle("title", parent=base["Title"], fontName="Helvetica-Bold", fontSize=25,
                                leading=31, textColor=colors.black, alignment=TA_CENTER, spaceAfter=12),
        "subtitle": ParagraphStyle("subtitle", parent=base["Normal"], fontName="Helvetica", fontSize=12,
                                   leading=17, textColor=GREY, alignment=TA_CENTER, spaceAfter=22),
        "h1": ParagraphStyle("h1", parent=base["Heading1"], fontName="Helvetica-Bold", fontSize=17,
                              leading=22, textColor=colors.black, spaceBefore=16, spaceAfter=9),
        "h2": ParagraphStyle("h2", parent=base["Heading2"], fontName="Helvetica-Bold", fontSize=12,
                              leading=16, textColor=colors.black, spaceBefore=10, spaceAfter=5),
        "body": ParagraphStyle("body", parent=base["BodyText"], fontName="Helvetica", fontSize=10,
                                leading=15, textColor=colors.black, spaceAfter=7),
        "small": ParagraphStyle("small", parent=base["BodyText"], fontName="Helvetica", fontSize=8.5,
                                 leading=11, textColor=GREY),
        "cover": ParagraphStyle("cover", parent=base["BodyText"], fontName="Helvetica", fontSize=11,
                                 leading=16, alignment=TA_CENTER, textColor=GREY),
        "table": ParagraphStyle("table", parent=base["BodyText"], fontName="Helvetica", fontSize=8.5,
                                 leading=11, textColor=colors.black),
        "tableHead": ParagraphStyle("tableHead", parent=base["BodyText"], fontName="Helvetica-Bold", fontSize=8.5,
                                     leading=11, textColor=WHITE),
    }


S = styles()

FRENCH_FIXES = {
    "administrateur": "administrateur", "administrateurs": "administrateurs",
    "accees": "accès", "acces": "accès", "accentue": "accentué", "acceptee": "acceptée",
    "accueil": "accueil", "annulee": "annulée", "apres": "après", "aout": "août",
    "caracteres": "caractères", "chiffre d affaires": "chiffre d'affaires", "commande recue": "commande reçue",
    "commandes recue": "commandes reçues", "comptes employes": "comptes employés",
    "comptes de demonstration": "comptes de démonstration", "controle": "contrôle",
    "crees": "créés", "cree": "créé", "creer": "créer", "creee": "créée",
    "deploiement": "déploiement", "depose": "dépose", "deposer": "déposer",
    "developpeur": "développeur", "donnees": "données", "detaill": "détaill",
    "e-mails": "e-mails", "echange": "échange", "ecrans": "écrans", "employes": "employés",
    "employe": "employé", "encodees": "encodées", "equipe": "équipe", "etapes": "étapes",
    "fidelite": "fidélité", "gerees": "gérées", "gerer": "gérer", "hierarchie": "hiérarchie",
    "identite": "identité", "integre": "intégré", "livree": "livrée", "maitrise": "maîtrise",
    "metier": "métier", "necessaires": "nécessaires", "necessaire": "nécessaire",
    "periode": "période", "prepares": "préparés", "preselectionne": "présélectionné",
    "propriete": "propriété", "qualite": "qualité", "regles": "règles", "regle": "règle",
    "realisees": "réalisées", "reception": "réception", "requetes": "requêtes", "separe": "sépare",
    "securite": "sécurité", "statistiques": "statistiques", "terminee": "terminée",
    "terminaison": "terminaison", "utilisation": "utilisation", "verifies": "vérifiés",
    "verification": "vérification", "verifier": "vérifier", "versionnees": "versionnées",
}


def P(text, style="body"):
    for old, new in sorted(FRENCH_FIXES.items(), key=lambda item: len(item[0]), reverse=True):
        text = text.replace(old, new)
    return Paragraph(text, S[style])


def footer(canvas, doc):
    canvas.saveState()
    canvas.setStrokeColor(LINE)
    canvas.setLineWidth(.45)
    canvas.line(doc.leftMargin, 1.35 * cm, A4[0] - doc.rightMargin, 1.35 * cm)
    canvas.setFont("Helvetica", 8)
    canvas.setFillColor(GREY)
    canvas.drawString(doc.leftMargin, .85 * cm, "Vite & Gourmand - Livrable ECF")
    canvas.drawRightString(A4[0] - doc.rightMargin, .85 * cm, f"Page {doc.page}")
    canvas.restoreState()


def cover(title, subtitle, date_label="Dossier de remise au jury"):
    data = [[P("VITE & GOURMAND", "h2")], [P(title, "title")], [P(subtitle, "subtitle")], [Spacer(1, 3.2 * cm)],
            [P(date_label, "cover")], [Spacer(1, .3 * cm)], [P("Titre professionnel Developpeur Web et Web Mobile", "cover")]]
    table = Table(data, colWidths=[16 * cm], hAlign="CENTER")
    table.setStyle(TableStyle([
        ("BACKGROUND", (0, 0), (-1, 0), GOLD),
        ("TEXTCOLOR", (0, 0), (-1, 0), WHITE),
        ("ALIGN", (0, 0), (-1, -1), "CENTER"),
        ("BOX", (0, 0), (-1, -1), .8, GOLD),
        ("TOPPADDING", (0, 0), (-1, -1), 14),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 14),
    ]))
    return [Spacer(1, 4.1 * cm), table, PageBreak()]


def data_table(headers, rows, widths):
    content = [[P(h, "tableHead") for h in headers]] + [[P(c, "table") for c in row] for row in rows]
    table = Table(content, colWidths=widths, repeatRows=1, hAlign="LEFT")
    style = [
        ("BACKGROUND", (0, 0), (-1, 0), DARK), ("GRID", (0, 0), (-1, -1), .35, LINE),
        ("VALIGN", (0, 0), (-1, -1), "MIDDLE"), ("LEFTPADDING", (0, 0), (-1, -1), 7),
        ("RIGHTPADDING", (0, 0), (-1, -1), 7), ("TOPPADDING", (0, 0), (-1, -1), 7),
        ("BOTTOMPADDING", (0, 0), (-1, -1), 7),
    ]
    for row in range(1, len(content)):
        if row % 2 == 0:
            style.append(("BACKGROUND", (0, row), (-1, row), BEIGE))
    table.setStyle(TableStyle(style))
    return table


class Wireframe(Flowable):
    def __init__(self, title, mobile=False):
        super().__init__()
        self.title, self.mobile = title, mobile
        self.width = 5.3 * cm if mobile else 15.5 * cm
        self.height = 8.5 * cm if mobile else 6.8 * cm

    def draw(self):
        c = self.canv
        w, h = self.width, self.height
        c.setStrokeColor(DARK); c.setLineWidth(1.2); c.roundRect(0, 0, w, h, 8, stroke=1, fill=0)
        c.setFillColor(GOLD); c.roundRect(0.12*cm, h-.9*cm, w-.24*cm, .72*cm, 5, stroke=0, fill=1)
        c.setFillColor(WHITE); c.setFont("Helvetica-Bold", 7); c.drawString(.32*cm, h-.62*cm, "Vite & Gourmand")
        if self.mobile:
            c.setFillColor(WHITE); c.setFont("Helvetica-Bold", 10); c.drawRightString(w-.32*cm, h-.63*cm, "=")
        c.setFillColor(BEIGE); c.roundRect(.25*cm, h-3.25*cm, w-.5*cm, 2.05*cm, 5, stroke=0, fill=1)
        c.setFillColor(DARK); c.setFont("Helvetica-Bold", 8); c.drawCentredString(w/2, h-2.2*cm, self.title)
        for i in range(3 if self.mobile else 4):
            x = .35*cm if self.mobile else .45*cm + i*3.72*cm
            y = .65*cm if self.mobile else .75*cm
            cw = w-.7*cm if self.mobile else 3.25*cm
            if self.mobile and i > 0:
                y = .65*cm + i*1.15*cm
                cw = w-.7*cm
            c.setFillColor(PALE); c.roundRect(x, y, cw, .85*cm, 5, stroke=0, fill=1)
            c.setStrokeColor(LINE); c.roundRect(x, y, cw, .85*cm, 5, stroke=1, fill=0)
            c.setFillColor(GREY); c.setFont("Helvetica", 6.5); c.drawCentredString(x+cw/2, y+.49*cm, "Contenu / action")


def manual():
    story = cover("Manuel utilisateur", "Parcours de demonstration pour les profils client, employe et administrateur")
    story += [P("Objectif", "h1"), P("Ce manuel permet au jury de verifier les parcours essentiels de l application de traiteur Vite & Gourmand. L application est accessible depuis le lien de production indique dans le depot GitHub. Les comptes de demonstration doivent etre crees et verifies avant le passage devant le jury."),
              P("Preparation de la demonstration", "h2"),
              data_table(["Profil", "Compte a preparer", "Verification avant passage"], [
                  ["Client", "Un compte client actif", "Connexion, une commande recue et une commande terminee."],
                  ["Employe", "Un compte employe actif", "Acces aux commandes, menus, plats, horaires et avis."],
                  ["Administrateur", "Un compte administrateur actif", "Acces aux employes et a la page Statistiques."],
              ], [3.2*cm, 5*cm, 8*cm]),
              P("Les mots de passe ne sont pas publies dans le depot. Ils sont crees par le responsable de la demonstration et communiques au jury le jour J.", "small"),
              P("Parcours visiteur", "h1"),
              P("1. Ouvrir la page Accueil. Verifier la presentation de Vite & Gourmand, l equipe, les avis valides, les horaires et les liens legaux du pied de page."),
              P("2. Ouvrir Menus. Tester les filtres prix, theme, regime et nombre minimal de personnes. Les resultats se mettent a jour sans rechargement."),
              P("3. Ouvrir le detail d un menu. Verifier la description, la composition en plats, les allergenes, le stock et les conditions. Le bouton Commander demande une connexion si necessaire."),
              P("Parcours client", "h1"),
              P("1. Creer un compte ou se connecter. Le mot de passe respecte la regle de dix caracteres avec majuscule, minuscule, chiffre et caractere special."),
              P("2. Depuis le detail d un menu, ouvrir Commander. Les coordonnees sont pre-remplies. Saisir la prestation, la date, l heure et le nombre de personnes."),
              P("3. Verifier le detail du prix : minimum du menu, frais de livraison hors Bordeaux et remise de 10 % a partir de cinq personnes supplementaires."),
              P("4. Valider. La commande apparait dans Mes commandes avec le statut recue. Elle peut etre modifiee ou annulee avant acceptation par l equipe."),
              P("5. Apres le statut terminee, ouvrir la commande et deposer un avis de 1 a 5 avec un commentaire."),
              PageBreak(),
              P("Parcours employe", "h1"),
              P("1. Ouvrir le tableau employe. Acceder aux commandes, a la gestion des menus et plats, aux horaires et aux avis."),
              P("2. Dans les commandes, utiliser les filtres de statut, reference et client. Ouvrir le detail avec le bouton oeil."),
              P("3. Faire progresser une commande en respectant son cycle : recue, acceptee, payee, en preparation, en livraison, livree, attente retour, terminee. Chaque changement est conserve dans l historique."),
              P("4. Valider ou refuser les avis avant leur affichage sur l accueil."),
              P("Parcours administrateur", "h1"),
              P("1. Ouvrir le tableau administrateur. Les cartes donnent acces aux fonctions de gestion."),
              P("2. Creer un compte employe, puis ouvrir la liste des employes. Desactiver un compte : il ne peut plus se connecter tant qu il n est pas reactive."),
              P("3. Ouvrir Statistiques. Filtrer par menu et periode, puis consulter le nombre de commandes et le chiffre d affaires issus de MongoDB."),
              P("Points de controle", "h1"),
              data_table(["Controle", "Resultat attendu"], [
                  ["Responsive", "Sur mobile et tablette, les formulaires restent lisibles et les tableaux se defilent dans leur conteneur."],
                  ["Securite", "Les actions sensibles refusent les utilisateurs non autorises, les formulaires POST utilisent un jeton CSRF et les comptes inactifs sont bloques."],
                  ["E-mails", "Les e-mails de bienvenue, reinitialisation, commande et avis dependent des variables SMTP configurees dans Heroku."],
              ], [4*cm, 12.2*cm])]
    build(OUT / "manuel_utilisateur.pdf", story)


def charter():
    story = cover("Charte graphique", "Identite visuelle, palette, typographie et wireframes de Vite & Gourmand")
    story += [P("Identite visuelle", "h1"), P("L interface cherche une ambiance chaleureuse et gastronomique. Le brun profond structure les zones de confiance comme le pied de page. L ocre apporte l accent sur les boutons et les elements de marque. Les fonds ivoire laissent les contenus respirer."),
              P("Palette de couleurs", "h1"),
              data_table(["Usage", "Couleur", "Code"], [
                  ["Accent principal", "Ocre", "#B87829"], ["Fond sombre", "Brun profond", "#24170F"],
                  ["Fond secondaire", "Ivoire", "#F5F0EA"], ["Surface claire", "Blanc chaud", "#FFF9F2"],
                  ["Texte secondaire", "Gris ardoise", "#606872"],
              ], [5.1*cm, 5.1*cm, 5.9*cm]),
              P("Typographie et composants", "h1"),
              data_table(["Element", "Regle"], [
                  ["Titres", "Helvetica ou sans-serif systeme, gras, noir. Hierarchie nette de 17 a 25 points sur les documents et tailles Bootstrap equivalentes dans l application."],
                  ["Texte", "Sans-serif reguliere, contrastes lisibles et espaces suffisants. Les accents francais sont encodes en UTF-8."],
                  ["Boutons", "Fond ocre pour l action principale, contour ou fond clair pour les actions secondaires. Libelles courts et explicites."],
                  ["Cartes", "Fond blanc chaud, angles arrondis, ombre legere et marges coherentes."],
                  ["Accessibilite", "Libelles associes aux champs, couleurs completees par du texte et composants Bootstrap adaptes aux petits ecrans."],
              ], [4.1*cm, 12*cm]),
              PageBreak(), P("Wireframes bureautiques", "h1"),
              P("Les wireframes ci-dessous fixent la structure attendue des ecrans principaux. Les maquettes de haute fidelite realisees dans Canva peuvent etre ajoutees en annexe avant le rendu final."),
              Wireframe("Accueil", mobile=False), Spacer(1, .35*cm), Wireframe("Liste des menus", mobile=False), Spacer(1, .35*cm), Wireframe("Tableau administrateur", mobile=False),
              PageBreak(), P("Wireframes mobiles", "h1"),
              P("Sur mobile, la navigation devient un menu compact, les cartes passent sur une colonne et les tableaux restent consultables dans une zone de defilement horizontal."),
              Table([[Wireframe("Accueil", True), Wireframe("Commande", True), Wireframe("Tableau de bord", True)]], colWidths=[5.3*cm]*3, hAlign="CENTER")]
    build(OUT / "charte_graphique.pdf", story)


def technical():
    story = cover("Documentation technique", "Architecture, donnees, securite, gestion de projet et deploiement")
    story += [P("Vue d ensemble", "h1"), P("Vite & Gourmand est une application PHP pour un traiteur. Elle separe les donnees transactionnelles MySQL des donnees analytiques MongoDB. Le site propose un espace public et trois profils authentifies : client, employe et administrateur."),
              P("Choix techniques", "h1"),
              data_table(["Couche", "Choix", "Justification"], [
                  ["Front-end", "HTML5, CSS, Bootstrap 5, JavaScript", "Interface responsive, composants coherents et filtrage dynamique des menus."],
                  ["Back-end", "PHP 8, PDO, Composer", "Application serveur lisible, requetes preparees et dependances gerees."],
                  ["Relationnel", "MySQL", "Source de verite pour utilisateurs, menus, plats, commandes, avis et horaires."],
                  ["NoSQL", "MongoDB", "Projection analytique des commandes pour comparaisons et chiffre d affaires par menu."],
                  ["Deploiement", "Heroku", "Application en ligne, variables sensibles en Config Vars et livraison reproductible."],
              ], [3*cm, 4.2*cm, 8.9*cm]),
              P("Architecture MVC", "h1"), P("Les routes entrent par public/index.php. Les controllers orchestrent les cas d utilisation. Les services regroupent les regles metier. Les repositories centralisent l acces PDO ou MongoDB. Les entites representent les donnees et les vues rendent le HTML avec Bootstrap."),
              data_table(["Dossier", "Responsabilite"], [
                  ["src/Controller", "Reception des routes et controle des droits."], ["src/Service", "Regles de commande, authentification, e-mails et calculs."], ["src/Repository", "Acces aux donnees MySQL et MongoDB."], ["src/Entity", "Objets metier."], ["src/View", "Ecrans PHP et fragments de mise en page."], ["database", "Migrations SQL versionnees."],
              ], [5*cm, 11.1*cm]),
              PageBreak(),
              P("Modele de donnees", "h1"), P("Le MCD fourni couvre les utilisateurs, roles, menus, plats, allergenes, commandes, avis, horaires, messages de contact et documents legaux. Les tables de liaison menus_plats et plats_allergenes portent les relations plusieurs a plusieurs."),
              Image(str(MCD), width=16*cm, height=18*cm) if MCD.exists() else P("Le MCD doit etre joint ici avant la remise finale."),
              P("Diagramme de cas d utilisation", "h1"),
              data_table(["Acteur", "Cas d utilisation"], [
                  ["Visiteur", "Consulter et filtrer les menus, voir le detail, contacter l entreprise, creer un compte."],
                  ["Client", "Commander, suivre, modifier ou annuler avant acceptation, deposer un avis apres terminaison."],
                  ["Employe", "Gerer menus, plats, horaires, commandes et moderer les avis."],
                  ["Administrateur", "Toutes les fonctions employe, comptes employes et statistiques MongoDB."],
              ], [4*cm, 12.1*cm]),
              P("Sequence principale de commande", "h1"),
              data_table(["Etape", "Echange"], [
                  ["1", "Client -> Vue : ouvre le formulaire avec le menu preselectionne."],
                  ["2", "Vue -> Controller -> Service : valide les donnees, le minimum de personnes, la livraison et la remise."],
                  ["3", "Service -> Repository MySQL : cree la commande au statut recue."],
                  ["4", "Service -> MailService : envoie la confirmation si SMTP est configure."],
                  ["5", "Employe -> Controller : fait avancer les statuts autorises."],
                  ["6", "Controller -> MongoDB : synchronise la projection analytique."],
                  ["7", "Client -> Avis : depose une note une fois la commande terminee."],
              ], [2*cm, 14.1*cm]),
              P("Securite et qualite", "h1"),
              data_table(["Mesure", "Application"], [
                  ["Authentification", "Mots de passe haches avec password_hash et verification avec password_verify."],
                  ["Autorisation", "Verification des roles et de la propriete d une commande avant les actions."],
                  ["CSRF", "Jeton controle sur les formulaires POST sensibles."],
                  ["Comptes", "Un compte desactive est refuse a la connexion."],
                  ["Secrets", "Variables .env locales et Heroku Config Vars ; aucun secret ne doit etre commit."],
                  ["Qualite", "Tests manuels par parcours et verification responsive mobile, tablette et bureau."],
              ], [3.5*cm, 12.6*cm]),
              P("Gestion de projet et Git", "h1"), P("Le projet est versionne avec une branche develop pour les integrations et une branche main pour les versions testees et deployees. Chaque evolution est committee, testee puis fusionnee. Les tests manuels et le guide de demonstration sont conserves dans docs/."),
              P("Deploiement Heroku", "h1"), P("1. Configurer DB, MongoDB, SMTP et APP_URL dans Settings > Config Vars. 2. Installer les dependances Composer via le buildpack PHP. 3. Pousser main sur le remote Heroku. 4. Executer les migrations necessaires, notamment scripts/migrate_order_lifecycle.php. 5. Verifier accueil, connexion, commande, e-mail et statistiques en production."),
              P("Checklist finale", "h1"), P("Completer les comptes de demonstration, verifier les e-mails SMTP, joindre les maquettes Canva originales si disponibles et renseigner les liens GitHub, Heroku et outil de gestion de projet dans la copie a rendre.")]
    build(OUT / "documentation_technique.pdf", story)


def build(path, story):
    document = SimpleDocTemplate(str(path), pagesize=A4, leftMargin=2*cm, rightMargin=2*cm,
                                 topMargin=1.7*cm, bottomMargin=2*cm, title=path.stem.replace("_", " "))
    document.build(story, onFirstPage=footer, onLaterPages=footer)


if __name__ == "__main__":
    manual()
    charter()
    technical()
    print("PDFs generated in", OUT)
