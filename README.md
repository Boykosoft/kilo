A short functional description of the problem and the solution.


Reasoning behind your technical choices, including architectural.


Trade-offs you might have made, anything you left out, or what you might do differently if you were to spend additional time on the project.
Payment system input object - could be not the best solution, but chosen because of other payment methods input data uncertainty - it could vary from Apple input data radically
Full integration test with subscription manager call definitely required - we have to be sure about money management in my opinion
It would be nice to automate set up process.
No user subscriptions history.
No renew cron.
No renew retry.
No subscription plan change.

A short description of how to set-up and launch the application.
1. clone repo
2. docker-compose up -d
3. Create kilo DB with adminer http://0.0.0.0:8081/ (root:root)
4. php artisan serve
5. curl --location --request POST 'http://127.0.0.1:8000/api/hook/apple' \
   --header 'Content-Type: application/json' \
   --data-raw '{
   "signature": "some-pass",
   "notification_type": "CANCEL",
   "auto_renew_adam_id":"123",
   "auto_renew_product_id":"one",
   "original_transaction_id":"1004",
   "auto_renew_status_change_date_ms": "1653658480962"
   }'
