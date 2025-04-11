-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 11, 2025 at 11:19 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Checkme_Des329`
--

-- --------------------------------------------------------

--
-- Table structure for table `blocked_accounts`
--

CREATE TABLE `blocked_accounts` (
  `block_id` int(11) NOT NULL,
  `blocker_user_id` int(11) NOT NULL,
  `blocked_user_id` int(11) NOT NULL,
  `blocked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `saved_todo`
--

CREATE TABLE `saved_todo` (
  `saved_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `saved_todo`
--

INSERT INTO `saved_todo` (`saved_id`, `user_id`, `item_id`, `saved_at`) VALUES
(1, 1, 1, '2025-04-09 13:18:23');

-- --------------------------------------------------------

--
-- Table structure for table `todo_items`
--

CREATE TABLE `todo_items` (
  `item_id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `is_completed` tinyint(1) DEFAULT '0',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `todo_items`
--

INSERT INTO `todo_items` (`item_id`, `folder_id`, `title`, `description`, `is_completed`, `due_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'test', NULL, 0, NULL, '2025-04-04 12:29:24', '2025-04-04 17:03:39'),
(10, 1, 'test2', '', 1, NULL, '2025-04-09 14:47:03', '2025-04-11 06:45:30');

--
-- Triggers `todo_items`
--
DELIMITER $$
CREATE TRIGGER `after_todo_delete` AFTER DELETE ON `todo_items` FOR EACH ROW BEGIN
    UPDATE users
    SET todo_post_count = todo_post_count - 1
    WHERE user_id = (
        SELECT user_id FROM todo_list_folders
        WHERE folder_id = OLD.folder_id
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_todo_insert` AFTER INSERT ON `todo_items` FOR EACH ROW BEGIN
    UPDATE users
    SET todo_post_count = todo_post_count + 1
    WHERE user_id = (
        SELECT user_id FROM todo_list_folders
        WHERE folder_id = NEW.folder_id
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `todo_list_folders`
--

CREATE TABLE `todo_list_folders` (
  `folder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `todo_list_folders`
--

INSERT INTO `todo_list_folders` (`folder_id`, `user_id`, `folder_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Project 3rd year', '2025-04-04 07:50:11', '2025-04-04 07:50:11'),
(2, 1, 'Today', '2025-04-04 08:32:49', '2025-04-04 08:32:49'),
(3, 1, 'Tmr', '2025-04-04 08:39:21', '2025-04-04 08:39:21'),
(4, 1, 'test', '2025-04-08 14:33:51', '2025-04-08 14:33:51'),
(5, 1, 'test2', '2025-04-08 15:25:31', '2025-04-08 15:25:31'),
(6, 1, 'test3', '2025-04-09 13:42:48', '2025-04-09 13:42:48'),
(7, 1, 'test4', '2025-04-09 14:49:16', '2025-04-09 14:49:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bio` varchar(255) DEFAULT NULL,
  `follower_count` int(11) DEFAULT '0',
  `following_count` int(11) DEFAULT '0',
  `todo_post_count` int(11) DEFAULT '0',
  `is_private` tinyint(1) DEFAULT '0',
  `language` varchar(10) DEFAULT 'en',
  `notify_comment` tinyint(1) DEFAULT '1',
  `notify_follow` tinyint(1) DEFAULT '1',
  `notify_likes` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `created_at`, `updated_at`, `bio`, `follower_count`, `following_count`, `todo_post_count`, `is_private`, `language`, `notify_comment`, `notify_follow`, `notify_likes`) VALUES
(1, 'test', '$2b$10$Bl/R4qQgzwYwPzgkSOHdcO/dRrH1Hp6kgodVCeLO60/XOTtfw.20i', '2025-04-04 06:41:32', '2025-04-11 07:31:27', 'hello', 600, 640, 3, 0, 'en', 0, 0, 0),
(2, 'test2', '$2b$10$bU5tNzePQ149NwBFISjjRO83Fq8Hf0HBD7aIW3X4SM/VDoM2arJ4y', '2025-04-04 06:44:10', '2025-04-04 06:44:10', '', 0, 0, 0, 0, 'en', 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blocked_accounts`
--
ALTER TABLE `blocked_accounts`
  ADD PRIMARY KEY (`block_id`),
  ADD UNIQUE KEY `blocker_user_id` (`blocker_user_id`,`blocked_user_id`),
  ADD KEY `blocked_user_id` (`blocked_user_id`);

--
-- Indexes for table `saved_todo`
--
ALTER TABLE `saved_todo`
  ADD PRIMARY KEY (`saved_id`),
  ADD UNIQUE KEY `unique_saved` (`user_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `todo_items`
--
ALTER TABLE `todo_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `folder_id` (`folder_id`);

--
-- Indexes for table `todo_list_folders`
--
ALTER TABLE `todo_list_folders`
  ADD PRIMARY KEY (`folder_id`),
  ADD UNIQUE KEY `user_folder_name` (`user_id`,`folder_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blocked_accounts`
--
ALTER TABLE `blocked_accounts`
  MODIFY `block_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_todo`
--
ALTER TABLE `saved_todo`
  MODIFY `saved_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `todo_items`
--
ALTER TABLE `todo_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `todo_list_folders`
--
ALTER TABLE `todo_list_folders`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blocked_accounts`
--
ALTER TABLE `blocked_accounts`
  ADD CONSTRAINT `blocked_accounts_ibfk_1` FOREIGN KEY (`blocker_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blocked_accounts_ibfk_2` FOREIGN KEY (`blocked_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_todo`
--
ALTER TABLE `saved_todo`
  ADD CONSTRAINT `saved_todo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_todo_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `todo_items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `todo_items`
--
ALTER TABLE `todo_items`
  ADD CONSTRAINT `todo_items_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `todo_list_folders` (`folder_id`) ON DELETE CASCADE;

--
-- Constraints for table `todo_list_folders`
--
ALTER TABLE `todo_list_folders`
  ADD CONSTRAINT `todo_list_folders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
