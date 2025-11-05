<?php

namespace App\DBAL;

class EventType extends EnumType
{
    public const EVENT_TITLE = 'title';
    public const EVENT_DESCRIPTION = 'description';
    public const EVENT_LOCATION = 'location';
    public const EVENT_CITY = 'city';
    public const EVENT_REGION = 'region';
    public const EVENT_ADDRESS = 'address';
    public const EVENT_DATE = 'date';
    public const EVENT_OWNER = 'owner';
    public const EVENT_FEEDBACKS = 'feedbacks';
    public const EVENT_ATTENDEES = 'attendees';
    public const EVENT_MAX_PARTICIPANTS = 'max_participants';
    public const EVENT_MIN_PARTICIPANTS = 'min_participants';

}