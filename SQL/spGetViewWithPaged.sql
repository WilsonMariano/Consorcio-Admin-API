DELIMITER $$
DROP PROCEDURE IF EXISTS spGetViewWithPaged$$
CREATE PROCEDURE spGetViewWithPaged(
	in view_name varchar(80),
	in rows_quantity int,
	in page int
)
BEGIN

	set max_sp_recursion_depth = 255;

	-- calculo el offset para la query
	set @calculated_page = rows_quantity * page;

		
	-- ejecuta la consulta parametrizada
	set @sql = CONCAT('select * from ', view_name ,' limit ', rows_quantity , ' offset ', @calculated_page);
	prepare stmt1 FROM @sql;
	execute stmt1;

		
END$$
DELIMITER ;

