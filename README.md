# ABC Challenge

This project is used as proxy service.
It receives requests from an Apple Shortcut and checks if this data already exists in a Google Sheet.

There are 3 possibilities:
- challenger is not available - request is blocked
- data does exist - will be updated with new request data
- data does not exist - data will be appended to the Google Sheet

The proxy validates the request data to be valid and also transforms float values (e.g. 51.5 or 51,5 will always 
result in 51.5).

## Setup

The proxy is setup on a default Laravel application.
Follow these steps to get Laravel to work:

- pull repository
- install composer dependencies
- start docker
- add the [Google Service](https://console.cloud.google.com/projectselector2/iam-admin/serviceaccounts?hl=de&supportedpurview=project) Json to `storage_path('app/settings/google_service_account.json')`

For an easy setup, [Laravel Sail](https://laravel.com/docs/10.x/sail) is used.

It's recommended to create the following alias on your local maschine:

```
alias sail="./vendor/bin/sail"
alias sart="./vendor/bin/sail artisan" 
```

### Use ***sail*** as you're used to do with ***docker compose***:

- `sail up -d`
- `sail down`

### Use ***sart*** as you're used to do with ***php artisan***

- `sart config:clear`
- `sart cache:clear`
- ...

## Usage

To use the proxy, simply make a GET request to:


```
https://YOUR_DOMAIN/api/challenge_results?name=NAME&date=CHALLENGE_DATE&stepCount=123&exerciseMinutes=99&pushupsDone=Yes
&alcoholConsumption=No&workoutDone=Yes&closedRings=Yes&validWorkoutDuration=91.1&totalWorkoutDuration=56.43&validWorkouts=2&totalWorkouts=1
```
