# farmdata

'''

DROP TABLE IF EXISTS `areas`;
CREATE TABLE IF NOT EXISTS `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` text NOT NULL,
  `location` text DEFAULT NULL,
  `area` decimal(10,2) NOT NULL DEFAULT 0.00,
  `manager` int(11) DEFAULT NULL,
  `monthly_rent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `leased_until` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

DROP TABLE IF EXISTS `designation`;
CREATE TABLE IF NOT EXISTS `designation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doe` timestamp NOT NULL DEFAULT current_timestamp(),
  `desig` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doe` timestamp NOT NULL DEFAULT current_timestamp(),
  `fname` text DEFAULT NULL,
  `lname` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `phone` varchar(13) NOT NULL,
  `joined_on` date DEFAULT NULL,
  `designation` int(11) DEFAULT NULL,
  `pword` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;


CREATE TABLE IF NOT EXISTS `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doe` timestamp NOT NULL DEFAULT current_timestamp(),
  `assetname` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doe` timestamp NOT NULL DEFAULT current_timestamp(),
  `resourcename` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doe` timestamp NOT NULL DEFAULT current_timestamp(),
  `activity` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



'''