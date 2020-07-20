DELIMITER $$

DROP EVENT IF EXISTS test_event $$

CREATE DEFINER=`root`@`%` 
EVENT test_event 
ON SCHEDULE EVERY 1 MINUTE STARTS NOW() 
 
DO 
BEGIN

  select "ok";
 
END $$