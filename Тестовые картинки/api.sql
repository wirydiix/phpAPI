CREATE TABLE `category` (
  `id_ctg` int NOT NULL,
  `title` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id_ctg`, `title`) VALUES
(1, 'Хосттел'),
(2, 'Эконом'),
(3, 'VIP'),
(4, 'GOLD');

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `id_comment` int NOT NULL,
  `id_prd` int NOT NULL,
  `login` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text CHARACTER SET utf8mb4 NOT NULL,
  `time` date NOT NULL DEFAULT '2022-04-14'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id_comment`, `id_prd`, `login`, `title`, `text`, `time`) VALUES
(1, 1, 'unknow_1', 'Дада', 'Советую очень.', '2022-04-14'),
(2, 5, 'unknow_2', 'Нене', 'Не рекомендую\n', '2022-04-20');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id_prd` int NOT NULL,
  `id_ctg` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 NOT NULL,
  `price` int NOT NULL,
  `tags` varchar(100) NOT NULL DEFAULT 'all',
  `image` varchar(100) NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id_prd`, `id_ctg`, `title`, `description`, `price`, `tags`) VALUES
(1, 1, 'Самый дешёвый номер', 'Зато без мам, пап и кредитов.', 5000, 'хосттел,1_этаж'),
(2, 1, 'Комната под лестницей', 'Когда-то здесь жил Гарри Поттер.', 6000, 'хосттел,чулан'),
(3, 2, 'Комната 404', 'Желаем найти её.', 10000, 'эконом,4_этаж'),
(4, 2, 'Старый гараж Стива Джобса', 'Время великих окрытий.', 12000, 'эконом,гараж'),
(5, 3, 'Морской бриз', 'Номер с видом на море. Три комнаты помогут заглушить чувство одиночества!', 120000, 'VIP,7_этаж'),
(6, 3, 'Номер в пентхаусе', 'Теперь вы находитесь на вершине этого мира.', 150000, '20_этаж'),
(7, 4, 'Домик Бильбо', 'Почувствуй себя в шкуре хоббита.', 10000000, 'Властелин колец'),
(8, 4, 'Батискаф на дне маррианской впадины.', 'Проведи свой месяц незабываемо.', 1000000000, 'abyss');

-- --------------------------------------------------------

--
-- Структура таблицы `token`
--

CREATE TABLE `token` (
  `id_token` int NOT NULL,
  `login` varchar(30) NOT NULL,
  `token` varchar(260) NOT NULL,
  `create_at` time NOT NULL,
  `destroy_at` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `login` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `login`, `password`, `access`) VALUES
(1, 'user_1', 'ᚘƺɸäᴌᴁ', 0),
(2, 'admin', 'ØɡȵɤϢ', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_ctg`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `fk_id_prd` (`id_prd`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_prd`),
  ADD KEY `fk_id_ctg` (`id_ctg`);

--
-- Индексы таблицы `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id_token`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `token`
--
ALTER TABLE `token`
  MODIFY `id_token` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
ALTER TABLE `product`
  MODIFY `id_prd` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_id_prd` FOREIGN KEY (`id_prd`) REFERENCES `product` (`id_prd`);

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_id_ctg` FOREIGN KEY (`id_ctg`) REFERENCES `category` (`id_ctg`);
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comment`
--

