-- Neue Tabelle fuer Boersen-Instanzen
CREATE TABLE IF NOT EXISTS `boersen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datum` date NOT NULL,
  `status` enum('offen','geschlossen') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'offen',
  PRIMARY KEY (`id`),
  KEY `datum` (`datum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

