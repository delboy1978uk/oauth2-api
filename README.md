OAuth2 API
==================
[![Build Status](https://travis-ci.org/delboy1978uk/oauth2-api.png?branch=master)](https://travis-ci.org/delboy1978uk/oauth2-api) [![Code Coverage](https://scrutinizer-ci.com/g/delboy1978uk/oauth2-api/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/delboy1978uk/oauth2-api/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/delboy1978uk/oauth2-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/delboy1978uk/oauth2-api/?branch=master)<br />

An OAuth2 server built using Bone MVC Framework and the League of Extraordinary Package's OAuth2 Server lib. 

Installation
------------
```
composer create-project delboy1978uk/oauth2-api
```
Apache setup
------------

Ensure your vhost is configured to run on a secure SSL port 443 connection.

Configure database credentials
------------------------------
Create a database with utf8mb4_unicode_ci<br />
Go into the config folder, add db connection credentials for Bone MVC to use.<br />
Also, edit migrant-cfg.php, and put connection details in there too.<br />

Run database migrations
-----------------------
make sure vendor/bin is in your $PATH environment variables. Run:
```
migrant migrate
```
This will create several tables used by the OAuth2 library.

Set up Private &amp; Public keys
------------------------------
See instructions here: http://oauth2.thephpleague.com/installation/#generating-public-and-private-keys
Copy the keys to the data/keys/ directory.

Test API Endpoint
-----------------
You can test your API is available by calling /ping
```
{"pong":"2017-03-21 13:44:49"}
```

Registering a Client
--------------------
Instructions will appear once I've built this!

Command Line Tool
-----------------
We'll have a CLI tool soon too! Stay tuned!