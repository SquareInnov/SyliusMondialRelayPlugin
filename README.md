# Sherlockode SyliusMondialRelayPlugin

----

[ ![](https://img.shields.io/packagist/l/sherlockode/sylius-mondial-relay-plugin) ](https://packagist.org/packages/sherlockode/sylius-mondial-relay-plugin "License")
[ ![](https://img.shields.io/packagist/v/sherlockode/sylius-mondial-relay-plugin) ](https://packagist.org/packages/sherlockode/sylius-mondial-relay-plugin "Version")
[ ![](https://poser.pugx.org/sherlockode/sylius-mondial-relay-plugin/downloads)](https://packagist.org/packages/sherlockode/sylius-mondial-relay-plugin "Total Downloads")
[ ![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://www.sherlockode.fr/contactez-nous/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mondial_relay)


## Table of Content

***

* [Overview](#overview)
* [Installation](#installation)
    * [Usage](#usage)
* [Demo](#demo-sylius-shop)
* [License](#license)
* [Contact](#contact)

# Overview

----
This plugin enables Mondial Relay shipping method on your Sylius website.
Note that this plugin only works with a google map API Key in order to display a map in the FO.

----

# Installation

----
Install the plugin with composer:

```bash
$ composer require sherlockode/sylius-mondial-relay-plugin
```

Complete the configuration:

```yaml
# config/packages/sherlockode_sylius_mondial_relay.yaml

sherlockode_sylius_mondial_relay:
    wsdl: The mondial relay WSDL
    merchant_id: Your merchant ID
    private_key: Your private key
    google_map_api_key: '%env(GOOGLE_MAP_API_KEY)%'
    enable_ticket_printing: true
```

```dotenv
#.env

GOOGLE_MAP_API_KEY=xxxxxxxxxxxx
```

Import routing:

```yaml
# config/routes.yaml

sherlockode_sylius_mondial_relay_plugin:
    resource: "@SherlockodeSyliusMondialRelayPlugin/Resources/config/routing.xml"
```

In your Shipment entity, import the `PickupPointTrait`:

```php
<?php

// App/Entity/Shipping/Shipment.php

namespace App\Entity\Shipping;

use Doctrine\ORM\Mapping as ORM;
use Sherlockode\SyliusMondialRelayPlugin\Model\PickupPointTrait;
use Sylius\Component\Core\Model\Shipment as BaseShipment;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_shipment")
 */
class Shipment extends BaseShipment
{
    use PickupPointTrait;
}
```

Don't forget to make a migration or a d:s:u after that

Update your webpack configuration to add entries both in shop config and admin config:
```js
// Shop config
Encore
  // ...
  .addEntry('sherlockode-mondial-relay', './vendor/sherlockode/sylius-mondial-relay-plugin/src/Resources/public/js/entry.js')

// Admin config
Encore
  // ...
  .addEntry('sherlockode-mondial-relay', './vendor/sherlockode/sylius-mondial-relay-plugin/src/Resources/public/js/admin.js')
```

----

## Usage

Now you only have to create a new shipping method.
For the Shipping charges option, select "Mondial Relay"

----

# Demo Sylius Shop

---

We created a demo app with some useful use-cases of plugins!
Visit [sylius-demo.sherlockode.fr](https://sylius-demo.sherlockode.fr/) to take a look at it. The admin can be accessed under
[sylius-demo.sherlockode.fr/admin/login](https://sylius-demo.sherlockode.fr/admin/login) link.
Plugins that we have used in the demo:

| Plugin name                  | GitHub                                                     | Sylius' Store |
|------------------------------|------------------------------------------------------------|---------------|
| Advance Content Bundle (ACB) | https://github.com/sherlockode/SyliusAdvancedContentPlugin | -             |
| Mondial Relay                | https://github.com/sherlockode/SyliusMondialRelayPlugin    | -             |
| Checkout Plugin              | https://github.com/sherlockode/SyliusCheckoutPlugin        | -             |
| FAQ                          | https://github.com/sherlockode/SyliusFAQPlugin             | -             |

## Additional resources for developers

---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)

## License

---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact

---
If you want to contact us, the best way is to fill the form on [our website](https://www.sherlockode.fr/contactez-nous/?utm_source=github&utm_medium=referral&utm_campaign=plugins_mondial_relay) or send us an e-mail to contact@sherlockode.fr with your question(s). We guarantee that we answer as soon as we can!
