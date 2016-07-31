
Create Table

CREATE TABLE `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_amount` decimal(10,0) DEFAULT NULL,
  `property_value` decimal(10,0) DEFAULT NULL,
  `ssn` varchar(50) DEFAULT NULL,
  `date_created` varchar(15) DEFAULT NULL,
  `application_id` varchar(50) DEFAULT NULL,
  `loan_status` varchar(50) DEFAULT 'In consideration',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1
