-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 10:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newstok`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `responded` timestamp(1) NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `delete_date` timestamp NULL DEFAULT NULL,
  `recovery_date` timestamp NULL DEFAULT NULL,
  `sts` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `message`, `responded`, `createdAt`, `delete_date`, `recovery_date`, `sts`) VALUES
(12, 'wadadoadko', 'waodkaodkado@gmail.com', 'woakdoadkaodkaodakdoawdadad', '2024-12-17 15:05:46.0', '2024-12-17 14:58:15', NULL, NULL, 2),
(13, 'waodkaodkaodk', 'woakdoadkaodk@gmail.com', 'waokdaokdaodkad', NULL, '2024-12-17 20:58:12', NULL, NULL, 1),
(14, 'wadadadaawdad', 'awdadaaawdad@gmail.com', 'wadadad', NULL, '2024-12-18 19:55:59', NULL, NULL, 1),
(15, 'wadadadaawdad', 'awdadaaawdad@gmail.com', 'wadadad', NULL, '2024-12-18 19:56:02', NULL, NULL, 1),
(16, 'wadadadaawdad', 'awdadaaawdad@gmail.com', 'wadadad', NULL, '2024-12-18 19:56:04', NULL, NULL, 1),
(17, 'awda', 'awdwadadadadaad@gmail.com', 'awdad', NULL, '2024-12-18 19:57:08', NULL, NULL, 1),
(18, 'awda', 'awdwadadadadaad@gmail.com', 'awdad', NULL, '2024-12-18 19:57:09', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `file_path` text NOT NULL,
  `file_size` varchar(11) NOT NULL,
  `sts` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `createdAt` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `updatedAt` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `delete_date` timestamp(6) NULL DEFAULT NULL,
  `recovery_date` timestamp(6) NULL DEFAULT NULL,
  `news_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ord` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sts` tinyint(4) DEFAULT 1,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delete_date` timestamp NULL DEFAULT NULL,
  `recovery_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `ord`, `url`, `sts`, `createdAt`, `updatedAt`, `delete_date`, `recovery_date`) VALUES
(8, 'Home (News)', 1, '/index.php', 2, '2024-12-16 19:16:27', '2024-12-16 19:16:27', NULL, NULL),
(9, 'Gallery', 2, '/gallery.php', 2, '2024-12-16 19:16:38', '2024-12-16 19:16:38', NULL, NULL),
(10, 'Contact Us', 4, '/contact.php', 2, '2024-12-16 19:16:52', '2024-12-17 18:54:42', NULL, NULL),
(11, 'About Us', 5, '/about.php', 2, '2024-12-16 19:17:03', '2024-12-17 18:54:45', NULL, NULL),
(12, 'Categories', 3, '/category', 2, '2024-12-17 18:54:26', '2024-12-17 18:54:26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `text` text NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT current_timestamp(),
  `sts` tinyint(4) DEFAULT 1,
  `author_id` int(11) DEFAULT NULL,
  `createdAt` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `updatedAt` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `tag_ids` varchar(255) NOT NULL,
  `delete_date` timestamp NULL DEFAULT NULL,
  `recovery_date` timestamp NULL DEFAULT NULL,
  `thumbnail` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `description`, `text`, `keywords`, `time`, `sts`, `author_id`, `createdAt`, `updatedAt`, `tag_ids`, `delete_date`, `recovery_date`, `thumbnail`) VALUES
