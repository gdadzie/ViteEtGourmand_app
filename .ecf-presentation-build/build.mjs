import fs from "node:fs/promises";
import path from "node:path";
import { pathToFileURL } from "node:url";
import { Presentation, PresentationFile } from "@oai/artifact-tool";

const skillDir = "C:/Users/EYRAM/.codex/plugins/cache/openai-primary-runtime/presentations/26.904.11930/skills/presentations";
const workspaceDir = "C:/Dev/wamp64/www/ViteEtGourmand_app";
const buildDir = path.join(workspaceDir, ".ecf-presentation-build");
const finalDir = path.join(workspaceDir, "output", "presentation");
const finalPath = path.join(finalDir, "Vite_et_Gourmand_Soutenance_ECF.pptx");
const candidatePath = path.join(buildDir, "candidate.pptx");
const runtimePython = "C:/Users/EYRAM/.cache/codex-runtimes/codex-primary-runtime/dependencies/python/python.exe";
const assets = {
  home: "C:/Users/EYRAM/AppData/Local/Packages/OpenAI.Codex_2p2nqsd0c76g0/AC/INetCache/DHWMU272/PAGE_ACCUEIL_-_Accueil[1].PNG",
  mobile: "C:/Users/EYRAM/AppData/Local/Packages/OpenAI.Codex_2p2nqsd0c76g0/AC/INetCache/STAZLQM3/PAGE_ACCUEIL_-_Accueil_mobile[1].PNG",
  detail: "C:/Users/EYRAM/AppData/Local/Packages/OpenAI.Codex_2p2nqsd0c76g0/AC/INetCache/GG1X9NVS/PAGE_ACCUEIL_-_Detail_menu[1].PNG",
  admin: "C:/Users/EYRAM/AppData/Local/Packages/OpenAI.Codex_2p2nqsd0c76g0/AC/INetCache/5819WTYY/PAGE_ACCUEIL_-_Espace_administrateur[1].PNG",
  menus: "C:/Users/EYRAM/AppData/Local/Packages/OpenAI.Codex_2p2nqsd0c76g0/AC/INetCache/DHWMU272/PAGE_ACCUEIL_-_Liste_des_menus[1].PNG",
  mcd: "D:/PROJETS/VITE ET GOURMAND/MCD/mcd_vite_et_gourmand.png.png",
};

const colors = { dark: "#24170F", gold: "#B87829", cream: "#F5F0EA", white: "#FFFFFF", ink: "#181410", grey: "#65707C", line: "#D9D0C6" };
const font = "Arial";
const imageBytes = async (file) => new Uint8Array(await fs.readFile(file));
const assetBytes = new Map();
for (const file of Object.values(assets)) assetBytes.set(file, await imageBytes(file));

const { finalizePresentation } = await import(pathToFileURL(path.join(skillDir, "container_tools", "artifact_tool_utils.mjs")).href);

await fs.mkdir(buildDir, { recursive: true });
await fs.mkdir(finalDir, { recursive: true });

const presentation = Presentation.create({ slideSize: { width: 1280, height: 720 } });

function textbox(slide, text, x, y, w, h, opts = {}) {
  const shape = slide.shapes.add({ geometry: "textbox", position: { left: x, top: y, width: w, height: h }, fill: "none", line: { fill: "none", width: 0 } });
  shape.text = text;
  shape.text.style = { typeface: font, fontSize: opts.size ?? 22, bold: opts.bold ?? false, color: opts.color ?? colors.ink, autoFit: "shrinkText", verticalAlignment: opts.valign ?? "top", alignment: opts.align ?? "left", marginLeft: 0, marginRight: 0, marginTop: 0, marginBottom: 0 };
  return shape;
}

function rect(slide, x, y, w, h, fill, radius = 0) {
  return slide.shapes.add({ geometry: radius ? "roundRect" : "rect", position: { left: x, top: y, width: w, height: h }, fill, line: { fill: "none", width: 0 } });
}

function image(slide, file, alt, x, y, w, h, fit = "cover") {
  return slide.images.add({ blob: assetBytes.get(file), contentType: "image/png", alt, fit, position: { left: x, top: y, width: w, height: h }, geometry: "roundRect", borderRadius: "rounded-xl" });
}

function label(slide, n, text = "Vite & Gourmand") {
  textbox(slide, String(n).padStart(2, "0"), 72, 670, 36, 22, { size: 11, bold: true, color: colors.gold });
  textbox(slide, text, 111, 670, 250, 22, { size: 11, color: colors.grey });
}

function title(slide, text, subtitle = "") {
  textbox(slide, text, 72, 52, 1000, 50, { size: 34, bold: true });
  if (subtitle) textbox(slide, subtitle, 74, 109, 820, 34, { size: 17, color: colors.grey });
}

