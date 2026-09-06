-- Permettre à un même plat d'appartenir à plusieurs menus.
CREATE TABLE IF NOT EXISTS menus_plats (
    id_menu_plat INT AUTO_INCREMENT PRIMARY KEY,
    id_menu INT NOT NULL,
    id_plat INT NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- Conserver les compositions créées avec l'ancien modèle (plats.id_menu).
INSERT INTO menus_plats (id_menu, id_plat)
SELECT p.id_menu, p.id_plat
FROM plats p
LEFT JOIN menus_plats mp ON mp.id_menu = p.id_menu AND mp.id_plat = p.id_plat
WHERE p.id_menu IS NOT NULL AND mp.id_menu_plat IS NULL;
