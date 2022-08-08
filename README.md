# Macopedia GUS Integration module

Module responsible for loading Polish companies data by NIP (VAT ID) from GUS (Główny Urząd Statystyczny) for Magento 2.
 - [Requirements](#requirements)
 - [Dependencies](#dependencies)
 - [Main features](#main-features)
 - [Installation and configuration](#installation-and-configuration)
 - [Screenshots](#Screenshots)

## Requirements

   Magento Open Source version > 2.4.x

## Dependencies

    gusapi/gusapi:5.3

## Main features

1. Enables address fields on customer register form
2. Adds custom VAT ID (NIP) field and button to load data from GUS on customer register form
3. Validates VAT ID (NIP), loads company data form GUS and fill-up registration form inputs with data

## Installation and configuration

### Installation
1. Using composer: 

```
composer require macopedia/magento2-gusintegration
```

2. Using zip file:
   1. Download zip file
   2. Extract module in directory `app/code/Macopedia/GusIntegration`

Enable module and install patches:
```
   bin/magento module:enable Macopedia_GusIntegration
   bin/magento setup:upgrade
```

### Configuration

1. Login to admin panel and go to settings **Stores > Configuration > Customers > Customer configuration**
2. In group **Create New Account Options** change value of **Show VAT Number on Storefront** setting to **Yes** and save configuration
3. Clean cache

For production environment:

1. Login to admin panel and go to settings **Stores > Configuration > Macopedia > API GUS**
2. In group **General** change value of **Is sandox account** setting to **No**
3. In group **General** change value of **User ID** to your user ID from GUS account
4. Clean cache

## Screenshots

![data-from-gus-registration-form](https://user-images.githubusercontent.com/7571848/183442532-6717e9c7-8a25-498c-9034-64862c7be06c.png)
