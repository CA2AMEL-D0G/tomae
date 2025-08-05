/* tomaelogico: */

CREATE TABLE bebida (
    id_bebida INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_bebida TEXT,
    preço DECIMAL(10, 2),
    estoque INTEGER,
    metadados TEXT,
    fk_categoria_id_categoria INTEGER
);

CREATE TABLE admin (
    id_admin INTEGER PRIMARY KEY AUTO_INCREMENT,
    login TEXT,
    senha TEXT
);

CREATE TABLE categoria (
    id_categoria INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_categoria TEXT
);
 
ALTER TABLE bebida ADD CONSTRAINT FK_bebida_2
    FOREIGN KEY (fk_categoria_id_categoria)
    REFERENCES categoria (id_categoria)
    ON DELETE SET NULL;