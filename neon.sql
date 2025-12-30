-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2021 at 10:11 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maxi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `adminid` varchar(300) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `name` varchar(300) NOT NULL,
  `email` text NOT NULL,
  `imagelocation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `adminid`, `password`, `salt`, `name`, `email`, `imagelocation`) VALUES
(1, '1471436678', '$2y$10$hXkYkC6/KaQlmXJgt3cuvu1n9BnFgP3FY8B7ScEwbfF7g7pBS6I.i', '¸û?	=@Ñ¿ûINR°“(¿<y/s_È$›½L°›bp', 'Admin Neon', 'admin@neon.com', '34115f0aab3e86ad.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `slug`, `date_added`) VALUES
(9, 'Developer of the Year', 'developer-of-the-year', '2019-08-15 13:37:47'),
(10, 'Developer of the Month', 'developer-of-the-month', '2019-08-15 13:38:02'),
(11, 'Agency of the Year', 'agency-of-the-year', '2019-08-15 13:38:22'),
(12, 'Agency of the Month', 'agency-of-the-month', '2019-08-15 13:38:35');

-- --------------------------------------------------------

--
-- Table structure for table `nominees`
--

CREATE TABLE `nominees` (
  `id` int(255) NOT NULL,
  `name` varchar(300) NOT NULL,
  `tag_line` varchar(300) NOT NULL,
  `imagelocation` varchar(300) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category` varchar(300) NOT NULL,
  `votes` int(50) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nominees`
--

INSERT INTO `nominees` (`id`, `name`, `tag_line`, `imagelocation`, `category_id`, `category`, `votes`, `date_added`) VALUES
(7, 'Anthony E Fisher', 'Work Ethic', '71a81cc44c58a5ae.jpg', 9, 'Developer of the Year', 0, '2019-08-15 14:08:07'),
(8, 'Julia D Mosley', 'Best web designer', 'f47dabb9dd9cd091.jpg', 9, 'Developer of the Year', 0, '2019-08-15 14:09:22'),
(9, 'Charles J Linton', 'Graphics is my thing', 'a4208ab6d0408a94.png', 9, 'Developer of the Year', 0, '2019-08-15 14:10:19'),
(10, 'Samatha H Rios', 'Pixel Perfect', 'd9ea3eee7e1df8c6.jpeg', 9, 'Developer of the Year', 0, '2019-08-15 14:11:51'),
(11, 'Helen W Ward', 'Winning Attitude', 'bd1f5e711e57357b.jpg', 10, 'Developer of the Month', 0, '2019-08-15 15:45:23'),
(12, 'Michael C McFarland', 'Beautiful Designs', '4e1f122b1a799ebf.jpg', 10, 'Developer of the Month', 0, '2019-08-15 15:46:16'),
(13, 'Eduard Cheng', 'Workahollic', '928b3f479e0d9c0e.jpg', 10, 'Developer of the Month', 0, '2019-08-15 15:49:16'),
(14, 'Marie Jensen', 'Creative & Lovely', '7d0f87b527ecb059.jpg', 10, 'Developer of the Month', 0, '2019-08-15 15:50:25'),
(15, 'Sophia Perez', 'Beautiful Designs', 'b8a2fc1e1ef22795.jpg', 11, 'Agency of the Year', 0, '2019-08-15 17:43:14'),
(16, 'Demetri Caron', 'Work Ethic', 'f2557f0ef6d54123.jpg', 11, 'Agency of the Year', 0, '2019-08-16 14:13:49'),
(17, 'Veeti Seppanen', 'Great Designer', '0768fa98e8369807.jpeg', 11, 'Agency of the Year', 0, '2019-08-16 14:14:58'),
(18, 'Derrick Wells', 'UI/UX', 'be1323e21a511e4d.jpg', 11, 'Agency of the Year', 0, '2019-08-16 14:15:50'),
(19, 'Ian Dejesus', 'UI/UX', '7ea85415f08d95b5.jpg', 12, 'Agency of the Month', 0, '2019-08-16 14:16:51'),
(20, 'Macie Naquin', 'Great Designer', '2525f2406858512b.jpg', 12, 'Agency of the Month', 0, '2019-08-16 14:17:38'),
(21, 'Kayley Dwyer', 'Beautiful, Creative', '88d3442e85c82aca.jpg', 12, 'Agency of the Month', 0, '2019-08-16 14:18:40'),
(22, 'Cain Kemp', 'Work Ethic', '6788338625afe47a.jpg', 12, 'Agency of the Month', 0, '2019-08-16 14:19:41');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `sitename` varchar(300) NOT NULL,
  `title` varchar(300) NOT NULL,
  `imagelocation` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `sitename`, `title`, `imagelocation`) VALUES
(1, 'Neon', 'Online Voting System', '9a4ad06f5f3633f0.png');

-- --------------------------------------------------------

--
-- Table structure for table `start`
--

CREATE TABLE `start` (
  `id` int(255) NOT NULL,
  `title` varchar(300) NOT NULL,
  `date_to_start` date NOT NULL,
  `date_to_end` date NOT NULL,
  `description` mediumtext NOT NULL,
  `end` tinyint(4) NOT NULL,
  `date_ended` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `start`
--

INSERT INTO `start` (`id`, `title`, `date_to_start`, `date_to_end`, `description`, `end`, `date_ended`) VALUES
(8, 'Neon Awards', '2019-08-15', '2021-04-04', 'Welcome to our Neon Awards Online Voting system. We have selectively chosen the best of the candidates who applied to be nominated for the categories. Now  register to vote for the candidates. Thank you.', 0, '2021-02-07');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(255) NOT NULL,
  `userid` varchar(300) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `name` varchar(300) NOT NULL,
  `email` text NOT NULL,
  `joined` datetime NOT NULL,
  `verified` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `userid`, `password`, `salt`, `name`, `email`, `joined`, `verified`) VALUES
(8, '852704825112', '$2y$10$Ie9QRNe88bmelP0a3mUP6uS7vU5cDwAnyf2b1FLnz3gWI.06evFYi', '', 'TheMashaBrand', 'voter@gmail.com', '2019-08-16 14:01:09', 1),
(10, '236518526808', '$2y$10$qQ5yOPoEFp6Y.WtpNNeIAuMInY5e4OalfIs4RI8LR0RfZub3MGQqC', '>?W????O?0?4s????Y?d????>-@', 'Dan', 'dan@gmail.com', '2021-02-01 19:35:32', 1),
(11, '864529816491', '$2y$10$ZCYfwmDOHoLV5ZBYnBQsxeBTNSfTF3qf19EK9rWnpo0pJopXc.upG', '*?^?\ry?b\nV???w?	[k\Z???;???S@?R', 'Voter 1', 'voter_1@gmail.com', '2021-02-02 10:22:03', 1),
(12, '739246640952', '$2y$10$uJ9H0rEm//n1yWmdIhFqDONPUOtaBWFyuMNKu1kfmTkMEs7ItmWv6', 'h?I)Q??d?2?f??dV0?@?2?d??', 'Voter 2', 'voter_2@gmail.com', '2021-02-02 10:23:18', 1),
(13, '260946205039', '$2y$10$sBqvj2itUuMfeqjwxtREjOfUrTG49sScNEGe8.Xlv8MST6tp6.m9e', 'l?p??X?8Vtt???5???h?;?U?n?', 'Voter 3', 'voter_3@gmail.com', '2021-02-02 10:24:48', 1),
(14, '236839658634', '$2y$10$3SR9iZsrINrYMwkEt48BC.lD02cIp7fA6qOJEezCVtH0D8K6R9DTa', '??????B[$@????(,*?[?k??>\0?', 'Voter 4', 'voter_4@gmail.com', '2021-02-02 10:25:02', 1),
(15, '327258290636', '$2y$10$iMzgLqObU2pCmYp210W5putLV98wjLaXjVof5clilfpzGgI2w2nDm', 'o?,?????????R????	9?3?P`?E??', 'Voter 5', 'voter_5@gmail.com', '2021-02-02 10:25:17', 1),
(16, '137485771790', '$2y$10$t2iyaaAWyv66LBVf9zuThOHN18ARi60LyZniWxwCLesNKdnq2u0IS', '??s??@j?W?D/&?????w-??A?pR', 'Voter 6', 'voter_6@gmail.com', '2021-02-02 10:25:44', 1),
(17, '109089008757', '$2y$10$/dzfKwBpfXK99EOfRrBBcOCM9yvk2HatXKgd4f3Nho4Ikw1X.zX8.', '????$:???V?VO??0y???^g?{?C??X', 'Voter 7', 'voter_7@gmail.com', '2021-02-02 10:25:59', 1),
(18, '104908593394', '$2y$10$ejXGqpMyc3D79HoXmsRd7eyFHrRb4aBorG0l3NtdvP/RSC0icvQoC', 'F]???!p?\0???8??g??c??5?]?-?', 'Voter 8', 'voter_8@gmail.com', '2021-02-02 10:26:15', 1),
(19, '302097933764', '$2y$10$XKE6O1zZIE9kRigLoRqo8e7JCl8ikHnjvnj.n3b9eiUiUUExeN1Xy', '}??~]?\"M??d;^????f?>b?~Nu?', 'Voter 9', 'voter_9@gmail.com', '2021-02-02 10:26:41', 1),
(20, '314093523800', '$2y$10$Xym6.ImmUoZNdxi7HKFEA.foY1cq7DG2u8dWEcgSBBNoIdvu6I18a', '?s?A(????\0V??,???]?\"Y^?q', 'Voter 10', 'voter_10@gmail.com', '2021-02-02 10:27:00', 1),
(21, '159123795770', '$2y$10$gf0TiLPbDxLV6CCSwRutfuXyJAupp5QjuygwbUSfOPOGh1SVOX4Hy', '?k??\0???????~?z?F?K??\'f????y', 'Voter 11', 'voter_11@gmail.com', '2021-02-02 10:27:14', 1),
(22, '343557107136', '$2y$10$uGJvfhs/1hrI.ERt6znFNucuXx4TAPF69N7qMEwvU5bHemIdYaA52', '?:X??*?????????T??#???B?.?', 'Voter 12', 'voter_12@gmail.com', '2021-02-02 10:27:30', 1),
(23, '986694787027', '$2y$10$ykhL6d4uCJs/fZA4BZEAX.ahaJCu18CC5x3Zy2iMAi/n4cXKiXY.a', 'K???K?}Ø??Af?jF???????????', 'Voter 13', 'voter_13@gmail.com', '2021-02-02 10:27:45', 1),
(24, '215622483317', '$2y$10$hSzWnd/JNDFYDifoSSzISufu7Y1wFyq7qGbwc4/rU1BpZHCadDA/q', 'q?1??2=??@Hu,???a?E?cI????', 'Voter 14', 'voter_14@gmail.com', '2021-02-02 10:28:09', 1),
(25, '289577629834', '$2y$10$0VrW8XWQfE8xbhxcllnl9ukjql8GyHOdSpTblYUY3d1il0yjzJmva', '>???oj{?fP??)wg?????`<???', 'Voter 15', 'voter_15@gmail.com', '2021-02-02 10:28:24', 1),
(26, '309265309990', '$2y$10$GDvl4DucpEMHVklrYUC2NOenWZuoRHXL4Tx0z1g3T4g.3EVmvjrQS', '?G?s???FD)\'?zyh)~/?B?????|???', 'Voter 16', 'voter_16@gmail.com', '2021-02-02 10:28:39', 1),
(27, '677694988750', '$2y$10$W6WLLzRH4MZW5/tD7nasK.oEncSF6JtCaiJCstJqEpGhleW75fl06', '????????p???\Z????3???oo[??', 'Voter 17', 'voter_17@gmail.com', '2021-02-02 10:28:56', 1),
(28, '321900077047', '$2y$10$fLJhPM8FhJS3XADx/QumXu/t5E33VrU7WEF74gTuDjTss9UC0kY6.', '?%`?=L?\'I?{?F?R???Ey?*\0Y$??>', 'Voter 18', 'voter_18@gmail.com', '2021-02-02 10:29:12', 1),
(29, '218129833203', '$2y$10$iHuYNMb6uf1NjgIFt5/jauk25341AnKIk9SpKzuimqDwx8VcJEPlO', '\n?c??TK?r+??H????+?Ly$?$??', 'Voter 19', 'voter_19@gmail.com', '2021-02-02 10:29:26', 1),
(30, '123075269317', '$2y$10$Nt.03zdNKzOk5WQLYBST0uUH42idV1fGgDGJYRevSsQZPcTBnKvUS', '???Q?4?????d?????%?L?', 'Voter 20', 'voter_20@gmail.com', '2021-02-02 10:29:45', 1),
(31, '129799626817', '$2y$10$A0AAGXxxwUVdFJSwoRrBbuP5ADa1oZ1l56zZ9D2SN3vbmVujR2maK', 'ov?G????z?????\0C?sn?o?M??j8??', 'Voter 21', 'voter_21@gmail.com', '2021-02-02 10:29:57', 1),
(32, '103717761463', '$2y$10$DxdwjGD6Ba8mHHlLYsS/m.sOKwXpBdN87HMk6sKkZRwlJeOhtM3tG', '???JF??Rd ???#L?t?????D?l?r', 'Voter 22', 'voter_22@gmail.com', '2021-02-02 10:30:10', 1),
(33, '333195778954', '$2y$10$07v1rL3O.jJ7Zg4nJyeytOz5FI1xLlTilhHbIK//Uvh0u6KH.XA5i', 'I?O?	???s????????t??~;?$Q??d?', 'Voter 23', 'voter_23@gmail.com', '2021-02-02 10:30:26', 1),
(34, '295953651295', '$2y$10$ANqVoJIIxhBNv/gpZMts4uGxd5x6ghxde5uKRin/chgf/tStr0Rja', '>$????dC??e??m???K\rQ?1?yy\r??f', 'Voter 24', 'voter_24@gmail.com', '2021-02-02 10:32:22', 1),
(35, '294132165509', '$2y$10$wyhx6xFkGUimCS5z0a4TbOpcBuGt50zMTyKZaEkqP0hh8VVaqSZ4S', '??B???\Z????t\"9??Z??=?>mn?)', 'Voter 25', 'voter_25@gmail.com', '2021-02-02 10:32:36', 1),
(36, '261628934207', '$2y$10$zEseOQeSeZYsjimmGu2n5OiWbi6Xem0wAf5ZT8kx6qJtJNW1osw2K', '?<?{?????$;C????d\"\'OVv?	\"?\n?', 'Voter 26', 'voter_26@gmail.com', '2021-02-02 10:32:53', 1),
(37, '791697514472', '$2y$10$.jPROHSao2s0zidDjaxHieEpwZON0AMNfOevjmLHWDc35kc8ger42', '?????\r?J<?????????H?1??oz]?', 'Voter 27', 'voter_27@gmail.com', '2021-02-02 10:33:08', 1),
(38, '185760700531', '$2y$10$Kpw/7gsRw489fznK5T06eeq176DRzfmlZyr67cHRY0LrwZnyz7yfi', ':\'KY??z??dL?F?????Bu', 'Voter 28', 'voter_28@gmail.com', '2021-02-02 10:33:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(255) NOT NULL,
  `userid` varchar(300) NOT NULL,
  `nominee` int(50) NOT NULL,
  `category_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `userid`, `nominee`, `category_id`) VALUES
(1, '852704825112', 9, 9),
(2, '852704825112', 14, 10),
(3, '236518526808', 8, 9),
(4, '236518526808', 13, 10),
(5, '236518526808', 17, 11),
(6, '236518526808', 22, 12),
(7, '864529816491', 7, 9),
(8, '739246640952', 7, 9),
(9, '260946205039', 7, 9),
(10, '236839658634', 7, 9),
(11, '327258290636', 7, 9),
(12, '137485771790', 7, 9),
(13, '109089008757', 7, 9),
(14, '104908593394', 7, 9),
(15, '302097933764', 7, 9),
(16, '314093523800', 7, 9),
(17, '159123795770', 7, 9),
(18, '343557107136', 8, 9),
(19, '986694787027', 8, 9),
(20, '215622483317', 8, 9),
(21, '289577629834', 8, 9),
(22, '309265309990', 8, 9),
(23, '677694988750', 8, 9),
(24, '321900077047', 8, 9),
(25, '218129833203', 8, 9),
(26, '123075269317', 9, 9),
(27, '129799626817', 9, 9),
(28, '103717761463', 9, 9),
(29, '333195778954', 9, 9),
(30, '295953651295', 9, 9),
(31, '294132165509', 10, 9),
(32, '261628934207', 10, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nominees`
--
ALTER TABLE `nominees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `start`
--
ALTER TABLE `start`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `nominees`
--
ALTER TABLE `nominees`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `start`
--
ALTER TABLE `start`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
