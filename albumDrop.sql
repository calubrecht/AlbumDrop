CREATE TABLE IF NOT EXISTS `images` (
  `id` varchar(45) NOT NULL,
  `fileLoc` varchar(250) NOT NULL,
  `thumbLoc` varchar(250) NOT NULL,
  `originalName` varchar(200) NOT NULL,
  `owner` int(11) NOT NULL,
  `isPublic` tinyint(4) NOT NULL DEFAULT '0',
  `isVisible` tinyint(4) NOT NULL DEFAULT '0',
  `imageTS` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `thumbWidth` int(11) DEFAULT NULL,
  `thumbHeight` int(11) DEFAULT NULL,
  `extension` varchar(45) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `users` (
`idusers` int(11) NOT NULL,
  `login` varchar(45) NOT NULL,
  `fullName` varchar(45) NOT NULL,
  `email` varchar(120) NOT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT '0',
  `pwHash` varchar(80) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

ALTER TABLE `images`
 ADD PRIMARY KEY (`id`), ADD KEY `ownerKey` (`owner`);

ALTER TABLE `users`
 ADD PRIMARY KEY (`idusers`), ADD UNIQUE KEY `login_UNIQUE` (`login`);

ALTER TABLE `images`
ADD CONSTRAINT `ownerKey` FOREIGN KEY (`owner`) REFERENCES `users` (`idusers`) ON DELETE CASCADE ON UPDATE CASCADE;