(96, 'Breaking: Major Event Unfolds', 'A major event has just occurred, capturing global attention.', 'In an unprecedented turn of events, the world witnesses a significant development. Details are still emerging as authorities and experts work to assess the situation.', 'breaking news, urgent, major event, live updates, current situation', '2024-12-16 00:00:00', 2, 7, '2024-10-16 18:35:17.631865', '2024-12-16 18:35:17.631865', '[\"1\",\"3\"]', NULL, NULL, '/gallery/default.webp'),
(97, 'Government Passes Landmark Legislation', 'A historic bill has been passed in parliament, promising sweeping changes.', 'The government has passed a landmark bill addressing key issues. This legislation aims to reshape policy in critical areas, sparking both praise and criticism from various stakeholders.', 'politics, government, policy, bill, legislation', '2024-12-16 00:00:00', 1, 7, '2024-12-16 18:35:53.446641', '2024-12-16 18:35:53.446641', '[\"3\",\"2\",\"1\"]', '2024-12-18 21:38:25', '2024-12-18 21:38:42', '/gallery/default.webp'),
(98, 'Political Turmoil in the Capital', 'Unprecedented political events unfold in the capital.', 'The nation witnesses political upheaval as key players make surprising moves, leading to debates across the spectrum.', 'politics, capital events, government, leadership changes, debates', '2024-12-16 00:00:00', 1, 7, '2024-12-16 14:35:17.631865', '2024-12-16 14:35:17.631865', '[\"2\"]', '2024-12-18 21:38:26', '2024-12-18 21:38:43', '/gallery/default.webp'),
(99, 'World Leaders Meet for Crisis Talks', 'World leaders convene to address pressing global issues.', 'In an urgent summit, global leaders discuss solutions to tackle pressing challenges facing the world today.', 'world news, summit, leaders, global challenges, diplomacy', '2024-12-16 00:00:00', 1, 7, '2024-12-16 14:35:17.631865', '2024-12-16 14:35:17.631865', '[\"3\"]', '2024-12-18 21:38:26', '2024-12-18 21:38:44', '/gallery/default.webp'),
(100, 'Economy Sees Unexpected Growth', 'Economic reports show growth surpassing expectations.', 'Experts are analyzing the recent surge in economic growth, which has surpassed all expectations for this quarter.', 'business, economy, growth, finance, markets', '2024-12-16 00:00:00', 2, 7, '2024-12-16 14:35:17.631865', '2024-12-16 14:35:17.631865', '[\"4\"]', NULL, NULL, '/gallery/default.webp'),
(101, 'Tech Giant Unveils New Innovation', 'A leading tech company announces a groundbreaking innovation.', 'The tech industry is abuzz as a major company reveals its latest cutting-edge technology that promises to change the way we live.', 'technology, innovation, tech company, new product, cutting edge', '2024-12-16 00:00:00', 2, 7, '2024-12-16 14:35:17.631865', '2024-12-16 14:35:17.631865', '[\"5\"]', NULL, NULL, '/gallery/default.webp'),
(102, 'Health Breakthrough Offers Hope', 'A new health discovery could save millions.', 'Medical researchers announce a breakthrough that could revolutionize the treatment of a widespread illness.', 'health, breakthrough, research, treatment, medicine', '2024-12-16 00:00:00', 2, 7, '2024-12-16 14:35:17.631865', '2024-12-16 14:35:17.631865', '[\"6\"]', NULL, NULL, '/gallery/default.webp'),
(103, 'Scientists Discover New Species', 'A groundbreaking discovery in the natural world.', 'Scientists announce the discovery of a previously unknown species, shedding new light on biodiversity.', 'science, discovery, new species, biodiversity, research', '2024-12-16 00:00:00', 2, 7, '2024-11-16 14:35:17.631865', '2024-12-17 15:02:03.000000', '[\"7\",\"17\",\"16\",\"15\"]', '2024-12-17 15:02:08', '2024-12-17 15:02:24', '/gallery/default.webp'),
(104, 'Environmental Crisis Demands Action', 'Calls for urgent action to address environmental challenges.', 'Activists and scientists stress the need for immediate measures to combat climate change and protect ecosystems.', 'environment, climate change, action, crisis, ecosystems', '2024-12-16 00:00:00', 2, 7, '2024-12-16 14:35:17.631865', '2024-11-16 14:35:17.631865', '[\"8\"]', NULL, NULL, '/gallery/default.webp');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sts` tinyint(4) DEFAULT 1,
  `createdAt` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `updatedAt` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `delete_date` timestamp NULL DEFAULT NULL,
  `recovery_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `sts`, `createdAt`, `updatedAt`, `delete_date`, `recovery_date`) VALUES
(1, 'Breaking News', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(2, 'Politics', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(3, 'World News', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(4, 'Business & Economy', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(5, 'Technology', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(6, 'Health', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(7, 'Science', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(8, 'Environment', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(9, 'Education', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(10, 'Sports', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(11, 'Entertainment', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(12, 'Culture & Arts', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(13, 'Lifestyle', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(14, 'Travel', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(15, 'Food & Drink', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(16, 'Opinion & Editorial', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(17, 'Weather', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(18, 'Crime & Law', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(19, 'History & Features', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL),
(20, 'Local News', 2, '2024-12-16 18:32:53.902059', '2024-12-16 21:28:30.740415', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `login_time` timestamp NULL DEFAULT NULL,
  `sts` tinyint(4) DEFAULT 1,
  `display_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delete_date` timestamp NULL DEFAULT NULL,
  `recovery_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `type`, `login_time`, `sts`, `display_name`, `email`, `createdAt`, `updatedAt`, `delete_date`, `recovery_date`) VALUES
(7, 'tsotne123', '$2y$10$L6L//XfHTyTi6rDNVXlJKOEHibifZpiYEjjjp1CZMecAUeaWWm9Vq', 'admin', '2024-12-18 20:40:36', 2, 'Tsotne Pharsenadze', 'Tsotne123@gmail.com', '2024-12-18 20:08:36', '2024-12-18 20:43:29', NULL, NULL),
(8, 'shdwfthr', '$2y$10$W8M9uxsR3x58JaXCcftYyOfSEMDauG5jtnFi5e15Zsr8lkyuLvtIi', 'editor', NULL, 3, 'shdwfthrshdwfthr', 'shdwfthr@gmail.com', '2024-12-18 21:31:49', '2024-12-18 21:33:40', '2024-12-18 21:33:40', NULL),
(9, 'shdwfthrshdwfthr', '$2y$10$SoaYxfkM80XvfZyGAMB7Fu87VLgSf4dlp6KHAakbfbzOfjWUU5xUO', '', NULL, 3, 'shdwfthrshdwfthr', 'shdwfthr@gmail.com', '2024-12-18 21:33:47', '2024-12-18 21:34:18', '2024-12-18 21:34:18', NULL),
(10, 'shdwfthrshdwfthr', '$2y$10$9P9gMJHwfQjZEASzFKJqk.E9MWxPG96H4FLhMlg5kIRd1oiUnE45.', 'admin', NULL, 2, 'shdwfthrshdwfthr', 'shdwfthr@gmail.com', '2024-12-18 21:34:25', '2024-12-18 21:34:25', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `gallery_ibfk_2` (`news_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `gallery_ibfk_2` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
