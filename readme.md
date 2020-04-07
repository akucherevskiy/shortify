## **UrlShortenerTest**
---
####Used: php7.2+, Symfony4.4/Flex, mysql5.7, postman, git

**Steps to install app**

*Firstly, git clone project*

*Then open `.env` file in project root and modify `DATABASE_URL=mysql://root:@127.0.0.1:3306/shorter` 
for your mysql user and password*
```
      e.g. DATABASE_URL="mysql://USER_NAME:PASSWORD@127.0.0.1:3306/shorter"
```
    
*Next, execute commands:*
```
     composer install ( press ENTER when it asks questions)
     php bin/console doctrine:database:create
     php bin/console doctrine:migrations:migrate
```

*Next go to `public` folder and make `php -S localhost:8000` - this will run php server with correct url for system*

#### How to use REST API
**To shortify long URL just make POST request to `localhost:8000/shortify` with json body**

```
       {
       	"url" : "https://google.com"
       }
```

*This request will return short url that can be already used and tracked inn system, like*

```    
     localhost:8000/yAOZC3z
```

*You can open that link and it will redirect you to  `https://google.com` in our case*

*To see statistic of short url use GET `localhost:8000/statistics/yAOZC3z`, where `yAOZC3z` is our short form*
```
   {
       "day": 1,
       "week": 1,
       "allTime": 1
   }
```

Several words about algorithm of shortify - I used PERMITTED_CHARS with len of 62 chars, 
short consist of 7 characters, so we have 62^7 unique combinations that system can consist, for test is more that ok

*another idea to speed up redirecting was using AMQP instead of saving statistic sync and wait for DB answer, 
instead of that send event, redirect and recalculate count in consumer*

*did not spent time on tests but system has basic exeptions like 400 response etc*


#####Updates - updated len of full size of URL - up to 65535