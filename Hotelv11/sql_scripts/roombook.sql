CREATE TABLE roombook (
  id int(10) NOT NULL AUTO_INCREMENT,
  Name varchar(50) NOT NULL,
  Email varchar(50) NOT NULL,
  Country varchar(30) NOT NULL,
  Phone varchar(30) NOT NULL,
  RoomType varchar(30) NOT NULL,
  Bed varchar(30) NOT NULL,
  cin date NOT NULL,
  cout date NOT NULL,
  nodays int(50) NOT NULL,
  stat varchar(30) NOT NULL,
  UserID INT,
  PRIMARY KEY (id),
  FOREIGN KEY (UserID) REFERENCES signup(UserID)
)