function addNotes(slide, text) { slide.speakerNotes.textFrame.setText(text); }

// 1. Cover
{
  const slide = presentation.slides.add();
  slide.background.fill = colors.dark;
  image(slide, assets.home, "Capture de la page d accueil de Vite & Gourmand", 640, 0, 640, 720, "cover");
  rect(slide, 0, 0, 680, 720, colors.dark);
  textbox(slide, "Vite & Gourmand", 76, 144, 500, 58, { size: 45, bold: true, color: colors.white });
  textbox(slide, "Application web de gestion et de commande pour un traiteur", 80, 219, 450, 80, { size: 24, color: "#F4E9DB" });
  textbox(slide, "Soutenance ECF\nDéveloppeur Web et Web Mobile", 80, 555, 430, 60, { size: 18, color: colors.white });
  addNotes(slide, "Présentation du projet Vite & Gourmand. Visuel : maquette fournie par le candidat.");
}

// 2. Context
{
  const slide = presentation.slides.add(); slide.background.fill = colors.cream;
  title(slide, "Le besoin métier", "Rendre les menus visibles et simplifier la gestion quotidienne du traiteur");
  textbox(slide, "Vite & Gourmand avait besoin d'un site qui permette aux visiteurs de découvrir les menus et de commander en ligne.", 72, 210, 480, 120, { size: 25, bold: true });
  textbox(slide, "L'équipe devait aussi gérer les commandes, les menus, les horaires, les avis et les comptes employés depuis des espaces sécurisés.", 72, 366, 460, 110, { size: 22, color: colors.grey });
  rect(slide, 670, 190, 6, 300, colors.gold);
  textbox(slide, "4 profils", 720, 195, 280, 46, { size: 28, bold: true, color: colors.gold });
  textbox(slide, "Visiteur\nClient\nEmployé\nAdministrateur", 720, 270, 360, 230, { size: 28, bold: true });
  label(slide, 2); addNotes(slide, "Le cahier des charges a conduit à identifier quatre profils et leurs droits.");
}

// 3. Experience
{
  const slide = presentation.slides.add(); slide.background.fill = colors.white;
  title(slide, "Une expérience pensée pour tous les écrans", "Bootstrap structure les pages et adapte les contenus au mobile, à la tablette et au bureau");
  image(slide, assets.mobile, "Maquette mobile de la page d accueil", 714, 142, 494, 438, "contain");
  textbox(slide, "Navigation compacte sur mobile", 72, 212, 510, 36, { size: 25, bold: true });
  textbox(slide, "Les formulaires prennent la largeur disponible. Les cartes se réorganisent sur une colonne. Les tableaux restent lisibles dans leur zone de défilement.", 72, 270, 520, 135, { size: 22, color: colors.grey });
  textbox(slide, "Objectif : préserver la lecture et les actions essentielles sur un iPhone comme sur un écran large.", 72, 456, 510, 72, { size: 20, bold: true, color: colors.gold });
  label(slide, 3); addNotes(slide, "Visuel : maquette mobile fournie par le candidat. Aucun chiffre de performance n'est affirmé.");
}

// 4. Menus
{
  const slide = presentation.slides.add(); slide.background.fill = colors.white;
  title(slide, "Consulter et filtrer les menus", "Une vue publique, claire et accessible avant connexion");
  image(slide, assets.menus, "Maquette de la liste des menus", 690, 140, 500, 460, "contain");
  textbox(slide, "Chaque menu présente", 72, 194, 470, 38, { size: 26, bold: true });
  textbox(slide, "Le titre, la description, le prix, le nombre minimal de personnes et un accès au détail.", 72, 252, 500, 76, { size: 22, color: colors.grey });
  textbox(slide, "Filtres disponibles", 72, 365, 470, 35, { size: 26, bold: true, color: colors.gold });
  textbox(slide, "Prix maximum ou fourchette de prix\nThème et régime\nNombre minimal de personnes", 72, 420, 505, 105, { size: 21 });
  label(slide, 4); addNotes(slide, "Visuel : maquette de liste des menus fournie. Les filtres sont actualisés sans rechargement de page.");
}

// 5. Order
{
  const slide = presentation.slides.add(); slide.background.fill = colors.cream;
  title(slide, "Commander un menu", "Le formulaire reprend les informations du compte et applique les règles métier");
  image(slide, assets.detail, "Maquette du détail d un menu", 715, 140, 460, 465, "contain");
  textbox(slide, "Une commande vérifie", 72, 192, 530, 36, { size: 26, bold: true });
  textbox(slide, "Le nombre minimal de personnes, les frais de livraison, le stock et la remise de 10 % lorsque le client commande cinq personnes de plus que le minimum.", 72, 248, 525, 130, { size: 21, color: colors.grey });
  textbox(slide, "Après validation", 72, 425, 530, 36, { size: 26, bold: true, color: colors.gold });
  textbox(slide, "La commande reçoit le statut « reçue ». Le client peut ensuite la modifier ou l'annuler avant son acceptation par l'équipe.", 72, 480, 520, 86, { size: 21 });
  label(slide, 5); addNotes(slide, "Visuel : maquette de détail de menu fournie. Les règles décrites sont contrôlées côté serveur.");
}

