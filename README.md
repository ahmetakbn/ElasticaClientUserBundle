1- You need [Elasticsearch](https://github.com/elastic/elasticsearch/tree/v1.7.1) installed and make sure that Elasticsearch is running:
	
	```sh
	$ sudo /etc/init.d/elasticsearch start
	```
2- Clone this project into your server. 3- Run Composer to install the dependencies:

	```sh
	php composer.phar update
	```
4. Go to `/path-to-project/web/bundles/forntend/` folder from Console and run Bower to update dependencies:

	```sh
	bower update
	```
5. Create Index and Type in Elasticsearch by adding an example user by running:

	```sh
	curl -XPUT 'http://localhost:9200/elastica/user/1' -d '{
    	"name" : "John",
    	"email" : "john@test.com",
    	"password" : "test"
	}'
	```

6. Browse `http://host:port/path-to-project/web/app_dev.php/`

Not: You can use different Index and Type in Elasticsearch. For that please change the parameters from `/path-to-project/src/ElasticaClient/UserBundle/Resources/config/parameters.yml` and create your Elasticsearch Index and Type.