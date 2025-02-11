-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2025 at 10:07 AM
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
-- Database: `tours_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities1`
--

CREATE TABLE `activities1` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities1`
--

INSERT INTO `activities1` (`id`, `tour_id`, `username`, `activity_name`, `date`, `start_time`, `end_time`, `description`, `status`, `reason`) VALUES
(20, 55, 'sfdafsf', 'adssa', '2025-02-20', '16:39:00', '19:37:00', 'sada', 'rejected', 'sxdads'),
(21, 655, 'wddad6546', 'asdsd', '2025-02-01', '14:13:00', '14:15:00', 'sdasa', 'pending', NULL),
(22, 888, 'sfdafsf', 'asdsd', '2025-01-31', '18:22:00', '14:26:00', 'sdd', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `tour_id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `accommodation_cost` decimal(10,2) NOT NULL,
  `transportation_cost` decimal(10,2) NOT NULL,
  `other_expenses` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `status` enum('Approved','Rejected','Pending') DEFAULT 'Pending',
  `admin_comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `tour_id`, `username`, `accommodation_cost`, `transportation_cost`, `other_expenses`, `total_cost`, `receipt_path`, `status`, `admin_comment`) VALUES
(1, 'TOUR-ABBFA0C5', '', 654.00, 6656.00, 8555.00, 15865.00, 'uploads/679bae8c57fd4.jpg', 'Approved', 'Approved without comment'),
(2, 'TOUR-CBABD6E4', '', 479.00, 4.00, 6454.00, 6937.00, 'uploads/67a1a15ab031f.png', 'Approved', 'Approved without comment'),
(3, 'TOUR-9515EE98', '', 45.00, 74.00, 654.00, 773.00, 'uploads/67a200d157fd3.png', 'Rejected', 'sdalksd'),
(4, 'TOUR-BF34E4E9', '', 4564.00, 4564.00, 64545.00, 73673.00, 'uploads/67a2027261bc4.png', 'Rejected', 'dasd'),
(5, 'TOUR-C8A226A7', '', 564.00, 546.00, 546.00, 1656.00, 'uploads/67a20350c6b82.png', 'Approved', 'Approved without comment'),
(6, 'TOUR-1E666E9E', 'bhuvnesh', 4546.00, 566.00, 544.00, 5656.00, 'uploads/67a5c5aaa7286.jpg', 'Rejected', 'sdsd');

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `id` int(11) NOT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL,
  `username` varchar(255) NOT NULL,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`id`, `destination`, `start_date`, `end_date`, `days`, `description`, `status`, `username`, `rejection_reason`) VALUES
(38, 'xyz', '2025-02-07', '2025-02-07', 5, 'dsdf', 'approved', 'bhuvnesh', ''),
(40, 'xyz', '2025-01-31', '2025-02-21', 4, 'sadad', 'rejected', 'bhuvnesh', 'aedsad'),
(42, 'adsds', '2025-02-20', '2025-02-28', 55, 'ada', 'pending', 'sfsdf', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employee','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'Vrushali', '$2y$10$JRHC7iFu/5K4RvTBJ7qoFe01xO49Fhy1lubFhC6LMiZPqvsMz3DlC', 'admin'),
(2, 'Raj', '$2y$10$0zblVWGYToWaQRB6xlm1vuOoaBeoaqJWr9nZwBLh4Vv/nJ7.wPQnm', 'admin'),
(3, 'Aarush', '$2y$10$NiKngqP8PWStutzJgmIhmu1JIvy4QOAoiysB1a/8Q5jJOL3rBM/Lu', 'employee'),
(4, 'Rahul', '$2y$10$SZ9KhpoK41RYUVUkpWrUXeSYHrWzf.GskougVOeBH0D7EAg.ZXU.u', 'employee'),
(5, 'Vaishali', '$2y$10$udOWmRZyCSb4AF/0Tvt8huntZtA5i3WaJMrGUAoL6zK8Qj4do.FD2', 'admin'),
(6, 'Sayli', '$2y$10$2f/XfpCSFr3u6adXmtNKVuS6FsfG/nmw6mKM.H/Nw35N4SFuocQYO', 'employee'),
(8, 'Shyam', '$2y$10$o2Iz4vqOQLsgPiqvgo3PTOiEeRGE5fj17Oo3c6baTKDhZ07KLQULW', 'employee'),
(9, 'siddhi', '$2y$10$ov5vNtB7MxljAHJI0raiJ.t53cSnkKIGUguLJ/GlmOKAtTccm8kty', 'admin'),
(10, 'Akshay', '$2y$10$Ij2NZcedcymWmG33qHOpD.g17Xn9ciSffgh.QvMBK8HTq6KTeZ5lq', 'employee'),
(11, 'Akanksha', '$2y$10$pOt0bIf7VhodYDzqwbdyW.ySPzhuHiFpdDUJxOhTfMy.BuZ3kvZwa', 'employee'),
(12, 'Adi', '$2y$10$lDTrvaFq32xgNAHBpv1uoulu5wMSvVDT1b0373FizuhToVijE4w2q', 'admin'),
(13, 'sahil matha', '$2y$10$0XONNoB3FDOvZXj1iQOJuuTkKNbeTb0Uv/pprM59Nz89awLGKWe9y', 'admin'),
(14, 'harshada Rajput', '$2y$10$FACvWcj6eGIzndn5Vw3TlukeorrEoKnhVUbfetBhXhpoucNnjA7fS', 'admin'),
(15, 'Bhuvnesh Patil', '$2y$10$OAbbkDRPiNRQNksUCjW3v.OsDuckY.13Hp/j8bki3P9.8uklCedlW', 'admin'),
(16, 'dev@gamil.com', '$2y$10$Q62ElZVB05r8vwvUPYsA6.8VeGO08q711oB4N.sO/N6PY79vz6wEm', 'employee'),
(17, 'dev', '$2y$10$Tk8GAzXF5rY26v12uwHzrObHbkY/SeguNzN3wVJlmwiiJzf6.cd2e', 'admin'),
(18, 'shark', '$2y$10$6yBr6yDPxWt8pqAUeyW6dewFbShMcN.6IuobkRCrxNap9yrKtguUK', 'employee'),
(19, '123456', '$2y$10$/1oAQItXqxvpbik8e3Z7ZOTtLTWW/1HfOB6AL0lnbHO/PB.0VdkAq', 'admin'),
(20, 'yogesh', '$2y$10$R3/39VvqppaPrD0Nn4j0pOnxnVpHsUlEs7A5T1D6CGz9fk6TdgehG', 'admin'),
(21, 'yogesh patil', '$2y$10$doza62KovSo1ZrX9U4dYduni/vdgS7mr/KKcBoO8RIC1P2DplYstC', 'admin'),
(22, 'dipesh patil', '$2y$10$40ry3aQDPBavqYUQeDiucenqluaIUVLDDJhJMiwgsTBGkuMKYYw36', 'employee'),
(23, 'dipesh', '$2y$10$AuPhd74T5y2BtbDPLmpFgOPk9HLDDjjVv3IR09AkObqak/HRlgkyy', 'admin'),
(24, 'bhuvnesh_exe', '$2y$10$40OJ1vduxWNT92YiEm33ruYG5M1xpm54xelK11ivpJdZpPzqSOh6W', 'admin'),
(25, 'salar', '$2y$10$8f6BbfkWu5WR.U/UFx9QOOfCfWZSZ6LDpnXhApUFvSWOJfZCL5Xfe', 'admin'),
(26, 'salar2', '$2y$10$jDYss21kjxN11ZMFPbdMdOpOqVA0iYbKzcQ40VmCKf1.uXXU8Hrlq', 'employee'),
(27, 'Sahil Shet', '$2y$10$730Fc2VALc.9c984eIsx4Oy0dT8E66EO.iE0esXe3t0./Fj5cPcaO', 'admin'),
(28, 'Bhuvnesh', '$2y$10$fqTrkdkltThWvHo9UN/acukR4jvqCYYwzAl9eb2eBkUtCox6FL/Em', 'employee'),
(29, 'admin', '$2y$10$eb8QnQNGzyWvy02eyOJuEe9xNYr/caWJ3Magqtw83IceYmYyVl1Vq', 'admin'),
(30, 'employee', '$2y$10$k45Zu1SbYQPvqYmeS1hdkOBjvv5LyYAHxAC.PEREqArZzkCCjmYCy', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities1`
--
ALTER TABLE `activities1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
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
-- AUTO_INCREMENT for table `activities1`
--
ALTER TABLE `activities1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
