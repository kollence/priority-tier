# Note
I have left the text from task 2 as comments in the files to make it easily searchable.

To locate the relevant logic, press Ctrl + Shift + F in VS Code and enter text from task 2. This will help you find which files contain the corresponding logic.

Comments from task 2 are not included for the User Management section and the last part, Imports.

The task was challenging for me. I initially thought I could complete it in two days, but I encountered unexpected delays due to strange bugs with Events, issues with sending emails, and difficulties setting up notifications.

## Install
Run the following commands:
composer install
npm install
For the mailer, Mailtrap was used.
Run migrations and seed the database:
php artisan migrate:fresh --seed
Start the queue worker:
php artisan queue:work
Test routes:
Open /generate-dummy-files in the browser to create dummy .xlsx and .csv files for testing.
Open /test-1 in the browser to test task 1.
Results will be displayed, and the entire logic for task 1 can be found in web.php under Route::get('task-1'), along with its associated classes and logic.
Configuration files for import types are located in config/import_types.
Login
Admin:
Email: admin@mail.com
Password: password

Manager:
Email: manager@mail.com
Password: password

User (restricted access):
Email: user@mail.com
Password: password

Cannot access User Management.
Notes on File Importing
Importing a file triggers a background job, but proper notifications are not yet implemented.
Mismatched headers will prevent file uploads.
Users without appropriate permissions cannot upload files.
Dynamic options for the select dropdown and sidebar are derived from config/import_types.

### Formatting of Config

(Maybe you wanted):
```php
return [['orders' => ['files'] => ['file1', 'file2', 'file3']]];
```
(And not like this):
```php
return [['orders'], ['products'], ['customers']];
```
So my iteration trough data is not satisfied  

## Basics
Admin and Manager Permissions:
Admin and Manager roles can manage users and roles, granting them access to all features.

Routes and Actions:
Access to routes and actions is restricted based on permissions.

Data Import:

The Data Import page only accepts files in the correct format with valid headers for storing or updating data in the database.
If validation fails:
An entry is made in the ImportLogs table.
If data updates occur:
Changes are recorded in the ImportAudit table, including old and new values.
If any ImportLogs are created during the process, an email will be sent to admin@mail.com (currently hardcoded).
There are issues with using auth()->user()->email, as it always returns null.
File Processing:

File processing is handled in the background by the ProcessDataImport job class.
Notifications are incomplete due to bugs encountered in bootstrap.js.
```js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
```
### Imported Data
Accessible Routes:
Display a list of all routes accessible to users.

Search Terms:

Search terms on the page are parameters defined in config/import_types.
Searches are conducted based on header parameters specific to the type and file.
Export Button:

The export button is partially implemented and currently non-functional.
Action Buttons:

Action buttons are not yet working.

### Imports
List of Data Imports:
Display all data imports with the ability to:
Search across all field types related to Data Import.
Open a modal to view logs if any were generated during the import process.