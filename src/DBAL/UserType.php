<?php

namespace App\DBAL;

class UserType extends EnumType
{
    public const EMAIL = 'email';
    public const LASTNAME = 'lastname';
    public const FIRSTNAME = 'firstname';
    public const USERNAME = 'username';
    public const ADDRESS = 'address';
    public const CITY = 'city';
    public const POSTAL_CODE = 'postal_code';
    public const IP_ADDRESS = 'ip_address';
    public const PASSWORD = 'password';
    public const ROLE = "role";
    public const LEGAL_TERMS = 'terms_accepted';
    public const PHONE_NUMBER = "phone_number";
    public const NEWSLETTER_SUBSCRIBED = 'newsletter_subscribed';

}


