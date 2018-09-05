# MailChimp API v3 Connector
This is a simple Mailchimp API v3 connector using PHP and cURL.

### Functions
- Add new member to list

### Example
```php
require_once('Mailchimp-API-v3.php');

$mailchimpConnector = new MailchimpConnector(SERVER, APIKEY);
$mailchimpConnector->addMember(LIST_ID, EMAIL, FIRST_NAME, LAST_NAME, PHONE);
```

- FIRST_NAME, LAST_NAME and PHONE are not required to save member

### License
This project is MIT licensed.