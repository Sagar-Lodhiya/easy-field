# Punch-In Implementation Summary

## Overview
This document summarizes the changes made to implement the new punch-in rules based on user types and punch-in types.

## User Types
- `user_type = 'sales'` - Sales users
- `user_type = 'office'` - Office users  
- `user_type = 'sales_and_office'` - Users who can do both sales and office work

## Punch-In Types
- `punch_in_type = 'S'` - Sales punch-in (requires all fields)
- `punch_in_type = 'O'` - Office punch-in (requires only lat/lng, validates office proximity)

## Changes Made

### 1. PunchInForm Model (`modules/api/models/PunchInForm.php`)

#### New Properties
- Added `$punch_in_type` property to distinguish between sales and office punch-ins

#### Updated Validation Rules
- `punch_in_type` is now required and must be either 'O' or 'S'
- Sales punch-in fields (`latitude`, `longitude`, `place`, `vehicle_type`, `battery`, `mobile_network`) are only required when `punch_in_type = 'S'`
- `meter_reading_in_km` is only required for sales users with vehicles
- `image` is only required for sales users
- Office proximity validation only runs when `punch_in_type = 'O'`

#### Enhanced Validation Logic
- `validateOfficeProximity()` now only validates for office users (`user_type = 'office'`)
- Added `splitLatLngFromSettings()` method to parse office location from settings

#### Updated Save Logic
- Sales users: All fields are saved including image, vehicle details, etc.
- Office users: Only minimal fields are saved (lat/lng, basic info), no image required

### 2. HomeHelper (`modules/api/helpers/HomeHelper.php`)

#### New Tracking Fields
- Added `can_start_tracking` and `can_stop_tracking` to the attendance response
- `can_start_tracking`: Only true for `user_type = 'sales_and_office'` when not punched in
- `can_stop_tracking`: True when user is punched in but not punched out

### 3. V1Controller (`modules/api/controllers/V1Controller.php`)

#### Enhanced Punch-In Action
- Added validation for `punch_in_type` parameter
- Returns appropriate error messages for missing or invalid punch-in types

## API Usage Examples

### Sales User Punch-In
```json
POST /api/v1/punch-in
{
    "punch_in_type": "S",
    "latitude": "28.6139",
    "longitude": "77.2090",
    "place": "Delhi",
    "vehicle_type": "Car",
    "meter_reading_in_km": "15000",
    "battery": "85",
    "mobile_network": "4G",
    "image": "[file upload]"
}
```

### Office User Punch-In
```json
POST /api/v1/punch-in
{
    "punch_in_type": "O",
    "latitude": "28.6139",
    "longitude": "77.2090"
}
```

### Home API Response
```json
{
    "user": { ... },
    "attendance": {
        "can_punch_in": true,
        "can_punch_out": false,
        "can_start_tracking": false,
        "can_stop_tracking": false,
        ...
    },
    "analytics": { ... }
}
```

## Key Features

1. **Conditional Field Requirements**: Sales users must provide all fields, office users only need location
2. **Office Proximity Validation**: Office users are validated against office location settings
3. **Tracking Permissions**: Only sales_and_office users can start/stop tracking
4. **No Database Changes**: `punch_in_type` is not stored in the database, only used for validation
5. **Flexible Validation**: Different validation rules based on user type and punch-in type

## Notes

- No migrations were created as `punch_in_type` is not stored in the database
- Office proximity validation only works when office location and distance settings are configured
- The implementation maintains backward compatibility while adding new functionality
- All validation errors are properly handled and returned to the API client
