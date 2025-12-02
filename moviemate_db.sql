-- Create Database
CREATE DATABASE IF NOT EXISTS moviemate_db;
USE moviemate_db;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Movies Table
CREATE TABLE movies_details (
    movie_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    language VARCHAR(50),
    release_date DATE,
    genre VARCHAR(50),
    rating DECIMAL(3,1) CHECK (rating >= 0 AND rating <= 10),
    poster_url VARCHAR(255),
    description TEXT
);

-- Cast Details Table
CREATE TABLE `cast_details` (
  `cast_id` INT AUTO_INCREMENT PRIMARY KEY,
  `movie_id` INT NOT NULL,
  `actor_name` VARCHAR(100) NOT NULL,
  `role_name` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`movie_id`) REFERENCES `movies_details`(`movie_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Theaters Table
CREATE TABLE theaters (
    theater_id INT AUTO_INCREMENT PRIMARY KEY,
    theater_name VARCHAR(100) NOT NULL,
    theater_location VARCHAR(100),
    ticket_price DECIMAL(10,2) NOT NULL DEFAULT 200
);

-- Showtimes Table
CREATE TABLE showtimes (
    show_id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    theater_id INT NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies_details(movie_id) ON DELETE CASCADE,
    FOREIGN KEY (theater_id) REFERENCES theaters(theater_id) ON DELETE CASCADE
);

-- Booking Table
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    show_id INT NOT NULL,
    seat_row VARCHAR(255) NOT NULL,
    total_seat INT NOT NULL,
    ticket_price DECIMAL(10,2) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    booking_status ENUM('Pending', 'Approved', 'Cancelled') DEFAULT 'Pending',
    booking_date DATE NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (movie_id) REFERENCES movies_details(movie_id) ON DELETE CASCADE,
    FOREIGN KEY (show_id) REFERENCES showtimes(show_id) ON DELETE CASCADE
);

-- Payments Table
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_method ENUM('UPI', 'Card', 'Cash') NOT NULL,
    payment_status ENUM('Pending', 'Confirmed', 'Failed') DEFAULT 'Pending',
    payment_message VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
);

-- Insert Records into movies_details Table
INSERT INTO `movies_details` (`movie_id`, `title`, `language`, `release_date`, `genre`, `rating`, `poster_url`, `description`) VALUES
(1, 'Singham Again', 'Hindi', '2025-03-07', 'Action', 8.3, 'https://upload.wikimedia.org/wikipedia/en/thumb/0/04/Singham_Again_poster.jpg/250px-Singham_Again_poster.jpg', 'Rohit Shetty returns with another powerful entry in the Singham franchise. Ajay Devgn reprises his role as the fearless cop Bajirao Singham who takes on a new wave of crime and corruption. Packed with intense action, emotional depth, and a star-studded police universe, the film promises pure adrenaline.'),
(2, 'Bhoot Police', 'Hindi', '2021-09-10', 'Horror', 6.8, 'https://upload.wikimedia.org/wikipedia/en/4/4f/Bhoot_Police_film_poster.jpg', 'Two brothers who run a fake ghost-hunting business get caught in a real supernatural encounter in the hills of Himachal. A horror-comedy starring Saif Ali Khan and Arjun Kapoor, the film mixes laughs and chills in equal measure. The scenic visuals and quirky characters add to its spooky charm.'),
(3, 'Housefull 5', 'Hindi', '2025-06-06', 'Comedy', 6.5, 'https://m.media-amazon.com/images/M/MV5BZmIzMThjNTYtNjkwZi00NmM3LTliNGItZWIxYTUwMGU1YzM0XkEyXkFqcGc@._V1_.jpg', 'The fifth installment in the Housefull franchise brings back familiar faces and fresh chaos. A madcap comedy of errors involving mistaken identities, lavish weddings, and wild adventures. With larger-than-life sets and over-the-top humor, it guarantees non-stop entertainment.'),
(4, 'War 2', 'Hindi', '2025-08-14', 'Action', 8.1, 'https://assets-in.bmscdn.com/iedb/movies/images/mobile/thumbnail/xlarge/war-2-et00356501-1755672553.jpg', 'Hrithik Roshan returns as Kabir in the high-octane sequel to War, teaming up with Jr NTR in a globe-trotting espionage thriller. With international action sequences and intense drama, it raises the stakes for Indian spy films. Expect fast-paced missions, betrayals, and explosive face-offs.'),
(5, 'Adhura', 'Hindi', '2024-07-15', 'Horror', 7.1, 'https://upload.wikimedia.org/wikipedia/en/thumb/0/02/Adhura_Poster.jpg/250px-Adhura_Poster.jpg', 'Set in an elite boarding school, Adhura unravels a terrifying mystery surrounding a missing child and a series of haunting events. As hidden truths surface, fear grips the corridors of the school. A psychological horror that blends emotional trauma with supernatural dread.'),
(6, '12th Fail', 'Hindi', '2023-10-27', 'Drama', 8.1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcToMxf_SsfLRqnKwc2ubQlH8Ii6Ede0cvhBTw&s', 'This inspiring drama tells the story of Manoj Sharma, who rose from poverty to become an IPS officer. The film beautifully captures the spirit of perseverance, failure, and redemption. Based on a true story, it celebrates the triumph of hard work and hope.'),
(7, 'Jawan', 'Hindi', '2023-09-07', 'Action', 7.2, 'https://resizing.flixster.com/lej1aNFjcromN2hYS5-638hSJ-k=/ems.cHJkLWVtcy1hc3NldHMvbW92aWVzL2FiOWE5MWYxLTc0MzctNGNjZi1hMjE0LWNhZmZiMDU2M2RhMS5qcGc=', 'Shah Rukh Khan takes center stage in Atlee’s high-octane action thriller Jawan, portraying a vigilante with a mysterious past. The film combines social justice themes with massive action sequences. With powerful performances and mass appeal, it became one of 2023’s biggest blockbusters.'),
(8, 'Golmaal Again', 'Hindi', '2021-08-15', 'Comedy', 8.5, 'https://upload.wikimedia.org/wikipedia/en/4/49/Ajay_Devgn%27s_Golmaal_Again_poster.jpg', 'The gang is back with even more laughter and ghostly mayhem in this horror-comedy ride. When they encounter a haunted mansion, chaos and laughter follow. A mix of humor, heart, and supernatural fun keeps audiences entertained throughout.'),
(9, 'Toxic', 'Hindi', '2025-04-10', 'Thriller', 7.2, 'https://m.media-amazon.com/images/M/MV5BMDZiNzAwZTQtYWIwMC00ODA0LWJiOGMtZTgzZGYzYzMxMDNiXkEyXkFqcGc@._V1_.jpg', 'Yash and Nayanthara headline this intense gangster thriller set in the gritty underworld. Exploring themes of loyalty and betrayal, the film showcases brutal power struggles. With gripping tension and stylized violence, it delivers an edge-of-the-seat experience.'),
(10, 'Fateh', 'Hindi', '2025-01-10', 'Action', 7.2, 'https://assets-in.bmscdn.com/iedb/movies/images/mobile/thumbnail/xlarge/fateh-et00391731-1734092649.jpg', 'Sonu Sood’s directorial debut features him as an ex-intelligence officer drawn back into action to dismantle a cyber mafia. Tackling cybercrime and digital warfare, the movie mixes technology with emotion. It’s a slick, action-packed film with a patriotic pulse.'),
(11, 'Kantara Chapter 1', 'Hindi', '2025-10-02', 'Thriller', 7.5, 'https://upload.wikimedia.org/wikipedia/en/thumb/6/69/Kantara-_Chapter_1_poster.jpg/250px-Kantara-_Chapter_1_poster.jpg', 'A spiritual prequel to the acclaimed Kantara, this chapter dives into the roots of divine justice and folklore. With stunning visuals and powerful performances, it’s a blend of mythology and mystery. The movie offers an immersive and haunting storytelling experience.'),
(12, 'Raid 2', 'Hindi', '2025-05-01', 'Action', 7.8, 'https://stat4.bollywoodhungama.in/wp-content/uploads/2021/12/Raid2-1.jpg', 'A direct continuation of the first film, Raid 2 follows the relentless Income Tax officer exposing powerful figures. The movie raises the intensity with deeper corruption and higher stakes. It’s a hard-hitting action drama about integrity versus power.'),
(13, 'Pushpa 2: The Rule', 'Hindi', '2025-01-14', 'Thriller', 8.9, 'https://m.media-amazon.com/images/M/MV5BNDM3N2UzM2UtMjEwMC00NGUzLThmMmQtNGMyM2VmMDA0ZWEwXkEyXkFqcGc@._V1_.jpg', 'Allu Arjun returns as Pushpa Raj in this explosive sequel, continuing his rise in the red sandalwood underworld. The film amplifies the action, emotions, and rivalries that defined the first part. Expect intense storytelling and power-packed performances.'),
(14, 'Sikandar', 'Hindi', '2025-04-01', 'Action', 7.3, 'https://upload.wikimedia.org/wikipedia/en/4/4a/Sikandar_2025_film_poster.jpg', 'Salman Khan stars as a lone warrior caught between crime, politics, and revenge in this high-voltage entertainer. Directed by AR Murugadoss, Sikandar combines raw emotion with stylized action. It’s a blend of power-packed drama and patriotic fervor.'),
(15, 'Shaitaan', 'Hindi', '2024-03-08', 'Horror', 7.4, 'https://m.media-amazon.com/images/M/MV5BOTdlZGE5YmUtZDE1Ny00NzUzLTg2YzYtNWYyMzgyNzRiY2EzXkEyXkFqcGc@._V1_.jpg', 'Ajay Devgn and R. Madhavan star in this gripping supernatural thriller. A family’s peaceful life turns dark when a stranger enters their home, revealing sinister secrets. The film skillfully blends psychological fear and dark mysticism.'),
(16, 'Chhori 2', 'Hindi', '2024-09-20', 'Horror', 7.6, 'https://m.media-amazon.com/images/M/MV5BNTFhOTE4MWItZTdmZS00NTI0LTliM2ItNTM4ZjM5MjE0MTYxXkEyXkFqcGc@._V1_.jpg', 'Sequel to the chilling Chhori, this story delves deeper into a cursed village’s horrifying secrets. As the protagonist faces her inner and outer demons, she must protect her unborn child. The movie heightens the terror with an emotional and spine-chilling twist.'),
(17, 'Dragon', 'Hindi', '2025-02-21', 'Thriller', 7.9, 'https://resizing.flixster.com/idSqXXW1SHplGNnq6W67KnkK-_s=/ems.cHJkLWVtcy1hc3NldHMvbW92aWVzLzQyMWQ0OTJhLThkYjYtNDY0MS1hMDNhLTU4NDk3YWExMDllMy5qcGc=', 'This fantasy-thriller set in a mythical kingdom follows a young hero destined to restore balance. With breathtaking visuals and intense battles, it’s a spectacle of courage and fate. The film mixes folklore and fantasy with modern cinematic flair.'),
(18, 'Vash 2', 'Hindi', '2025-09-12', 'Horror', 7.7, 'https://assets-in.bmscdn.com/iedb/movies/images/mobile/thumbnail/xlarge/vash-level-2-et00430860-1755154833.jpg', 'A chilling continuation of Shaitaan’s universe, Vash 2 explores the dark world of black magic and human fear. As a new family becomes entangled in curses, survival becomes a desperate struggle. The film raises psychological horror to a new level.'),
(19, 'OMG 2', 'Hindi', '2025-08-01', 'Comedy', 7.3, 'https://upload.wikimedia.org/wikipedia/en/thumb/5/56/OMG_2_%E2%80%93_Oh_My_God%21_2_poster.jpg/250px-OMG_2_%E2%80%93_Oh_My_God%21_2_poster.jpg', 'Akshay Kumar returns in this thought-provoking satirical comedy exploring sex education and societal hypocrisy. With Pankaj Tripathi’s heartfelt performance, the film balances humor with meaningful commentary. It’s a bold, emotional, and enlightening entertainer.'),
(20, 'Drishyam 2', 'Hindi', '2025-07-19', 'Thriller', 7.8, 'https://filmik.blog/wp-content/uploads/2022/11/Drishyam-2-Movie-.webp', 'Ajay Devgn returns as Vijay Salgaonkar in this gripping sequel to the 2015 hit. As new evidence emerges, his perfect crime begins to crack, testing his wits once again. A masterful thriller filled with suspense, emotion, and shocking twists.');

-- Insert Records into cast_details Table
INSERT INTO `cast_details` (`movie_id`, `actor_name`, `role_name`) VALUES
-- 1. Singham Again
(1, 'Ajay Devgn', 'Bajirao Singham'),
(1, 'Kareena Kapoor Khan', 'Avni Singham'),
(1, 'Ranveer Singh', 'Simmba'),
(1, 'Akshay Kumar', 'Veer Sooryavanshi'),

-- 2. Bhoot Police
(2, 'Saif Ali Khan', 'Vibhooti'),
(2, 'Arjun Kapoor', 'Chiraunji'),
(2, 'Yami Gautam', 'Maya'),
(2, 'Jacqueline Fernandez', 'Kanika'),

-- 3. Housefull 5
(3, 'Akshay Kumar', 'Sandy'),
(3, 'Riteish Deshmukh', 'Bala'),
(3, 'Kriti Sanon', 'Kriti'),
(3, 'Pooja Hegde', 'Pooja'),

-- 4. War 2
(4, 'Hrithik Roshan', 'Kabir'),
(4, 'Jr NTR', 'Rudra'),
(4, 'Kiara Advani', 'Agent Maya'),
(4, 'Anil Kapoor', 'Colonel Rathore'),

-- 5. Adhura
(5, 'Rasika Dugal', 'Supriya'),
(5, 'Ishwak Singh', 'Adhiraj'),
(5, 'Shrenik Arora', 'Vedant'),
(5, 'Rahul Dev', 'Principal'),

-- 6. 12th Fail
(6, 'Vikrant Massey', 'Manoj Sharma'),
(6, 'Medha Shankar', 'Shraddha Joshi'),
(6, 'Anant V Joshi', 'Pritam Pandey'),

-- 7. Jawan
(7, 'Shah Rukh Khan', 'Azad Rathore / Vikram Rathore'),
(7, 'Nayanthara', 'Narmada Rai'),
(7, 'Vijay Sethupathi', 'Kaali Gaikwad'),
(7, 'Deepika Padukone', 'Aishwarya Rathore'),

-- 8. Golmaal Again
(8, 'Ajay Devgn', 'Gopal'),
(8, 'Arshad Warsi', 'Madhav'),
(8, 'Tusshar Kapoor', 'Lucky'),
(8, 'Tabu', 'Anna'),

-- 9. Toxic
(9, 'Yash', 'Ravi'),
(9, 'Nayanthara', 'Meera'),
(9, 'Prithviraj Sukumaran', 'Arjun'),
(9, 'R. Madhavan', 'ACP Rajan'),

-- 10. Fateh
(10, 'Sonu Sood', 'Kabir'),
(10, 'Jacqueline Fernandez', 'Riya'),
(10, 'Nora Fatehi', 'Maya'),
(10, 'Vijay Raaz', 'Cyber Expert'),

-- 11. Kantara Chapter 1
(11, 'Rishab Shetty', 'Devendra'),
(11, 'Saptami Gowda', 'Leela'),
(11, 'Kishore', 'Village Head'),
(11, 'Achyuth Kumar', 'Priest'),

-- 12. Raid 2
(12, 'Ajay Devgn', 'Amay Patnaik'),
(12, 'Ileana D’Cruz', 'Malini Patnaik'),
(12, 'Saurabh Shukla', 'Rameshwar Singh'),
(12, 'Kumud Mishra', 'CBI Officer'),

-- 13. Pushpa 2: The Rule
(13, 'Allu Arjun', 'Pushpa Raj'),
(13, 'Rashmika Mandanna', 'Srivalli'),
(13, 'Fahadh Faasil', 'Bhanwar Singh Shekhawat'),
(13, 'Sunil', 'Mangalam Srinu'),

-- 14. Sikandar
(14, 'Salman Khan', 'Sikandar'),
(14, 'Rashmika Mandanna', 'Aisha'),
(14, 'Suniel Shetty', 'ACP Ranbir'),
(14, 'Prakash Raj', 'Minister Rao'),

-- 15. Shaitaan
(15, 'Ajay Devgn', 'Kabir'),
(15, 'R. Madhavan', 'Vanraj'),
(15, 'Jyothika', 'Neha'),
(15, 'Janki Bodiwala', 'Jhanvi'),

-- 16. Chhori 2
(16, 'Nushrratt Bharuccha', 'Sakshi'),
(16, 'Soha Ali Khan', 'Reema'),
(16, 'Saurabh Goyal', 'Hemant'),

-- 17. Dragon
(17, 'Ranbir Kapoor', 'Arjun'),
(17, 'Alia Bhatt', 'Riya'),
(17, 'Amitabh Bachchan', 'Guru Dev'),
(17, 'Disha Patani', 'Tara'),

-- 18. Vash 2
(18, 'Hiten Kumar', 'Atharva'),
(18, 'Janki Bodiwala', 'Nisha'),
(18, 'Niilam Panchal', 'Seema'),
(18, 'Hitu Kanodia', 'Inspector Rakesh'),

-- 19. OMG 2
(19, 'Akshay Kumar', 'Messenger of Lord Shiva'),
(19, 'Pankaj Tripathi', 'Kanti Sharan Mudgal'),
(19, 'Yami Gautam', 'Advocate Kamini Maheshwari'),
(19, 'Aarush Varma', 'Vivek'),

-- 20. Drishyam 2
(20, 'Ajay Devgn', 'Vijay Salgaonkar'),
(20, 'Tabu', 'IG Meera Deshmukh'),
(20, 'Shriya Saran', 'Nandini Salgaonkar'),
(20, 'Akshaye Khanna', 'IG Tarun Ahlawat');

-- Insert Records into Theaters Table
INSERT INTO `theaters` (`theater_id`, `theater_name`, `ticket_price`, `theater_location`) VALUES
(1, 'INOX Raj Imperial', 250.00, 'Surat - Piplod'),
(2, 'PVR RahulRaj Mall', 380.00, 'Surat - Dumas Road'),
(3, 'Valentine Multiplex', 320.00, 'Surat - Dumas Road'),
(4, 'Cinemax VR Mall', 350.00, 'Surat - VR Mall'),
(5, 'INOX Reliance Mall', 240.00, 'Surat - Varachha'),
(6, 'Rajhans Cinemas', 200.00, 'Surat - Adajan'),
(7, 'Time Cinema', 300.00, 'Surat - Ring Road');

-- Insert Records into Show Time Table
INSERT INTO `showtimes` (`movie_id`, `theater_id`, `show_date`, `show_time`) VALUES
-- 1. Singham Again
(1, 1, CURDATE(), '10:00:00'),
(1, 2, CURDATE(), '13:00:00'),
(1, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(1, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(1, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(1, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(1, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 2. Bhoot Police
(2, 1, CURDATE(), '10:00:00'),
(2, 2, CURDATE(), '13:00:00'),
(2, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(2, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(2, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(2, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(2, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 3. Housefull 5
(3, 1, CURDATE(), '10:00:00'),
(3, 2, CURDATE(), '13:00:00'),
(3, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(3, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(3, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(3, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(3, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 4. War 2
(4, 1, CURDATE(), '10:00:00'),
(4, 2, CURDATE(), '13:00:00'),
(4, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(4, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(4, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(4, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(4, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 5. Adhura
(5, 1, CURDATE(), '10:00:00'),
(5, 2, CURDATE(), '13:00:00'),
(5, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(5, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(5, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(5, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(5, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 6. 12th Fail
(6, 1, CURDATE(), '10:00:00'),
(6, 2, CURDATE(), '13:00:00'),
(6, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(6, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(6, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(6, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(6, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 7. Jawan
(7, 1, CURDATE(), '10:00:00'),
(7, 2, CURDATE(), '13:00:00'),
(7, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(7, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(7, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(7, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(7, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 8. Golmaal Again
(8, 1, CURDATE(), '10:00:00'),
(8, 2, CURDATE(), '13:00:00'),
(8, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(8, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(8, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(8, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(8, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 9. Toxic
(9, 1, CURDATE(), '10:00:00'),
(9, 2, CURDATE(), '13:00:00'),
(9, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(9, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(9, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(9, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(9, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 10. Fateh
(10, 1, CURDATE(), '10:00:00'),
(10, 2, CURDATE(), '13:00:00'),
(10, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(10, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(10, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(10, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(10, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 11. Kantara Chapter 1
(11, 1, CURDATE(), '10:00:00'),
(11, 2, CURDATE(), '13:00:00'),
(11, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(11, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(11, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(11, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(11, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 12. Raid 2
(12, 1, CURDATE(), '10:00:00'),
(12, 2, CURDATE(), '13:00:00'),
(12, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(12, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(12, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(12, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(12, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 13. Pushpa 2
(13, 1, CURDATE(), '10:00:00'),
(13, 2, CURDATE(), '13:00:00'),
(13, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(13, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(13, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(13, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(13, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 14. Sikandar
(14, 1, CURDATE(), '10:00:00'),
(14, 2, CURDATE(), '13:00:00'),
(14, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(14, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(14, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(14, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(14, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 15. Shaitaan
(15, 1, CURDATE(), '10:00:00'),
(15, 2, CURDATE(), '13:00:00'),
(15, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(15, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(15, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(15, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(15, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 16. Chhori 2
(16, 1, CURDATE(), '10:00:00'),
(16, 2, CURDATE(), '13:00:00'),
(16, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(16, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(16, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(16, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(16, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 17. Dragon
(17, 1, CURDATE(), '10:00:00'),
(17, 2, CURDATE(), '13:00:00'),
(17, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(17, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(17, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(17, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(17, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 18. Vash 2
(18, 1, CURDATE(), '10:00:00'),
(18, 2, CURDATE(), '13:00:00'),
(18, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(18, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(18, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(18, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(18, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 19. OMG 2
(19, 1, CURDATE(), '10:00:00'),
(19, 2, CURDATE(), '13:00:00'),
(19, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(19, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(19, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(19, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(19, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00'),

-- 20. Drishyam 2
(20, 1, CURDATE(), '10:00:00'),
(20, 2, CURDATE(), '13:00:00'),
(20, 3, CURDATE() + INTERVAL 1 DAY, '16:00:00'),
(20, 4, CURDATE() + INTERVAL 1 DAY, '19:00:00'),
(20, 5, CURDATE() + INTERVAL 2 DAY, '22:00:00'),
(20, 6, CURDATE() + INTERVAL 2 DAY, '12:00:00'),
(20, 7, CURDATE() + INTERVAL 3 DAY, '21:00:00');
