CREATE SCHEMA IF NOT EXISTS scholarship_owl;
CREATE SCHEMA IF NOT EXISTS sowl_emails;
CREATE USER IF NOT EXISTS `scholarship_owl`@`localhost` IDENTIFIED BY 'bgw3HhisDuWhj8X28QmZ';
GRANT ALL ON scholarship_owl.* TO `scholarship_owl`@`localhost` WITH GRANT OPTION;
GRANT ALL ON sowl_emails.* TO `scholarship_owl`@`localhost` WITH GRANT OPTION;
FLUSH PRIVILEGES;
