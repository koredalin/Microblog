# Micro blog API - Roadmap

## Slim 3 framework deployment

- [x] Slim 3 framework installation.
	[Slim 3 Docs web site](https://www.slimframework.com/docs/v3/)
	
Additional Components

- [x] [CodeCeption](https://codeception.com/) testing framework


## Database

General deployment of User and BlogPost entities.

- [x] Entities
- [x] Repository Services
- [x] SQL Dump with testing data
	[SQL Dump](https://github.com/koredalin/Microblog/blob/master/Common/db_dumps/db_scheme_14X2021.sql)
- [x] The database scheme as an image.
	[DB Scheme image](https://github.com/koredalin/Microblog/blob/master/Common/db_dumps/db_scheme_14X2021.png)

### CRUD

- [x] User entity.
- [x] Post entity.

### Adding default data for testing later development.

- [x] Adding some data to tables `users`, `blog_posts`.

### Authentication and Authorization

Developing a controller and service for:

- [x] Sign up.
- [x] Log in.

### Collecting data
- [x] All Users.
- [x] All Posts.
- [ ] All blog posts by user.

### Data search

Developing a controller and service for:
- [ ] Post search by author, post name.
- [ ] User search by first name, last name, email.

### Images modules
- [x] Upload images.
- [x] Deleting files.

### Testing
API Functional tests for general functionalities.
- [x] User entity.
- [x] Post entity.

### Git
- [x] Git tags of general project levels of development.
- [x] Git united by topics commits.