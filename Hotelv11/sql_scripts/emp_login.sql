CREATE TABLE emp_login (
empid INT(100) NOT NULL AUTO_INCREMENT,
Emp_Email VARCHAR(50) NOT NULL,
Emp_Password VARCHAR(50) NOT NULL,
PRIMARY KEY (empid)
);

INSERT INTO emp_login (empid, Emp_Email, Emp_Password) 
VALUES
(1, 'Admin@gmail.com', '123');