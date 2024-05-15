CREATE TABLE payment (
  id int(30) NOT NULL AUTO_INCREMENT,
  Name VARCHAR(30) NOT NULL,
  Email VARCHAR(30) NOT NULL,
  RoomType VARCHAR(30) NOT NULL,
  Bed VARCHAR(30) NOT NULL,
  NoofRoom INT(30) NOT NULL,
  cin DATE NOT NULL,
  cout DATE NOT NULL,
  noofdays INT(30) NOT NULL,
  finaltotal DOUBLE(8,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  payment_status ENUM('To Pay', 'Paid', 'Cancelled') NOT NULL DEFAULT 'To Pay',
  PRIMARY KEY (id)
)