CREATE TABLE room (
  id int(30) NOT NULL AUTO_INCREMENT,
  type varchar(50) NOT NULL,
  bedding varchar(50) NOT NULL,
  price decimal(10, 2),
  PRIMARY KEY (id)
);

INSERT INTO room (id, type, bedding, price) VALUES
(1, 'Standard Room', 'Single', 2200.00),
(2, 'Standard Room', 'Double', 2400.00),
(3, 'Standard Room', 'Triple', 2600.00),
(4, 'Standard Room', 'Quad', 2800.00),
(5, 'Superior Room', 'Single', 4200.00),
(6, 'Superior Room', 'Double',  4400.00),
(7, 'Superior Room', 'Triple',  4600.00),
(8, 'Superior Room', 'Quad',  4800.00);