// 6. Back office
{
  const slide = presentation.slides.add(); slide.background.fill = colors.white;
  title(slide, "Espaces employés et administrateurs", "Des fonctions séparées selon le rôle de l'utilisateur");
  image(slide, assets.admin, "Maquette de l espace administrateur", 610, 156, 590, 420, "contain");
  textbox(slide, "Employé", 72, 190, 300, 35, { size: 25, bold: true, color: colors.gold });
  textbox(slide, "Suit les commandes, gère les menus, les plats, les horaires et modère les avis.", 72, 240, 400, 85, { size: 21, color: colors.grey });
  textbox(slide, "Administrateur", 72, 380, 360, 35, { size: 25, bold: true, color: colors.gold });
  textbox(slide, "Possède les droits employés. Il gère aussi les comptes employés et consulte les statistiques.", 72, 430, 420, 92, { size: 21, color: colors.grey });
  label(slide, 6); addNotes(slide, "Visuel : maquette du tableau administrateur fournie par le candidat.");
}

// 7. MVC
{
  const slide = presentation.slides.add(); slide.background.fill = colors.dark;
  textbox(slide, "Architecture MVC", 72, 55, 700, 48, { size: 34, bold: true, color: colors.white });
  textbox(slide, "Le code sépare le routage, les règles métier, l'accès aux données et l'affichage.", 74, 110, 780, 35, { size: 17, color: "#EBDDCF" });
  const cols = [72, 324, 576, 828];
  const data = [
    ["Route", "public/index.php", "Reçoit la page demandée"],
    ["Controller", "src/Controller", "Contrôle les droits et orchestre"],
    ["Service", "src/Service", "Applique les règles métier"],
    ["Repository", "src/Repository", "Accède à MySQL et MongoDB"],
  ];
  data.forEach((d, i) => {
    rect(slide, cols[i], 255, 205, 170, i % 2 ? colors.gold : "#3B2A1C", 18);
    textbox(slide, d[0], cols[i] + 20, 282, 164, 34, { size: 23, bold: true, color: colors.white });
    textbox(slide, d[1], cols[i] + 20, 330, 164, 24, { size: 14, bold: true, color: "#F8E8D7" });
    textbox(slide, d[2], cols[i] + 20, 370, 164, 48, { size: 15, color: colors.white });
  });
  textbox(slide, "Les vues dans src/View rendent les pages PHP avec Bootstrap. Les entités représentent les objets métier.", 72, 535, 920, 42, { size: 21, color: colors.white });
  label(slide, 7, "Architecture applicative"); addNotes(slide, "Architecture progressive MVC du dépôt. Les chemins de dossiers sont vérifiables dans le code source.");
}

// 8. data
{
  const slide = presentation.slides.add(); slide.background.fill = colors.white;
  title(slide, "Données relationnelles et analytiques", "MySQL gère l'activité quotidienne. MongoDB alimente les statistiques administrateur.");
  image(slide, assets.mcd, "Modèle conceptuel de données de Vite et Gourmand", 70, 170, 610, 430, "contain");
  textbox(slide, "MySQL", 760, 205, 280, 35, { size: 27, bold: true, color: colors.gold });
  textbox(slide, "Utilisateurs, rôles, menus, plats, allergènes, commandes, avis, horaires et messages de contact.", 760, 260, 380, 100, { size: 21, color: colors.grey });
  textbox(slide, "MongoDB", 760, 412, 280, 35, { size: 27, bold: true, color: colors.gold });
  textbox(slide, "Projection des commandes pour comparer les menus et calculer le chiffre d'affaires avec des filtres de période.", 760, 465, 380, 96, { size: 21, color: colors.grey });
  label(slide, 8); addNotes(slide, "Visuel : MCD fourni par le candidat. MySQL reste la source de vérité métier.");
}

