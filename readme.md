# Movie Planet Project

## About
- This project is a web service for a movie database that user can view and rate movies.
- This project build using laravel 5.4.* with OAuth2 and laravel passport.
- You can check this project online here [link to project!](http://movieplanet.herokuapp.com)

## Install with docker container for development
Follow the following steps to install the project within docker container.

- Create a throw-away container by executing the following command
  --docker run --rm -v $(pwd):/app composer/composer install
- Starting docker 
  --docker-compose up

- Copy the .env.example file into our own .env file. This file will not be checked into version control copy --.env.example -> --.env

- Run the following commands
  --docker-compose exec app php artisan key:generate
  --docker-compose exec app php artisan optimize
  --docker-compose exec app php artisan migrate --seed
  --docker-compose exec app php artisan passport:install

## Install without docker for development
Follow the following steps to install the project without docker container, you will need php apache server or Nginx with MariaDB or any other compatible database.
- Run the following commands in your local enviroment inside your app root.
  --composer install
  --php artisan key:generate
  --php artisan optimize
  --php artisan migrate --seed
  --php artisan passport:install

*NOTE: Don't forget to edit your .env file with proper configuration.*

## Consume endpoints

Follow the following steps in-order to consume endpoints.
	-- At least a clients credential is required to perform most of actions.

- You can check all endpoints by running the following command on your local:
  --php artisan route:list.
- Go to "/api/movies" to retrieve a list of all movies or a single movie, and to update, post, or delete a movie by passing the necessary parameters to the url for example "api/movies/3".
- To update a movie genres or actors you need to go to dedicated endpoints for example: "api/movies/{movie}/actors/{actor}".
- You can rate a movie by sending a post request to "api/movies/{movie}/user/{user}/ratings".
- To retrieve list of all users, register or do actions related to user go to "api/users" passing necessary parameters to the url.
- There are more dedicated endpoints to retrieve special information like "api/years", "api/genres", "api/movie/{movie}/actors", etc..

## Headers
- Authorization with Bearer {token}.
- X-localization like ex: de, fr, this will change the retrived results language.
- form-data / x-www-form-urlencoded to send post and put requests.

## Translation
- The app uses headers to determine user preferred language setting, all languages are compatible.
- For now movie description, and genre name will be only translated according to the matched translation in the application database or fallback to en by default.
- You can add/edit(update) a translation of movie's description, or any genre's name using any language, for example by sending request with X-localization: de header to "api/genres/3" with PUT request, now if you send a genre name to update it, that will be automatically inserted into a dedicated table for translation with code 'de', or will update if already existed.
- The newly movie or genre will always be created in english language even if your language is set to a different one.

## Filtration, Sorting, and Per page limit
- You can filter by any field by passing ?{field_name}=$value to url.
- Sorting by any field name by passing ?sort_by=$field_name.
- You can set per page ex: ?per_page=2.

## Obtain a clients credential
run php artisan passport:client
- Then copy client secret, after that send a post request with the following form-data to api/oauth/token
	with the following [
		grant_type 	  => 'client_credentials',
		client_id 	  => {client_id},
		client_secret => {client_secret}
	]
- To use client credentials add to header
	Authorization => Bearer {access_token}

## Obtain a user password type credential 
Run php artisan passport:client --password
- Then copy client secret, after that send a post request with the following form-data to api/oauth/token
	with the following [
		grant_type 	  => 'password',
		client_id 	  => {client_id},
		client_secret => {client_secret},
		username 	  => {any user email},
		password      => secret
	]
- To use password grant_type credentials add to header
	Authorization => Bearer {access_token}

## Obtain personal access token 
Run php artisan passport:client --personal
- Then go to /home/my-tokens using any browser, then click 'Create New Token', after that you will be able to access any endpoint and caution with this cause it is only used for testing purposes or to give a special access to specific user, and note that it has no expiration date. If you don't want to use it don't run --personal command in your cli.
- To use personal type credentials add to header
	Authorization => Bearer {access_token}

## Create new client for user you need to be logined
Go to My Clients then create new client name and redirect url
- To obtain access token using the authorization code grand type using this, we need to go to 'api/oauth/authorize?client_id={client_id}&redirect_uri={redirect_url}&response_type=code'.
- Then login or create a new user then authorize.
- Copy url and then use any online url decode library ex:www.url-encode-decode.com, to decode the url copy only the code not full url.
- To obtain an authorization code we need to go to 'oauth/token' with postman, and send a post request with form-data with the following [
		client_id 	   => {client_id},
		client_secret  => {client_secret},
		redirect_uri   => {url},
		code      	   => {auth_code},
		grant_type	   => 'authorization_code'
	]
- You can revoke this client any time by going to Authorized Clients with your browser.

Note: redirect_uri must always match the original redirect url.

## Implicit grant type
- This can be done by going to 'api/oauth/authorize?client_id={client_id}&redirect_uri={redirect_url}&response_type=token' in your browser and authorize.
- Then copy access_token from url into by using normal text editor.
- To use the access token add to header
	Authorization => Bearer {access_token}
Note: this access token is only recommended for very specific type of clients, in other case we need to avoid it.

## Refreshing tokens
- Using a password grant type we obtain a client secret, then send a post request to 'api/oauth/token' with form-data with the following [
		grant_type	   => 'password',
		client_id 	   => {client_id},
		client_secret  => {client_secret},
		username 	   => {user email},
		password       => secret
	]
- Then to obtain a new token we need to copy the refresh token, then send a post request to 'api/oauth/token' with form-data with the following [
		grant_type	   => 'refresh_token',
		client_id 	   => {client_id},
		client_secret  => {client_secret},
		refresh_token  => {refresh_token},	
	]
Note: refresh token only can be used one time.

## Request access token with specific scope without using user interface 
- Send a post request to 'api/oauth/token' with form-data with the following [
		client_id 	   => {client_id},
		client_secret  => {client_secret},
		grant_type 	   => 'password',
		username	   => {user email},
		password       => secret,
		scope 		   => 'manage-movies, manage-account' or '\*'.
	]

## Feedback

Please send me an e-mail to MOHUM at moattia86@gmail.com. All feedbacks are appreciated.
