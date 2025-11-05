<?php

namespace App\DBAL;

class MeetupType extends EnumType
{
    public const MEETUP_TITLE = 'title';
    public const MEETUP_DESCRIPTION = 'description';
    public const MEETUP_LOCATION = 'location';
    public const MEETUP_CITY = 'city';
    public const MEETUP_REGION = 'region';
    public const MEETUP_ADDRESS = 'address';
    public const MEETUP_DATE = 'date';
    public const MEETUP_OWNER = 'owner';
    public const MEETUP_FEEDBACKS = 'feedbacks';
    public const MEETUP_ATTENDEES = 'attendees';
    public const MEETUP_MAX_PARTICIPANTS = 'max_participants';
    public const MEETUP_MIN_PARTICIPANTS = 'min_participants';

}