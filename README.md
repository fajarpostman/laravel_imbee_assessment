## Rabbitmq Queue and Push notification Firebase FCM

1. Run composer install to run the project
2. Import database
3. Copy .env file to the project

## Setup RabbitMQ
1. Create queue on RabbitMQ "notification.fcm"
2. Create exchange on RabbitMQ "notification_exchange"

## Run the project
1. Run the RabbitMQ and Web-Server (you can use xampp or ther webserver)
2. Consume message on terimal type: "php artisan consume:notifications"

## Simulate on Postmant
- Publish Message:
    - Method: POST
    - URL: /api/publish
    - Body (JSON):
    {
        "identifier": "fcm-msg-123",
        "type": "device",
        "deviceId": "your_device_token", //you can get the device id when you run the android app and check the logcat and find "TOKEN FCM" and copy to the parameter
        "text": "Example notification"
    }   

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
