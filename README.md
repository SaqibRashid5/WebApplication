## API Web Application

### Database Schema

MySQL database is used. Create database with name "webapp" and password is empty
Movie has:
- Casts
- Director
- Ratings

Therefore, in order to normalize, they have differnt tables to store this data. Movie_id is used as foreign key in cast,
director and rating.

### POST /api/v1/movies

This endpoint takes an object comprise of movie/s as request body. On post, the CreateMovie's create function is executed.
It validates the required fields, uses model object to push data in the database.

### GET /api/v1/movies/{id}

This endpoint takes a movie id and retrieves the movie. On get, the RetrieveMovie's get function is executed.
It validates if the movie exists in the database and return a formulated response.

### GET /api/v1/movies

This endpoint retrieves all the movies. On get, the RetrieveMovie's getAll function is executed.
It validates if a movie exists in the database and return a formulated response.