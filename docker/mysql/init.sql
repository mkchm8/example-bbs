-- Create testing database for Laravel tests
CREATE DATABASE IF NOT EXISTS testing;
GRANT ALL PRIVILEGES ON testing.* TO 'sail'@'%';
FLUSH PRIVILEGES;