// 9 Security and Git
{
  const slide = presentation.slides.add(); slide.background.fill = colors.cream;
  title(slide, "Sécurité, configuration et Git", "Des données sensibles séparées du code versionné");
  textbox(slide, "Sécurité applicative", 72, 196, 410, 35, { size: 26, bold: true, color: colors.gold });
  textbox(slide, "Mots de passe hachés\nContrôle des rôles et de la propriété des commandes\nJetons CSRF sur les formulaires sensibles\nComptes désactivés refusés à la connexion", 72, 252, 505, 170, { size: 21 });
  textbox(slide, "Fichiers sensibles", 690, 196, 410, 35, { size: 26, bold: true, color: colors.gold });
  textbox(slide, ".env et .env.*\nIdentifiants MySQL, MongoDB et SMTP\n/vendor, logs, caches et sauvegardes locales\nVariables de production placées dans Heroku Config Vars", 690, 252, 500, 170, { size: 21 });
  textbox(slide, "Workflow Git : develop pour les intégrations, main pour une version testée puis déployée.", 72, 515, 1020, 42, { size: 22, bold: true, color: colors.ink });
  label(slide, 9); addNotes(slide, "Le fichier .gitignore exclut les secrets, dépendances locales, logs, caches et sauvegardes. Aucun mot de passe ne doit être partagé à l'oral.");
}

// 10 deploy
{
  const slide = presentation.slides.add(); slide.background.fill = colors.white;
  title(slide, "Déploiement et vérifications", "Une application mise en ligne sur Heroku et contrôlée par parcours");
  const steps = [
    ["1", "Config Vars", "MySQL, MongoDB, SMTP et APP_URL"],
    ["2", "Déploiement", "Push de la branche main vers Heroku"],
    ["3", "Migrations", "Mises à jour SQL, dont le cycle de vie des commandes"],
    ["4", "Tests", "Accueil, connexion, commande, e-mails et statistiques"],
  ];
  steps.forEach((s, i) => {
    const x = 72 + i * 286;
    textbox(slide, s[0], x, 210, 60, 58, { size: 44, bold: true, color: colors.gold });
    textbox(slide, s[1], x, 282, 220, 32, { size: 22, bold: true });
    textbox(slide, s[2], x, 328, 220, 82, { size: 18, color: colors.grey });
  });
  textbox(slide, "Les scénarios de test couvrent les menus, le cycle de commande, les avis, les rôles, les statistiques et le responsive.", 72, 500, 970, 54, { size: 25, bold: true });
  label(slide, 10); addNotes(slide, "Les détails des tests manuels sont disponibles dans docs/tests_manuels.md du dépôt.");
}

// 11 conclusion
{
  const slide = presentation.slides.add(); slide.background.fill = colors.dark;
  textbox(slide, "Vite & Gourmand", 72, 135, 850, 55, { size: 45, bold: true, color: colors.white });
  textbox(slide, "Une application qui relie la présentation des menus, la commande client et la gestion interne du traiteur.", 74, 220, 900, 66, { size: 27, color: "#F3E7D9" });
  textbox(slide, "Démonstration conseillée", 74, 375, 500, 33, { size: 23, bold: true, color: colors.gold });
  textbox(slide, "Accueil et menus\nCommande client\nSuivi employé\nStatistiques administrateur", 74, 425, 440, 150, { size: 24, color: colors.white });
  textbox(slide, "Merci pour votre attention", 74, 625, 570, 34, { size: 20, bold: true, color: colors.gold });
  addNotes(slide, "Conclusion. Proposer ensuite une démonstration avec les comptes préparés et les liens réels.");
}

if (process.env.RENDER_ONLY === "1") {
  const previewDir = path.join(buildDir, "preview");
  await fs.mkdir(previewDir, { recursive: true });
  for (let index = 0; index < 11; index += 1) {
    const slide = presentation.slides.getItem(index);
    const png = await slide.export({ format: "png", scale: 1 });
    await fs.writeFile(path.join(previewDir, `slide-${index + 1}.png`), new Uint8Array(await png.arrayBuffer()));
  }
  console.log(`Rendered previews in ${previewDir}`);
  process.exit(0);
}

await (await PresentationFile.exportPptx(presentation)).save(candidatePath);
const requirements = { explicitTotalSlideCount: 11, requiredNativeTableOwnerSlides: [], requiredNativeChartOwnerSlides: [] };
const result = await finalizePresentation({
  ...requirements,
  workspaceDir,
  candidatePath,
  finalPath,
  pythonExecutable: runtimePython,
  integrityValidatorPath: path.join(skillDir, "container_tools", "inspect_presentation_package_integrity.py"),
  layoutValidatorPath: path.join(skillDir, "container_tools", "inspect_presentation_layout_geometry.py"),
  layoutArgs: ["--expected-slide-size-emu", "12192000,6858000", "--validate-bullet-geometry", "--validate-heading-fit"],
  fontPolicy: { basis: "design", families: [font] },
  verifyArtifactToolImport: true,
  receiptPath: path.join(buildDir, "validation.json"),
});
console.log(JSON.stringify({ finalPath, result }, null, 2));
