DELIMITER $$
DROP PROCEDURE IF EXISTS spGetAdherentesWithPaged$$
CREATE PROCEDURE spGetAdherentesWithPaged(
	in rows_quantity int,
	in page int,
	out total_pages int
)
BEGIN
	-- calculo el total de páginas
	set @total_rows := (select COUNT(*) FROM adherentes);
	set total_pages = @total_rows / rows_quantity;
	
	-- calculo el offset para la query
	set @calculated_page = rows_quantity * page;
	
	
	
	-- ejecuta la consulta parametrizada
	set @sql = CONCAT('select * from adherentes limit ', rows_quantity , ' offset ', @calculated_page);
	prepare stmt1 FROM @sql;
	execute stmt1;


END$$
DELIMITER ;

