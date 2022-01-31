A short functional description of the problem and the solution.
Combination of services and methods to set up environment and be able to handle apple backend server hooks and update 
customer subscription statuses. Two interfaces for work with payment provider and on general service to operate with subscriptions.


Reasoning behind your technical choices, including architectural.
1. Separate Payment provider and hooks executor interfaces - not all payment providers would send hooks to our backend server
2. PostgreSQL to be able to precess transactions on our DB
3. Service provider not used in AppleWebhookExecutor - only ApplePaymentProvider can be injected here.


Trade-offs you might have made, anything you left out, or what you might do differently if you were to spend additional time on the project.
1. Payment system input object - could be not the best solution, but chosen because of other payment methods input data uncertainty - it could vary from Apple input data radically
2. Full integration test with subscription manager call definitely required - we have to be sure about money management in my opinion
3. It would be nice to automate set up process.
4. No user subscriptions history.
5. No renew cron.
6. No renew retry.
7. No subscription plan change.
8. No tests written.

A short description of how to set-up and launch the application.
1. clone repo
2. docker-compose up -d
3. Create kilo DB with adminer http://0.0.0.0:8081/ (root:root)
4. php artisan migrate
5. php artisan serve
6. curl --location --request POST 'http://127.0.0.1:8000/api/hook/apple' \
   --header 'Content-Type: application/json' \
   --data-raw '{
   "signature": "some-pass",
   "notification_type": "CANCEL",
   "auto_renew_adam_id":"123",
   "auto_renew_product_id":"one",
   "original_transaction_id":"1004",
   "auto_renew_status_change_date_ms": "1653658480962"
   }'
