DELIMITER $$
DROP PROCEDURE IF EXISTS spGetViewWithFilter$$
CREATE PROCEDURE spGetViewWithFilter(
	in view_name varchar(80),
	in column_name varchar(80),
	in text_to_find varchar(80),
	in rows_quantity int,
	in page int,
	-- out total_pages int,
	out total_rows int
)
BEGIN
	set max_sp_recursion_depth=255;
	
	set @final_text = CONCAT ('%',text_to_find,'%');	
			
	-- ejecuta la consulta parametrizada   
	set @sql = CONCAT('create temporary table my_temp SELECT * FROM ', view_name ,' where ', column_name , ' like ', "'", @final_text ,"';");
	prepare stmt1 FROM @sql;
	execute stmt1;	
	
 
	set total_rows  = (select COUNT(*) FROM my_temp);
	-- set total_pages = total_rows / rows_quantity;
	
	-- aplico paginado a la tabla temporal
	call spGetViewWithPaged ('my_temp',rows_quantity,page);
		
END$$
DELIMITER ;

