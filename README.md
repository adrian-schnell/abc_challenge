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
- add
  the [Google Service](https://console.cloud.google.com/projectselector2/iam-admin/serviceaccounts?hl=de&supportedpurview=project)
  Json to `storage_path('app/settings/google_service_account.json')`

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
https://YOUR_DOMAIN/api/challenge_results?
name=NAME&date=CHALLENGE_DATE&stepCount=123&pushupsDone=Yes&alcoholAbstinence=No&closedRings=Yes&validWorkoutDuration
=91.1&totalWorkoutDuration=56.43&validWorkouts=2&totalWorkouts=1&noSugar=Yes&noCarbs=No&noGluten=Yes
&noDairy=Yes&ringsActivityEnergy=500&ringsExercise=45&ringsStand=10&mindfulness=100
```

| #  | Field Name           |  Type  | Purpose / Description                                 |
|----|----------------------|:------:|-------------------------------------------------------|
| 1  | name                 | manual | Participant name                                      |
| 2  | date                 | manual | Date for which day the results should be submitted    |
| 3  | stepCount            |  auto  | Amount of steps                                       |
| 4  | pushupsDone          | manual | Answer to question if push ups have been done         |
| 5  | alcoholAbstinence    | manual | Answer to question if alcohol has been avoided        |
| 6  | noSugar              | manual | Answer to question if sugar has been avoided          |
| 7  | noCarbs              | manual | Answer to question if carbohydrates have been avoided |
| 8  | noGluten             | manual | Answer to question if carbohydrates have been avoided |
| 9  | noDairy              | manual | Answer to question if carbohydrates have been avoided |
| 10 | closedRings          |  auto  | Have all three activity rings been closed             |
| 11 | validWorkoutDuration |  auto  | Sum of all workout minutes (not including "Walking")  |
| 12 | totalWorkoutDuration |  auto  | Sum of all workout minutes                            |
| 13 | validWorkouts        |  auto  | Count of all workouts (not including "Walking")       |
| 14 | totalWorkouts        |  auto  | Count of all workouts                                 |
| 15 | ringsActivityEnergy  |  auto  | Value of Activity Energy Ring                         |
| 16 | ringsExercise        |  auto  | Value of Exercise Ring                                |
| 17 | ringsStand           |  auto  | Value of Stand Ring                                   |
| 18 | mindfulness          |  auto  | Value of Mindfulnes Ring in minutes                   |

The validation rule can be seen in the [ChallengeDataRequest](app/Http/Requests/ChallengeDataRequest.php) form request.

The Type in this case is just a meta data. It describes how the iOS shortcut will determine the submitted value.

- Type: Manual => User will be querried
- Type: Auto => Value will be automatically be determined (reading from AppleHealth)
