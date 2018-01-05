
CREATE PROCEDURE addcol() BEGIN
IF NOT EXISTS(
	SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'password' and table_schema = 'minecraft'
	)
	THEN
		ALTER TABLE users ADD password varchar(255);
END IF;
END;

CALL addcol();

DROP PROCEDURE addcol;

insert into users (name, groups, password) values ('admin','default', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3');