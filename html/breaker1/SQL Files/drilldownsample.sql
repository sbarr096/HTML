-- MySQL commands to create and populate circuit breaker# data --
--
-- --------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS breaker1;
USE breaker1;



DROP TABLE IF EXISTS `monthly_kWh`;
CREATE TABLE `monthly_kWh` (
  `Month` varchar(50) NOT NULL,
  `Watts` int(10) unsigned NOT NULL,
  `Year` int(10) unsigned NOT NULL,
  `Quarter` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`Year`,`Quarter`,`Month`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `monthly_kWh` (`Month`,`Sales`,`Year`,`Quarter`) VALUES 
 ('Feb',800,2011,'Q1'),
 ('Jan',888,2011,'Q1'),
 ('Mar',912,2011,'Q1'),
 ('Apr',777,2011,'Q2'),
 ('Jun',888,2011,'Q2'),
 ('May',999,2011,'Q2'),
 ('Aug',900,2011,'Q3'),
 ('Jul',800,2011,'Q3'),
 ('Sep',700,2011,'Q3'),
 ('Dec',600,2011,'Q4'),
 ('Nov',990,2011,'Q4'),
 ('Oct',980,2011,'Q4'),
 ('Feb',0,2012,'Q1'),
 ('Jan',0,2012,'Q1'),
 ('Mar',0,2012,'Q1'),
 ('Apr',0,2012,'Q2'),
 ('Jun',0,2012,'Q2'),
 ('May',0,2012,'Q2'),
 ('Aug',0,2012,'Q3'),
 ('Jul',0,2012,'Q3'),
 ('Sep',0,2012,'Q3'),
 ('Dec',0,2012,'Q4'),
 ('Nov',0,2012,'Q4'),
 ('Oct',0,2012,'Q4'),
 ('Feb',0,2013,'Q1'),
 ('Jan',00,2013,'Q1'),
 ('Mar',0,2013,'Q1'),
 ('Apr',900,2013,'Q2'),
 ('Jun',5,2013,'Q2'),
 ('May',1565,2013,'Q2'),
 ('Aug',1246,2013,'Q3'),
 ('Jul',1200,2013,'Q3'),
 ('Sep',1090,2013,'Q3'),
 ('Dec',1520,2013,'Q4'),
 ('Nov',1500,2013,'Q4'),
 ('Oct',980,2013,'Q4'),
 ('Feb',1600,2014,'Q1'),
 ('Jan',1875,2014,'Q1'),
 ('Mar',1565,2014,'Q1'),
 ('Apr',2389,2014,'Q2'),
 ('Jun',1922,2014,'Q2'),
 ('May',1289,2014,'Q2'),
 ('Aug',1854,2014,'Q3'),
 ('Jul',2006,2014,'Q3'),
 ('Sep',1100,2014,'Q3'),
 ('Dec',2188,2014,'Q4'),
 ('Nov',1500,2014,'Q4'),
 ('Oct',875,2014,'Q4'),
 ('Feb',3965,2015,'Q1'),
 ('Jan',4087,2015,'Q1'),
 ('Mar',2684,2015,'Q1'),
 ('Apr',2983,2015,'Q2'),
 ('Jun',2315,2015,'Q2'),
 ('May',3265,2015,'Q2'),
 ('Aug',3998,2015,'Q3'),
 ('Jul',3215,2015,'Q3'),
 ('Sep',3787,2015,'Q3'),
 ('Dec',2148,2015,'Q4'),
 ('Nov',2654,2015,'Q4'),
 ('Oct',4098,2015,'Q4'),
 ('Feb',3965,2016,'Q1'),
 ('Jan',2983,2016,'Q1'),
 ('Mar',2952,2016,'Q1'),
 ('Apr',3998,2016,'Q2'),
 ('Jun',2737,2016,'Q2'),
 ('May',3265,2016,'Q2'),
 ('Aug',3787,2016,'Q3'),
 ('Jul',3215,2016,'Q3'),
 ('Sep',4171,2016,'Q3'),
 ('Dec',1256,2016,'Q4'),
 ('Nov',3566,2016,'Q4'),
 ('Oct',4078,2016,'Q4');


DROP TABLE IF EXISTS `quarterly_kWh`;
CREATE TABLE `quarterly_kWh` (
  `Quarter` varchar(20) NOT NULL,
  `Watts` varchar(20) NOT NULL,
  `Year` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `quarterly_wKh` (`Quarter`,`Sales`,`Year`) VALUES 
 ('Q1','2600','2011'),
 ('Q2','2664','2011'),
 ('Q3','2400','2011'),
 ('Q4','2570','2011'),
 ('Q1','0','2012'),
 ('Q2','0','2012'),
 ('Q3','0','2012'),
 ('Q4','0','2012'),
 ('Q1','0','2013'),
 ('Q2','0','2013'),
 ('Q2','0','2013'),
 ('Q4','0','2013'),
 ('Q1','0','2014'),
 ('Q2','0','2014'),
 ('Q3','0','2014'),
 ('Q4','0','2014'),
 ('Q1','0','2015'),
 ('Q2','0','2015'),
 ('Q3','0','2015'),
 ('Q4','0','2015'),
 ('Q1','0','2016'),
 ('Q2','0','2016'),
 ('Q3','0','2016'),
 ('Q4','0','2016');

DROP TABLE IF EXISTS `yearly_kWh`;
CREATE TABLE `yearly_kWh` (
  `Year` varchar(20) NOT NULL,
  `Sales` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `yearly_kWh` (`Year`,`Sales`) VALUES 
 ('2011','10234'),
 ('2012','0'),
 ('2013','0'),
 ('2014','0'),
 ('2015','0'),
 ('2016','0');

