ET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dp1sep2016`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `vendita`
--

CREATE TABLE `vendita` (
    `id` int(11) NOT NULL,
      `quantity` int(11) NOT NULL,
      `price` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `vendita`
--

INSERT INTO `vendita` (`id`, `quantity`, `price`) VALUES
(46, 1, 1050),
(47, 8, 1100),
(48, 6, 1150),
(49, 15, 1200);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vendita`
--
ALTER TABLE `vendita`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vendita`
--
ALTER TABLE `vendita`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;
