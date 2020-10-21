USE newDb;

CREATE TABLE IF NOT EXISTS recipes(
	recipe_id INT NOT NULL AUTO_INCREMENT,
	recipe_title VARCHAR(255),
	recipe_description VARCHAR(255),
	score INT,
	PRIMARY KEY (recipe_id)
);

CREATE TABLE IF NOT EXISTS recipe_ingredients(
	recipe_id INT NOT NULL,
	measurement_id INT,
	measurement_qty_id INT,
	ingredient_id INT,
	PRIMARY KEY (recipe_id)
);

CREATE TABLE IF NOT EXISTS measure_units(
	measurement_id INT NOT NULL AUTO_INCREMENT,
	measurement_desc VARCHAR(255),
	PRIMARY KEY (measurement_id)
);

CREATE TABLE IF NOT EXISTS  measure_quant(
	measurement_qty_id INT NOT NULL AUTO_INCREMENT,
	quant VARCHAR(255),
	PRIMARY KEY (measurement_qty_id)
);

CREATE TABLE IF NOT EXISTS ingredients(
	ingredient_id INT NOT NULL AUTO_INCREMENT,
	ingredient_name VARCHAR(255),
	PRIMARY KEY (ingredient_id)
);

ALTER TABLE recipes ADD FOREIGN KEY (recipe_id) REFERENCES recipe_ingredients (recipe_id);
ALTER TABLE recipe_ingredients ADD FOREIGN KEY (recipe_id) REFERENCES recipes (recipe_id);
ALTER TABLE recipe_ingredients ADD FOREIGN KEY (measurement_id) REFERENCES measure_units (measurement_id);
ALTER TABLE recipe_ingredients ADD FOREIGN KEY (measurement_qty_id) REFERENCES measure_quant (measurement_qty_id);
ALTER TABLE recipe_ingredients ADD FOREIGN KEY (ingredient_id) REFERENCES ingredients (ingredient_id);
ALTER TABLE measure_units ADD FOREIGN KEY (measurement_id) REFERENCES recipe_ingredients (measurement_id);
ALTER TABLE measure_quant ADD FOREIGN KEY (measurement_qty_id) REFERENCES recipe_ingredients (measurement_qty_id);
ALTER TABLE ingredients ADD FOREIGN KEY (ingredient_id) REFERENCES recipe_ingredients (ingredient_id);


/*

SELECT 
	r.recipe_title AS Recipe Name,
	r.recipe_description AS Recipe Description,
	z.measurement_qty_id AS Amount,
	u.measurement_desc AS Unit,
	i.ingredient_name AS Ingredient
FROM 
	recipes r
LEFT JOIN
	recipe_ingredients z on r.recipe_id = z.recipe_id
LEFT JOIN
	measure_units u on r.measurement_id = u.measurement_id
LEFT JOIN
	ingredients i on r.ingredient_id = i.ingredient_id
;
	
	
/*	
	
