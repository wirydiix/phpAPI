-- 
-- Таблица пользователей
--
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `login` varchar(30) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `surname	` varchar(30) NOT NULL,
  `id_rules` tinyint unsigned  DEFAULT 0,
  CONSTRAINT ID_USERS_PRIMARY_KEY PRIMARY KEY (id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- заполнение
INSERT INTO `users` (`login`, `password`, `name`, `surname`, `id_rules`) VALUES
('user_1', '3f49044c1469c6990a665f46ec6c0a41', 'Егор', 'Мифтахов',0),
('admin', '21232f297a57a5a743894a0e4a801fc3', 'Александр', 'Миронов',3);
--
-- Таблица прав
--
CREATE TABLE `rules` (
  `id_rules` tinyint unsigned NOT NULL UNIQUE,
  `name_rules` varchar(30) NOT NULL UNIQUE,
  `description_rules` varchar(255) NOT NULL,
  `discount` tinyint unsigned NOT NULL DEFAULT 0,
  CONSTRAINT ID_RULES_PRIMARY_KEY PRIMARY KEY (id_rules)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- заполнение
INSERT INTO `rules` (`id_rules`, `name_rules`, `description_rules`, `discount`) VALUES
(0, 'Пользователь','Базовые прова просмотра номеров и добавления коментарий.', 0),
(1, 'VIP','Пометка постоянных клиентом сервиса особым статусом. Скидка 10%', 10),
(2, 'Модератор','Права на: добавление, редактирование и удаление номеров и коментарий к ним. Скидка 15%', 15),
(3, 'Администратор','Управление правами. Скидка 20%', 20);
--
-- Таблица категорий
--
CREATE TABLE `category` (
  `id_ctg` int NOT NULL AUTO_INCREMENT,
  `name_ctg` varchar(30) NOT NULL UNIQUE,
  `description_ctg` varchar(255) NOT NULL,
  CONSTRAINT ID_CATEGORY_PRIMARY_KEY PRIMARY KEY (id_ctg)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- заполнение
INSERT INTO `category` (`name_ctg`, `description_ctg`) VALUES
('Хостел','Самые дешёвые номера.'),
('Эконом','Комфортно и доступно.'),
('VIP','Уютные и большие номера.'),
('GOLD','Экзотические предложения.');
--
-- Таблица статуса комнаты
--
CREATE TABLE `status` (
  `id_status` tinyint unsigned NOT NULL UNIQUE,
  `name_status` varchar(30) NOT NULL UNIQUE,
  `description_status` varchar(255) NOT NULL,
  CONSTRAINT ID_STATUS_PRIMARY_KEY PRIMARY KEY (id_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
INSERT INTO `status` (`id_status`, `name_status`, `description_status`) VALUES
(0, 'Свободно','Номер полностью свободен и его можно снять.'),
(1, 'Занято','На данный момент номер занят, пожалуйста подождите или выберите другой.'),
(2, 'В ремонте','К сожалению номер пришолсь закрыть на ремонт.'),
(3, 'В разработке','Скоро мы подготовим для вас нечто невероятное!');
--
-- Структура таблицы номеров
--
CREATE TABLE `product` (
  `id_prd` int NOT NULL AUTO_INCREMENT,
  `id_ctg` int NOT NULL,
  `name_prd` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `description_prd` varchar(500) CHARACTER SET utf8mb4 NOT NULL,
  `price` int NOT NULL,
  `tags` varchar(100) NOT NULL DEFAULT 'all',
  `image` varchar(100) NOT NULL DEFAULT 'none',
  `to_date` date NOT NULL DEFAULT '2022-01-01',
  `id_status` tinyint unsigned NOT NULL DEFAULT 0,
  CONSTRAINT ID_CTG_PRD_FOREIGN_KEY FOREIGN KEY (id_ctg) REFERENCES category (id_ctg),
  CONSTRAINT ID_STATUS_PRD_FOREIGN_KEY FOREIGN KEY (id_status) REFERENCES STATUS (id_status),
  CONSTRAINT ID_PRD_PRIMARY_KEY PRIMARY KEY (id_prd)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- заполнение
INSERT INTO `product` (`id_ctg`, `name_prd`, `description_prd`, `price`, `tags`) VALUES
(1, 'Самый дешёвый номер', 'Зато без мам, пап и кредитов.', 5000, '["Патрик","Хостел"]'),
(1, 'Комната под лестницей', 'Когда-то здесь жил Гарри Поттер.', 6000, '["Хостел","чулан"]'),
(2, 'Комната 404', 'Желаем найти её.', 10000, '["эконом","4_этаж"]'),
(2, 'Старый гараж Стива Джобса', 'Время великих окрытий.', 12000, '["эконом","гараж"]'),
(3, 'Морской бриз', 'Номер с видом на море. Три комнаты помогут заглушить чувство одиночества!', 120000, '["VIP","7_этаж"]'),
(3, 'Номер в пентхаусе', 'Теперь вы находитесь на вершине этого мира.', 150000, '["VIP","20_этаж"]'),
(4, 'Домик Бильбо', 'Почувствуй себя в шкуре хоббита.', 10000000, '["GOLD","Властелин колец"]'),
(4, 'Батискаф на дне маррианской впадины.', 'Проведи свой месяц незабываемо.', 1000000000, '["GOLD","abyss"]');

--
-- Структура таблицы `comment`
--
CREATE TABLE `comment` (
  `id_comment` int NOT NULL AUTO_INCREMENT,
  `id_prd` int NOT NULL,
  `id_user` int NOT NULL,
  `text` text CHARACTER SET utf8mb4 NOT NULL,
  `time` date NOT NULL DEFAULT '2022-04-14',
  CONSTRAINT ID_PRD_COMMENT_FOREIGN_KEY FOREIGN KEY (id_prd) REFERENCES product (id_prd),
  CONSTRAINT ID_USER_COMMENT_FOREIGN_KEY FOREIGN KEY (id_user) REFERENCES USERS (id_user),
  CONSTRAINT ID_COMMENT_PRIMARY_KEY PRIMARY KEY (id_comment)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Дамп данных таблицы `comment`
--
INSERT INTO `comment` (`id_prd`,  `id_user`, `text`, `time`) VALUES
(1, 1, 'Советую очень.', '2022-04-14'),
(2, 1, 'Не рекомендую.', '2022-04-20');
--
-- Структура таблицы `token`
--
CREATE TABLE `token` (
  `id_token` int AUTO_INCREMENT,
  `login` varchar(30) NOT NULL,
  `token` varchar(260) NOT NULL,
  `create_at` time NOT NULL,
  `destroy_at` time NOT NULL,
  CONSTRAINT ID_USER_TOKEN_FOREIGN_KEY FOREIGN KEY (id_user) REFERENCES USERS (id_user),
  CONSTRAINT ID_TOKEN_PRIMARY_KEY PRIMARY KEY (id_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Структура таблицы контрактов
--
CREATE TABLE `contract` (
  `id_contract` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_prd` int NOT NULL,
  `from_date` date NOT NULL DEFAULT '2022-04-14',
  `to_date` date NOT NULL DEFAULT '2022-04-15',
  `final_price` int,
  CONSTRAINT ID_USER_CONTRACT_FOREIGN_KEY FOREIGN KEY (id_user) REFERENCES USERS (id_user),
  CONSTRAINT ID_PRD_CONTRACT_FOREIGN_KEY FOREIGN KEY (id_prd) REFERENCES product (id_prd),
  CONSTRAINT ID_CONTRACT_PRIMARY_KEY PRIMARY KEY (id_contract)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop TRIGGER test;
delimiter $$
CREATE DEFINER=`root`@`127.0.0.1`
TRIGGER `test` BEFORE INSERT ON `contract`
FOR EACH ROW BEGIN 
IF ((SELECT `to_date` FROM `product` WHERE `id_prd`= NEW.id_prd) < NOW() OR (SELECT `id_status` FROM `product` WHERE `id_prd`= NEW.id_prd) = 0) AND New.to_date >= NOW() AND New.from_date >= NOW() AND New.from_date <= New.to_date THEN
set New.final_price = DATEDIFF(New.to_date, New.from_date) * (SELECT `price` FROM `product` WHERE `id_prd`= NEW.id_prd) * (1-(SELECT `discount` FROM `rules` WHERE `id_rules`= (SELECT `id_rules` FROM `users` WHERE `id_user` = NEW.id_user))/100);
UPDATE `product` SET `to_date` = New.to_date WHERE `id_prd`= NEW.id_prd;
UPDATE `product` SET `id_status` = 1 WHERE `id_prd`= NEW.id_prd;
else
	signal sqlstate '45000';
END IF;
END$$
---------------
INSERT INTO `contract` (`id_user`,  `id_prd`, `from_date`, `to_date`) VALUES
(1, 1, '2022-04-14', '2022-04-15');

DROP TABLE token;
DROP TABLE comment;
DROP TABLE contract;
DROP TABLE users;
DROP TABLE rules;
DROP TABLE product;
DROP TABLE category;




