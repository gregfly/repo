SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `note` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ParagraphID` int(11) NOT NULL,
  `Name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `note_action` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(20) NOT NULL,
  `NoteID` int(11) NOT NULL,
  `Timestamp` bigint(20) NOT NULL,
  `Type` varchar(20) NOT NULL,
  `CursorBegin` int(11) NOT NULL,
  `CursorEnd` int(11) NOT NULL,
  `String` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `paragraph` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TextID` int(11) NOT NULL,
  `Position` decimal(10,0) NOT NULL,
  `Name` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `paragraph` (`ID`, `TextID`, `Position`, `Name`) VALUES
(1,	1,	1,	'— Я хочу рассказать о человеке и его мечте, — сказал Мартин. — Это был обыкновенный человек, живущий на планете Земля. И мечта у него была обыкновенная, простая, другой бы и за мечту ее не посчитал... уютный домик, маленькая машина, любимая жена и славные детишки. Человек умел не только мечтать, но и работать. Он построил свой дом, и дом даже получился не слишком маленьким. Встретил девушку, которую полюбил, и она полюбила его. Человек купил машину — чтобы можно было ездить в путешествия и быстрее возвращаться домой. Он даже купил еще одну машину — для жены, чтобы та не слишком скучала без него. У них родились дети: не один, не двое, а четверо прекрасных, умных детей, которые любили родителей.'),
(2,	1,	2,	'Ключник слушал. Сидел на диванчике в одной из маленьких комнатенок московской Станции и внимательно слушал Мартина.'),
(3,	1,	3,	'— И вот, когда мечта человека исполнилась, — продолжал Мартин, — ему вдруг стало одиноко. Его любила жена, его обожали дети, в доме было уютно и все дороги мира были открыты перед ним. Но чего-то не хватало. И однажды, темной осенней ночью, когда холодный ветер срывал последние листья с деревьев, человек вышел на балкон своего дома и посмотрел окрест. Он искал свою мечту, без которой стало так тяжело жить. Но мечта о доме превратилась в кирпичные стены и перестала быть мечтой. Все дороги лежали перед ним и машина стала лишь сваренными вместе кусками крашенного железа. Даже женщина, спавшая в его постели, была обычной женщиной, а не мечтой о любви. Даже дети, которых он любил, стали обычными детьми, а не мечтой о детях. И человек подумал, что было бы очень хорошо выйти из своего прекрасного дома, пнуть в крыло роскошную машину, помахать рукой жене, поцеловать детей и уйти навсегда...'),
(4,	1,	4,	'Мартин перевел дыхание. Ключники любили паузы, но дело было даже не в этом — Мартин еще не знал, как закончит свой рассказ.'),
(5,	1,	5,	'— Он ушел? — спросил ключник, и Мартин понял, как надо ответить.'),
(6,	1,	6,	'— Нет. Он спустился в спальню, лег рядом с женой и уснул. Не сразу, но все-таки уснул. И старался больше не выходить из дома, когда осенний ветер играет с опавшей листвой. Человек постиг то, что некоторые узнают в детстве, но многие не понимают и в старости. Он осознал, что нельзя мечтать о достижимом. С тех пор он старался придумать себе новую мечту, настоящую. Конечно же, это не вышло. Но зато — он жил мечтой о настоящей мечте.'),
(7,	1,	7,	'— Это очень старая история, — задумчиво сказал ключник. — Старая и печальная. Но ты развеял мою грусть, путник. Входи во Врата и начинай свой путь.');

CREATE TABLE `text` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Content` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `text` (`ID`, `Name`, `Content`) VALUES
(1,	'Сергей Лукьяненко, \"Спектр\"',	'— Я хочу рассказать о человеке и его мечте, — сказал Мартин. — Это был обыкновенный человек, живущий на планете Земля. И мечта у него была обыкновенная, простая, другой бы и за мечту ее не посчитал... уютный домик, маленькая машина, любимая жена и славные детишки. Человек умел не только мечтать, но и работать. Он построил свой дом, и дом даже получился не слишком маленьким. Встретил девушку, которую полюбил, и она полюбила его. Человек купил машину — чтобы можно было ездить в путешествия и быстрее возвращаться домой. Он даже купил еще одну машину — для жены, чтобы та не слишком скучала без него. У них родились дети: не один, не двое, а четверо прекрасных, умных детей, которые любили родителей.\r\nКлючник слушал. Сидел на диванчике в одной из маленьких комнатенок московской Станции и внимательно слушал Мартина.\r\n— И вот, когда мечта человека исполнилась, — продолжал Мартин, — ему вдруг стало одиноко. Его любила жена, его обожали дети, в доме было уютно и все дороги мира были открыты перед ним. Но чего-то не хватало. И однажды, темной осенней ночью, когда холодный ветер срывал последние листья с деревьев, человек вышел на балкон своего дома и посмотрел окрест. Он искал свою мечту, без которой стало так тяжело жить. Но мечта о доме превратилась в кирпичные стены и перестала быть мечтой. Все дороги лежали перед ним и машина стала лишь сваренными вместе кусками крашенного железа. Даже женщина, спавшая в его постели, была обычной женщиной, а не мечтой о любви. Даже дети, которых он любил, стали обычными детьми, а не мечтой о детях. И человек подумал, что было бы очень хорошо выйти из своего прекрасного дома, пнуть в крыло роскошную машину, помахать рукой жене, поцеловать детей и уйти навсегда...\r\nМартин перевел дыхание. Ключники любили паузы, но дело было даже не в этом — Мартин еще не знал, как закончит свой рассказ.\r\n— Он ушел? — спросил ключник, и Мартин понял, как надо ответить.\r\n— Нет. Он спустился в спальню, лег рядом с женой и уснул. Не сразу, но все-таки уснул. И старался больше не выходить из дома, когда осенний ветер играет с опавшей листвой. Человек постиг то, что некоторые узнают в детстве, но многие не понимают и в старости. Он осознал, что нельзя мечтать о достижимом. С тех пор он старался придумать себе новую мечту, настоящую. Конечно же, это не вышло. Но зато — он жил мечтой о настоящей мечте.\r\n— Это очень старая история, — задумчиво сказал ключник. — Старая и печальная. Но ты развеял мою грусть, путник. Входи во Врата и начинай свой путь.');
