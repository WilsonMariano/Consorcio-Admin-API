DELIMITER $$
DROP PROCEDURE IF EXISTS spGetPagedWithOptionalFilter$$
CREATE PROCEDURE spGetPagedWithOptionalFilter(
	/*
		@view_name		:	Nombre de la tabla o vista a consultar. 
		@column_1		:	Nombre de la columna para criterio de filtrado.
		@text_to_find_1 :	Texto a buscar en el primer criterio de filtrado.
		@column_2		:	Nombre de la columna para sub-consulta (segundo criterio de filtrado).
		@text_to_find_2 :	Texto a buscar en el segundo criterio de filtrado (sub-consulta).
		@rows_quantity 	:	Cantidad de filas a devolver en la consulta (se usa para paginar)
		@page 			:	Página actual a devolver en la consulta.
		@total_rows 	:	Cantidad total de filas resultantes. Párametro de salida
	*/
	in  view_name		varchar(80),
	in  column_1		varchar(80),
	in  text_to_find_1 	varchar(80),
	in  column_2		varchar(80),
	in  text_to_find_2 	varchar(80),
	in  rows_quantity 	int,
	in  page 			int,
	out total_rows 		int
)
BEGIN
	set max_sp_recursion_depth=255;
	drop table if exists my_temp;
	
	set @sql = CONCAT( "create temporary table my_temp SELECT * FROM ", view_name );
	
	IF  column_1 is not null and length(column_1) > 1 THEN 
		set @final_text_1 = CONCAT ( "%", text_to_find_1, "%" );
		set @sql = CONCAT( @sql, " where ", column_1, " like '", @final_text_1 , "'" );
		IF  column_2 is not null and length(column_2) > 1 THEN 
			set @final_text_2 = CONCAT ( "%", text_to_find_2, "%" );		
			set @sql = CONCAT( @sql, " and ", column_2, " like '", @final_text_2 , "'" );
		END IF;
    END IF;
	
	-- cierro la query con  ";"
	set @sql = CONCAT (@sql, ";");
	
	-- ejecuto la query , se crea tabla temporal
	prepare stmt1 FROM @sql;
	execute stmt1;	
	
	set total_rows  = (select COUNT(*) FROM my_temp);
		
	-- aplico paginado a la tabla temporal
	call spGetViewWithPaged ("my_temp", rows_quantity, page);
		
END$$
DELIMITER ;

