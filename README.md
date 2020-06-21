# P6 snowTrickMass

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/9f4e930c312d48ae9037625c2b8c19a6)](https://www.codacy.com/manual/gillespatrick/P6?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=gillespatrick/P6&amp;utm_campaign=Badge_Grade)


Community website snowboard figures with Symfony framework.


## Environment used during development

* OS GNU/LINUX Debian 10 (Buster)
* Composer        1.10.5
* Bootstrap       4.5
* Symfony         4.3.9
* [Symfony CLI](https://github.com/symfony/cli/)    4.15.0
* LAMP 
	* Apache  2.4.38
	* MariaDB 10.32
	* PHP     7.3

## Settings

1. Clone or download the GitHub repository :

```
	gitl clone https://github.com/gillespatrick/P6.git
```

2. Set up your enviroment variable .env file : Database, SMTP
3. Download and install [Composer](https://getcomposer.org/download/) and [git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git/)
4. Create database if it isn't exist
```
	symfony console database:create
```

5. Make migration
```
	symfony console database:migrations:migrate
```

6. Congratulations, you done, the project is installed correctly
