-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2026 at 09:37 AM
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
-- Database: `portfolio_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(2, 'admin', '$2y$10$7o365fnpv/eINLkEbB2D4eis0e56vxK5RAXBR5FEwvULiJgAC4wNO');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `sent_at`) VALUES
(1, 'Abduhamid Mirzaahmedov', 'mirzaahmedovabdulhamid@gmail.com', 'new project', 'hello i have new project', '2026-05-14 05:19:13'),
(2, 'Abduhamid Mirzaahmedov', 'mirzaahmedovabdulhamid@gmail.com', 'new project', 'hello i have new project', '2026-05-14 05:21:11'),
(3, 'Abduhamid Mirzaahmedov', 'mirzaahmedovabdulhamid@gmail.com', 'new project', 'hello i have new project', '2026-05-14 05:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `tech_stack` varchar(200) DEFAULT NULL,
  `github_link` varchar(300) DEFAULT NULL,
  `live_link` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `tech_stack`, `github_link`, `live_link`, `created_at`) VALUES
(4, 'Layout Detection', 'This project is about detection layouts in documents. I trained Yolo8v model with my dataset. Current version of this project can detect list, text, header and graph.', 'Python, Yolov, Streamlit', 'https://github.com/ABDULHAM1D0/Layout_detection', '', '2026-05-14 07:07:48'),
(5, 'Resume Matcher', 'This project is a CV and Job Description Matching Model that calculates the similarity between a candidate\'s CV and a job description. It is built in Python using NLP and machine learning libraries and provides a similarity score to help assess candidate-job fit.', 'Python, NLP, Transformers, Machine Learning', 'https://github.com/ABDULHAM1D0/Resume-Matcher', '', '2026-05-14 07:08:36'),
(6, 'Power Allocator', 'This project focuses on power allocation in NOMA systems to address one of their key challenges: interference between users. NOMA improves spectral efficiency by allowing multiple users to share the same frequency resources, but this comes at the cost of increased interference. The goal of this research-based project is to reduce interference rates by learning optimal power allocation strategies using machine learning.', 'Python, Machine Learning', 'https://github.com/ABDULHAM1D0/Power-Allocator-in-NOMA', '', '2026-05-14 07:09:04'),
(7, 'AI chatbot', 'This project is an AI-powered chatbot that can generate English language questions in a multiple-choice format. It is designed to interact through a console interface, with plans to add a user-friendly UI in future updates.', 'Python, Api', 'https://github.com/ABDULHAM1D0/chat-and-quiz-with-AI', '', '2026-05-14 07:10:06'),
(8, 'Face Recognition', 'This project recognize specific people who are in our database. I created embedding dataset that save person\'s vector embeddings. From photo and video, my model detect faces, then it convert them into vectors, then it will compare them embeddings with database embeddings. in this way it can compare person and give us result. I used insightface, deepface, MTCNN, cosine_similarity models and functions.', 'Python, Insighface, Transformers, Gradio', 'https://github.com/ABDULHAM1D0?tab=repositories', '', '2026-05-14 07:13:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
