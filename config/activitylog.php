<?php

use Spatie\Activitylog\Models\Activity;

return [

    /*
     * If set to false, no activities will be saved to the database.
     */
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    /*
     * When the clean-command is executed, all recording activities older than
     * the number of days specified here will be deleted.
     */
    'delete_records_older_than_days' => 365,

    /*
     * If no log name is passed to the activity() helper, we use this default log name.
     */
    'default_log_name' => 'default',

    /*
     * You can specify an auth driver here that gets passed to the Auth facade.
     * When set to null, the default auth driver will be used.
     */
    'default_auth_driver' => null,

    /*
     * If set to true, the subject returns deleted models.
     */
    'subject_returns_soft_deleted_models' => true,

    /*
     * This model will be used to log activity.
     * It should be, or extend Spatie\Activitylog\Models\Activity.
     */
    'activity_model' => Activity::class,

    /*
     * This is the name of the table that will be created by the migration and
     * used by the Activity model shipped with this package.
     */
    'table_name' => 'activity_log',

    /*
     * This is the name of the database connection that will be used by the migration and
     * used by the Activity model shipped with this package. In case it is not set, Laravel's
     * database.default will be used.
     */
    'database_connection' => null,
];
