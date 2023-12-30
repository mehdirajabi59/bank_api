# **Welcome to Bank Project**

## **Setup Instructions**

Follow these steps to run the Bank Project:

1.**Migrate Database and Seed**

Run the following command to migrate the database and seed it with initial data:
```php
php artisan migrate --seed
```

2.**Run the Application**

Start the application server by executing the following command:

```php
php artisan serve
```


## **SMS Driver Configuration**

To configure the SMS driver, you can set the SMS_DRIVER key in the .env file. You have two options for the driver: kavehnegar or ghasedak.



## **Creating a Custom SMS Driver**


If you want to create a custom SMS driver, you need to ensure that your driver class implements the SmsInterface. This interface defines the necessary methods for sending SMS messages. You can refer to the existing drivers like Kavehnegar and Ghasedak as examples.

Feel free to explore and modify the project according to your needs!
