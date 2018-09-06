# MailChimp API v3 Connector
This is a simple Mailchimp API v3 connector using PHP and cURL.

### Functions
- Get members from list
- Add new member to list

### Example
```php
require_once('Mailchimp-API-v3.php');

$mailchimpConnector = new MailchimpConnector(SERVER, APIKEY);

// Get members
$mailchimpConnector->getMembers(LIST_ID);

// Add member (FIRST_NAME, LAST_NAME, PHONE are not required)
$mailchimpConnector->addMember(LIST_ID, EMAIL, FIRST_NAME, LAST_NAME, PHONE);
```

### License
This project is MIT licensed.