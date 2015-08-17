## ElasticaClinet User Management Symfony Bundle
#### (Elastica for Elasticsearch - Symfony - AngularJS)
ElasticaClient is an example user management symfony2 bundle. It is a Restful Api at the backend and uses AngularJs at the front end. It uses Elasticsearch as local storage and [Elastica](https://github.com/ruflin/Elastica) as PHP client for Elasticsearch. 
##### The Project Consist From:
- [Symfony](https://github.com/symfony/symfony)
- [Elastica](https://github.com/ruflin/Elastica)
- [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)
- [JMSSerializerBundle](https://github.com/schmittjoh/JMSSerializerBundle)
- [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle)
- [AngularJS](https://angularjs.org/)
- [Bootstrap](http://getbootstrap.com/)

### Installation
1. You need [Elasticsearch](https://github.com/elastic/elasticsearch/tree/v1.7.1) installed and make sure that Elasticsearch is running:
<<<<<<< HEAD

 ```sh
    $ sudo /etc/init.d/elasticsearch start
 ```
 
=======
```sh
$ sudo /etc/init.d/elasticsearch start
```
>>>>>>> ff101f44e3d3360610894e0823fe76a6a2a9f8d9
2. Clone this project into your server
3. Run Composer to install the dependencies:

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