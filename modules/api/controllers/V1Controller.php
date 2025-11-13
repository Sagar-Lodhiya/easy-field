<?php

namespace app\modules\api\controllers;

use app\models\Leave;
use app\models\Notifications;
use app\models\Parties;
use app\models\Payment;
use app\models\Users;
use app\models\Expenses;
use app\models\Visits;
use app\models\Settings;
use app\modules\api\helpers\ApplicationHelper;
use app\modules\api\helpers\AttendanceHelper;
use app\modules\api\helpers\DropdownHelper;
use app\modules\api\helpers\HomeHelper;
use app\modules\api\helpers\ImageUploadHelper;
use app\modules\api\models\BulkLocationForm;
use app\modules\api\models\LeaveForm;
use app\modules\api\models\LeaveSearch;
use app\modules\api\models\LogForm;
use app\modules\api\models\LoginForm;
use app\modules\api\models\ExpensesForm;
use app\modules\api\models\ExpensesSearch;
use app\modules\api\models\PartiesForm;
use app\modules\api\models\PartiesSearch;
use app\modules\api\models\PaymentsForm;
use app\modules\api\models\PaymentsSearch;
use app\modules\api\models\PunchInForm;
use app\modules\api\models\PunchOutForm;
use app\modules\api\models\SearchNotifications;
use app\modules\api\models\StartTrackingForm;
use app\modules\api\models\StopTrackingForm;
use app\modules\api\models\VisitsForm;
use app\modules\api\models\VisitsSearch;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Default controller for the `api` module
 */
class V1Controller extends Controller
{
    private $_data;
    private $_message = "";
    private $_customKeys = [];
    private $_response_code = 200;
    private $_cache;

    public function init()
    {
        $headers = Yii::$app->response->headers;
        $headers->add("Cache-Control", "no-cache, no-store, must-revalidate");
        $headers->add("Pragma", "no-cache");
        $headers->add("Expires", 0);

        Yii::$app->response->on(
            \yii\web\Response::EVENT_BEFORE_SEND,
            [$this, 'beforeResponseSend']
        );

        $this->_cache = Yii::$app->cache;
        parent::init();
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => (YII_ENV_PROD) ? [''] : ['http://localhost'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Method' => ['GET', 'HEAD', 'POST', 'PUT'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Request-Headers' => ['X-Wsse', 'Content-Type'],
                    // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'except' => ['login', 'setting', 'dropdown', 'app-update'],
                'authMethods' => [
                    HttpBearerAuth::className()
                ]
            ],
        ];
    }

    /**
     *
     * @param type $action
     * @return mixed
     */
    public function beforeAction($action)
    {

        //Yii::$app->language = (!empty($_GET['lang'])) ? $_GET['lang'] : 'en';
        Yii::$app->language = (!empty($_GET['lang'])) ? $_GET['lang'] : 'en';
        Yii::$app->controller->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function beforeResponseSend(\yii\base\Event $event)
    {
        $response = $event->sender;
        if (Yii::$app->response->statusCode == 401) {
            Yii::$app->response->statusCode = 200;
            $response->data = [
                'success' => Yii::$app->response->isSuccessful,
                'status' => 401,
                'message' => 'Unauthorized Access',
                'data' => [],
            ];

            // return $data;
        }
    }

    private function _response()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $response = $this->_data;
        if (empty($response)) {
            $response = new \stdClass();
        }

        $data = [
            'success' => Yii::$app->response->isSuccessful,
            'status' => $this->_response_code,
            'message' => $this->_message,
            'data' => $response,
        ];

        if (!empty($this->_customKeys))
            $data = array_merge($data, $this->_customKeys);

        return $data;
    }

    public function actionLogin()
    {
        $request = Yii::$app->request->bodyParams;
        if ($this->request->isPost && !empty($request)) {

            $model = new LoginForm();

            if ($model->load($request, '') && $model->login()) {
                $this->_data = [
                    "user" => ApplicationHelper::_formatUser($model->getUser()),
                ];
            } else {
                $this->_response_code = 404;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors)) ? $model->firstErrors[array_key_first($model->firstErrors)] : $model->firstErrors;
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }


        return $this->_response();
    }

    public function actionPunchIn()
    {
        $request = Yii::$app->request->post();

        if ($this->request->isPost && !empty($request)) {
            // Validate punch_in_type is provided
            if (!isset($request['punch_in_type']) || !in_array($request['punch_in_type'], ['O', 'S'])) {
                $this->_response_code = 400;
                $this->_message = 'punch_in_type is required and must be either "O" (Office) or "S" (Sales)';
                return $this->_response();
            }

            $model = new PunchInForm();

            // Load the uploaded file for the model
            $model->image = \yii\web\UploadedFile::getInstanceByName('image');

            if ($model->load($request, '') && $model->validate()) {
                $this->_message = "Job started successfully";
                $this->_data = $model->savePunchIn();
            } else {
                $this->_response_code = 401;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors)) ? $model->firstErrors[array_key_first($model->firstErrors)] : $model->firstErrors;
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }

        return $this->_response();
    }


    public function actionLog()
    {
        $request = Yii::$app->request->post();
        if ($this->request->isPost && !empty($request)) {
            $model = new LogForm();
            if ($model->load($request, '') && $model->validate()) {
                $this->_message = "location loggedd successfully";
                $this->_data = $model->saveLog();
            } else {
                $this->_response_code = 401;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors)) ? $model->firstErrors[array_key_first($model->firstErrors)] : $model->firstErrors;
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }

        return $this->_response();
    }

    public function actionLogout()
    {
        if ($this->request->isPost) {
            // Logout the user using Yii's built-in logout function
            $logoutResult = Yii::$app->user->logout();

            if ($logoutResult) {
                $this->_message = "Logged out successfully";
            } else {
                $this->_response_code = 500;
                $this->_message = "Failed to logout. Please try again.";
            }
        } else {
            $this->_response_code = 405;
            $this->_message = 'Method not allowed. Use POST request.';
        }

        return $this->_response();
    }

    /**
     * Start location tracking for user
     */
    public function actionStartTracking()
    {
        if ($this->request->isPost) {
            $model = new StartTrackingForm();

            if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
                $result = $model->startTracking();

                if ($result) {
                    $this->_message = $result['message'];
                    $this->_data = $result['data'];
                } else {
                    $this->_response_code = 400;
                    $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors))
                        ? $model->firstErrors[array_key_first($model->firstErrors)]
                        : 'Failed to start tracking';
                }
            } else {
                $this->_response_code = 400;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors))
                    ? $model->firstErrors[array_key_first($model->firstErrors)]
                    : 'Validation failed';
            }
        } else {
            $this->_response_code = 405;
            $this->_message = 'Method not allowed. Use POST request.';
        }

        return $this->_response();
    }

    /**
     * Stop location tracking for user without punching out
     */
    public function actionStopTracking()
    {
        if ($this->request->isPost) {
            $model = new StopTrackingForm();

            if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
                $result = $model->saveStopTracking();

                if ($result) {
                    $this->_message = $result['message'];
                    $this->_data = $result;
                } else {
                    $this->_response_code = 400;
                    $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors))
                        ? $model->firstErrors[array_key_first($model->firstErrors)]
                        : 'Failed to stop tracking';
                }
            } else {
                $this->_response_code = 400;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors))
                    ? $model->firstErrors[array_key_first($model->firstErrors)]
                    : 'Validation failed';
            }
        } else {
            $this->_response_code = 405;
            $this->_message = 'Method not allowed. Use POST request.';
        }

        return $this->_response();
    }



    public function actionPunchOut()
    {
        $request = Yii::$app->request->bodyParams;
        if ($this->request->isPost && !empty($request)) {
            $model = new PunchOutForm();

            // Load the uploaded file for the model
            $model->image = \yii\web\UploadedFile::getInstanceByName('image');

            if ($model->load($request, '') && $model->validate()) {
                $this->_message = "Job done successfully";
                $this->_data = $model->savePunchOut();
            } else {
                $this->_response_code = 401;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors)) ? $model->firstErrors[array_key_first($model->firstErrors)] : $model->firstErrors;
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }

        return $this->_response();
    }

    public function actionHome()
    {
        if ($this->request->isGet) {
            // Get all necessary data from helper methods
            $user = HomeHelper::getUser();
            $attendance = HomeHelper::getAttendance();
            $analytics = HomeHelper::getAnalytics();

            // Build the response
            $this->_data = [
                'user' => $user,
                'attendance' => $attendance,
                'analytics' => $analytics,
            ];

            $this->_message = 'Data Fetched Successfully';
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }

        return $this->_response();
    }

    public function actionAttendance($id = null)
    {
        if ($this->request->isGet) {
            $request = Yii::$app->request->get();

            if ($id !== null) {
                $model = AttendanceHelper::getAttendanceDetails($id);
                $this->_message = 'Attendance fetched successfully.';
                $this->_data = $model;
            } else {

                $model = AttendanceHelper::getAttendance($request['month'], $request['year'], Yii::$app->user->identity->id);
                $this->_message = 'Attendance fetched successfully.';
                $this->_data = $model;
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }

        return $this->_response();
    }

    public function actionMenu()
    {
        if ($this->request->isGet) {
            // Get all necessary data from helper methods
            $menu = HomeHelper::getMenu();

            // Build the response
            $this->_data = $menu;

            $this->_message = 'Data Fetched Successfully';
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }

        // Return the response
        return $this->_response();
    }


    public function actionNotification()
    {
        // get notification
        $request = Yii::$app->request;
        if ($request->isGet) {
            $searchModel = new SearchNotifications();

            $dataProvider = $searchModel->search($this->request->queryParams);
            $notifications = $dataProvider->getModels();
            $pagination = $dataProvider->getPagination();
            // $model = Notifications::find()->where(['is_cleared'=>0])->andWhere(['user_id'=> Yii::$app->user->identity->id]);
            $model = Notifications::find()->where(['user_id' => Yii::$app->user->identity->id])->andwhere(['is_cleared' => 0])->all();
            // print_r($model);exit;
            if ($model) {
                $formattedNotifications = array_map(function ($data) {
                    return [
                        'id' => $data->id,
                        'text' => $data->text,
                        'date' => $data->created_at

                    ];
                }, $notifications);
                $this->_data = [
                    'content' => $formattedNotifications,
                    'pagination' => [
                        'totalCount' => $pagination->totalCount,
                        'pageCount' => $pagination->pageCount,
                        'currentPage' => $pagination->page + 1, // currentPage is zero-indexed
                        'perPage' => $pagination->pageSize,
                    ],
                ];
            } else {
                $this->_message = 'No Notification Available';
                $this->_data = [
                    'content' => [],
                    'pagination' => [
                        'totalCount' => 0,
                        'pageCount' => 0,
                        'currentPage' => 1, // currentPage is zero-indexed
                        'perPage' => 0,
                    ],
                ];
            }
        } else if ($request->isPut) {
            $this->request->queryParams;
            $model = Notifications::find()->where(['id' => $this->request->queryParams['id']])->one();
            $this->_data = $model;
            if ($model) {
                $model->is_read = 1;
                if ($model->save()) {
                    $this->_message = 'notification Cleared Successfully';
                }
            } else {
                $this->_response_code = 404;
                $this->_message = 'Notification Not Found';
            }
        } else if ($request->isDelete) {
            $model = Notifications::updateAll(['is_cleared' => 1], ['user_id' => Yii::$app->user->identity->id]);

            $this->_message = 'Notification Delete Successfully';
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }
        // view notifiaction
        // read notification
        //delete notification
        return $this->_response();
    }

    /**
     * Create expense for the user
     */
    public function actionExpense($id = null)
    {
        $request = Yii::$app->request;


        // Handle GET request: List or view a specific expense
        if ($request->isGet) {
            if ($id !== null) {
                // View a specific expense
                $expense = Expenses::findOne(['id' => $id, 'user_id' => Yii::$app->user->identity->id]);
                if ($expense) {
                    $categoryName = $expense->category ? $expense->category->name : '';
                    $cityName = $expense->city ? $expense->city->city : '';

                    $this->_message = 'Expense fetched successfully';
                    $this->_data = [
                        'category_id' => $expense->category_id,
                        'category_name' => $categoryName,
                        'city_id' => $expense->city_id,
                        'city_name' => $cityName,
                        'created_at' => $expense->created_at,
                        'expense_details' => $expense->expense_details,
                        'expense_date' => $expense->expense_date,
                        'expense_photo' => $expense->expense_photo,
                        'requested_amount' => $expense->requested_amount,
                        'status_id' => $expense->status_id,
                        'status_name' => $expense->status->name,


                    ];
                } else {
                    $this->_response_code = 404;
                    $this->_message = 'Expense Not Found';
                }
            } else {
                $searchModel = new ExpensesSearch();
                $dataProvider = $searchModel->search($request->queryParams);
                $expenses = $dataProvider->getModels();
                $pagination = $dataProvider->getPagination();


                $formattedExpenses = array_map(function ($expense) {
                    return [
                        'id' => $expense->id,
                        'category_id' => $expense->category_id,
                        'category_name' => $expense->category->name,
                        'city_id' => $expense->city_id,
                        'city_name' => $expense->city->city,
                        'expense_date' => $expense->expense_date,
                        'expense_details' => $expense->expense_details,
                        'expense_photo' => $expense->expense_photo,
                        'requested_amount' => $expense->requested_amount,
                        'approved_amount' => $expense->approved_amount,
                        'status_name' => $expense->status->name,
                        'status_id' => $expense->status_id,
                        'type' => $expense->expense_type,

                    ];
                }, $expenses);


                $this->_data = [
                    'content' => $formattedExpenses,
                    'pagination' => [
                        'totalCount' => $pagination->totalCount,
                        'pageCount' => $pagination->pageCount,
                        'currentPage' => $pagination->page + 1, // currentPage is zero-indexed
                        'perPage' => $pagination->pageSize,
                    ],
                ];
            }
        } elseif ($request->isPost && !empty($request->post())) {
            // Handle POST request: Create a new expense

            $model = new ExpensesForm();
            if ($model->load($request->post(), '') && $model->validate()) {
                $this->_message = 'Expense created successfully.';
                $this->_data = $model->saveExpense();
            } else {
                $this->_response_code = 401;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors)) ? $model->firstErrors[array_key_first($model->firstErrors)] : $model->firstErrors;
            }
        } else {

            $this->_response_code = 500;
            $this->_message = 'Method Not Found';
        }
        return $this->_response();
    }

    public function actionVisit($id = null)
    {
        $request = Yii::$app->request;


        if ($request->isGet) {
            if ($id !== null) {
                // View a specific expense
                $visit = Visits::findOne(['id' => $id, 'user_id' => Yii::$app->user->identity->id]);
                if ($visit) {

                    $partyName = $visit->party ? $visit->party->dealer_name : '';
                    $this->_message = 'Visit fetched successfully';
                    $this->_data = [
                        'created_at' => $visit->created_at,
                        'time' => $visit->time,
                        'place' => $visit->place,
                        'party_id' => $visit->party_id,
                        'party_name' => $partyName,
                        'duration' => $visit->duration,
                        'discussion_point' => $visit->discussion_point,
                        'remarks' => $visit->remark,
                        'latitude' => $visit->latitude,
                        'longitude' => $visit->longitude,

                    ];
                } else {
                    $this->_response_code = 404;
                    $this->_message = 'Expense Not Found';
                }
            } else {
                // Fetch all visits with pagination and filtering
                $searchModel = new VisitsSearch();
                $dataProvider = $searchModel->search($request->queryParams);
                $visit = $dataProvider->getModels();

                $pagination = $dataProvider->getPagination();


                $formattedExpenses = array_map(function ($visit) {
                    return [
                        'id' => $visit->id,
                        'created_at' => $visit->created_at,
                        'time' => $visit->time,
                        'place' => $visit->place,
                        'party_id' => $visit->party_id,
                        'party_name' => $visit->party->dealer_name,
                        'duration' => $visit->duration,
                        'discussion_point' => $visit->discussion_point,
                        'remarks' => $visit->remark,
                        'latitude' => $visit->latitude,
                        'longitude' => $visit->longitude,
                    ];
                }, $visit);
                $this->_data = [
                    'content' => $formattedExpenses,
                    'pagination' => [
                        'totalCount' => $pagination->totalCount,
                        'pageCount' => $pagination->pageCount,
                        'currentPage' => $pagination->page + 1, // currentPage is zero-indexed
                        'perPage' => $pagination->pageSize,
                    ],
                ];
            }
        } elseif ($request->isPost) {
            Yii::info('POST request received');

            $model = new VisitsForm();
            $model->user_id = Yii::$app->user->identity->id; // Assign the logged-in user's ID

            if ($model->load($request->post(), '') && $model->validate()) {
                if ($visit = $model->saveVisits()) {
                    return [
                        'success' => true,
                        'message' => 'Visit created successfully.',
                        'data' => $visit,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Failed to create visit.',
                'errors' => $model->errors, // Include validation errors
            ];
        } else {
            $this->_response_code = 500;
            $this->_message = 'Method Not Found';
        }
        return $this->_response();
    }

    public function actionParties($id = null)
    {
        $request = Yii::$app->request;


        if ($request->isGet) {
            if ($id !== null) {
                // View a specific expense
                $party = Parties::findOne(['id' => $id]);
                if ($party) {

                    // $CategoryName = $party->party_category_id->name;
                    $this->_message = 'Party fetched successfully';
                    $this->_data = [

                        'created_at' => $party->created_at,
                        'dealer_categories' => $party->party_category_id,
                        'dealer_name' => $party->partyCategory->name,
                        'firm_name' => $party->firm_name,
                        'dealer_phone' => $party->dealer_phone,
                        'city_or_town' => $party->city_or_town,
                        'employee_id' => $party->employee_id,
                        'gst_number' => $party->gst_number,
                        'dealer_aadhar' => $party->dealer_aadhar,
                        'party_category_id' => $party->party_category_id,
                        'party_category_name' => $party->partyCategory->name,

                    ];
                } else {
                    $this->_response_code = 404;
                    $this->_message = 'Expense Not Found';
                }
            } else {
                // Fetch all visits with pagination and filtering
                $searchModel = new PartiesSearch();
                $dataProvider = $searchModel->search($request->queryParams);
                $party = $dataProvider->getModels();

                $pagination = $dataProvider->getPagination();


                $formattedExpenses = array_map(function ($party) {
                    return [
                        'id' => $party->id,
                        'city_or_town' => $party->city_or_town,
                        'created_at' => $party->created_at,
                        'dealer_aadhar' => $party->dealer_aadhar,
                        'dealer_name' => $party->partyCategory->name,
                        'dealer_phone' => $party->dealer_phone,
                        'firm_name' => $party->firm_name,
                        'gst_number' => $party->gst_number,
                        'dealer_categories' => $party->party_category_id,
                        'employee_id' => $party->employee_id,
                        'party_category_id' => $party->party_category_id,
                        'party_category_name' => $party->partyCategory->name,
                    ];
                }, $party);
                $this->_data = [
                    'content' => $formattedExpenses,
                    'pagination' => [
                        'totalCount' => $pagination->totalCount,
                        'pageCount' => $pagination->pageCount,
                        'currentPage' => $pagination->page + 1, // currentPage is zero-indexed
                        'perPage' => $pagination->pageSize,
                    ],
                ];
            }
        } elseif ($request->isPost) {
            Yii::info('POST request received');

            $model = new PartiesForm();
            $model->employee_id = Yii::$app->user->identity->id; // Assign the logged-in user's ID

            if ($model->load($request->post(), '') && $model->validate()) {
                if ($party = $model->saveParties()) {

                    return [
                        'success' => true,
                        'message' => 'Party created successfully.',
                        'data' => $party,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Failed to create party.',
                'errors' => $model->errors, // Include validation errors
            ];
        } elseif ($request->isPut) {
            // Ensure ID is provided for the update
            if ($id === null) {
                $this->_response_code = 400;
                $this->_message = 'ID is required for updating a party.';
            } else {
                // Find the party by ID
                $party = Parties::findOne(['id' => $id]);
                if ($party) {
                    // Parse the PUT data
                    $data = json_decode($request->getRawBody(), true);

                    // Only update fields that are provided in the request
                    $party->load($data, '');  // Loads only the provided fields

                    if ($party->save()) {
                        $this->_message = 'Party updated successfully.';
                        $this->_data = $party;
                    } else {
                        $this->_response_code = 422;
                        $this->_message = 'Failed to update party.';
                        $this->_data = $party->errors;  // Include validation errors
                    }
                } else {
                    $this->_response_code = 404;
                    $this->_message = 'Party Not Found';
                }
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'Method Not Found';
        }

        return $this->_response();
    }

    public function actionPayment($id = null)
    {
        $request = Yii::$app->request;


        // Handle GET request: List or view a specific Payment
        if ($request->isGet) {
            if ($id !== null) {
                // View a specific Payment
                $payments = Payment::findOne(['id' => $id, 'user_id' => Yii::$app->user->identity->id]);
                if ($payments) {


                    if ($payments->status == 1) {
                         $statusName = 'Pending';
                    } elseif ($payments->status == 2) {
                         $statusName = 'Approved';
                    } else {
                         $statusName =  'Declined';
                    }
                    $this->_message = 'Payments fetched successfully';
                    $this->_data = [
                        'id' => $payments->id,
                        'amount' => $payments->amount,
                        'amount_details' => $payments->amount_details  ?? '',
                        'amount_type' => $payments->amount_type  ?? '',
                        'collection_of' => $payments->collection_of  ?? '',
                        'created_at' => $payments->created_at,
                        'extra' => $payments->extra  ?? '',
                        'party_id' => $payments->party_id ,
                        'party_name' => $payments->parties->dealer_name  ?? '',
                        'payments_details' => $payments->payments_details ?? '',
                        'payments_photo' => $payments->payments_photo,
                        'status_id' => $payments->status,
                        'status_name' => $statusName,
                    ];
                } else {
                    $this->_response_code = 404;
                    $this->_message = 'Payments Not Found';
                }
            } else {
                // Fetch all expenses with pagination and filtering
                $searchModel = new PaymentsSearch();
                $dataProvider = $searchModel->search($request->queryParams);
                $payments = $dataProvider->getModels();
                $pagination = $dataProvider->getPagination();

                $formattedExpenses = array_map(function ($payments) {
                    if ($payments->status == 1) {
                        $statusName = 'Pending';
                    } elseif ($payments->status == 2) {
                        $statusName = 'Approved';
                    } else {
                        $statusName =  'Declined';
                    }
                    return [
                        'id' => $payments->id,
                        'amount' => $payments->amount ,
                        'amount_details' => $payments->amount_details  ?? '',
                        'amount_type' => $payments->amount_type,
                        'collection_of' => $payments->collection_of  ?? '',
                        'created_at' => $payments->created_at,
                        'extra' => $payments->extra  ?? '',
                        'party_id' => $payments->party_id,
                        'party_name' => $payments->parties->dealer_name  ?? '',
                        'payments_details' => $payments->payments_details ?? '',
                        'payments_photo' => $payments->payments_photo,
                        'status_id' => $payments->status,
                        'status_name' => $statusName,

                    ];
                }, $payments);

                $this->_data = [
                    'content' => $formattedExpenses,
                    'pagination' => [
                        'totalCount' => $pagination->totalCount,
                        'pageCount' => $pagination->pageCount,
                        'currentPage' => $pagination->page + 1, // currentPage is zero-indexed
                        'perPage' => $pagination->pageSize,
                    ],
                ];
            }
        } elseif ($request->isPost && !empty($request->post())) {
            $model = new PaymentsForm();
            if ($model->load($request->post(), '') && $model->validate() && $data = $model->savePayments()) {
                $this->_data = $data;
                $this->_message = 'Payments created successfully.';
            } else {
                $this->_response_code = 401;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors))
                    ? $model->firstErrors[array_key_first($model->firstErrors)]
                    : $model->firstErrors;
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'Method Not Found';
        }
        return $this->_response();
    }

    public function actionDropdown()
    {

        $request = Yii::$app->request;
        if ($request->isGet) {
            $parties = DropdownHelper::getParties();
            $states = DropdownHelper::getStates();
            $cities = DropdownHelper::getCities();
            $categories = DropdownHelper::getCategories();
            $leaveType = DropdownHelper::getLeaveType();
            $amountType = DropdownHelper::getAmountType();
            $collectionOf = DropdownHelper::getCollectionOf();
            $paymentDetails = DropdownHelper::getPaymentDetails();
            $expenseCategories = DropdownHelper::getExpenseCategories();

            $this->_data = [
                'parties' => $parties,
                'states' => $states,
                'cities' => $cities,
                'categories' => $categories,
                'amount_type' => $amountType,
                'collection_of' => $collectionOf,
                'payment_details' => $paymentDetails,
                'expense_categories' => $expenseCategories,
                'leave_types' => $leaveType
            ];
            $this->_message = 'Data Fetched Successfully';
        } else {
            $this->_response_code = 500;
            $this->_message = 'Method Not Found';
        }
        return $this->_response();
    }

    public function actionLeave($id = null)
    {
        $request = Yii::$app->request;


        // Handle GET request: List or view a specific Payment
        if ($request->isGet) {
            if ($id !== null) {
                // View a specific Payment
                $leave = Leave::findOne(['id' => $id, 'user_id' => Yii::$app->user->identity->id]);
                if ($leave->status == 1) {
                    $result = 'Pending';
                } elseif ($leave->status == 2) {
                    $result = 'Approved';
                } elseif ($leave->status == 3) {
                    $result = 'Declined';
                }

                if ($leave) {


                    $this->_message = 'Leave fetched successfully';
                    $this->_data = [
                        'user_id' => $leave->user_id,
                        'name' => $leave->user->name,
                        'leave_type_id' => $leave->leave_type_id,
                        'leave_type' => $leave->leaveType->leave_type,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'reason' => $leave->reason,
                        'status' => $result,
                    ];
                } else {
                    $this->_response_code = 404;
                    $this->_message = 'Leave Not Found';
                }
            } else {
                // Fetch all expenses with pagination and filtering
                $searchModel = new LeaveSearch();
                $dataProvider = $searchModel->search($request->queryParams);
                $leave = $dataProvider->getModels();
                $pagination = $dataProvider->getPagination();



                $formattedExpenses = array_map(function ($leave) {
                    return [
                        'user_id' => $leave->user_id,
                        'name' => $leave->user->name,
                        'leave_type_id' => $leave->leave_type_id,
                        'leave_type' => $leave->leaveType->leave_type,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'reason' => $leave->reason,
                        'status' => $leave->status,
                    ];
                }, $leave);
                $this->_data = [
                    'content' => $formattedExpenses,
                    'pagination' => [
                        'totalCount' => $pagination->totalCount,
                        'pageCount' => $pagination->pageCount,
                        'currentPage' => $pagination->page + 1, // currentPage is zero-indexed
                        'perPage' => $pagination->pageSize,
                    ],
                ];
            }
        } elseif ($request->isPost && !empty($request->post())) {
            $model = new LeaveForm();
            $model->status = 1;

            $model->user_id = Yii::$app->user->id;

            if ($model->load($request->post(), '') && $model->validate() && $data = $model->saveLeaves()) {
                $this->_data = $data;
                $this->_message = 'Leave created successfully.';
            } else {
                $this->_response_code = 401;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors))
                    ? $model->firstErrors[array_key_first($model->firstErrors)]
                    : $model->firstErrors;
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'Method Not Found';
        }
        return $this->_response();
    }

    public function actionAppUpdate()
    {
        $model = Settings::find()->where(['id' => ['6']])->all();

        $this->_message = 'Data fetched successfully';
        $this->_data = [
            'app_version' => $model[0]->value,
            'is_force_update' => '0'
        ];

        return $this->_response();
    }

    public function actionSetting()
    {
        $request = Yii::$app->request;

        if ($request->isGet) {
            try {
                // Fetch only the required settings
                $settings = Settings::find()
                    ->select(['type', 'value'])
                    ->where(['type' => ['under_maintenance', 'ping_interval', 'app_version']])
                    ->asArray()
                    ->all();

                $formattedSettings = [];
                foreach ($settings as $setting) {
                    $formattedSettings[$setting['type']] = $setting['value'];
                }

                return $this->asJson([
                    'success' => true,
                    'status' => 200,
                    'message' => 'Settings fetched successfully',
                    'data' => $formattedSettings
                ]);
            } catch (\Exception $e) {
                return $this->asJson([
                    'success' => false,
                    'status' => 500,
                    'message' => 'Settings not fetched successfully',
                    'data' => new \stdClass() // Returning empty object in case of error
                ]);
            }
        }

        return $this->asJson([
            'success' => false,
            'status' => 400,
            'message' => 'Invalid request method',
            'data' => new \stdClass()
        ]);
    }

    public function actionBulkLocation()
    {
        $request = Yii::$app->request->getRawBody();
        // print_r($request);exit;
        if ($this->request->isPost && !empty($request)) {
            $model = new BulkLocationForm();
            if ($model->load(json_decode($request, true), '') && $model->validate() && $model->saveLog()) {
                $this->_message = "location loggedd successfully";
                $this->_data = new \stdClass();
            } else {
                $this->_response_code = 401;
                $this->_message = (is_array($model->firstErrors) && !empty($model->firstErrors)) ? $model->firstErrors[array_key_first($model->firstErrors)] : $model->firstErrors;
            }
        } else {
            $this->_response_code = 500;
            $this->_message = 'There was an error processing the request. Please try again later.';
        }

        return $this->_response();
    }

    public function actionProfile()
    {
        $request = Yii::$app->request;

        if ($request->isGet) {
            $profile = Users::findOne(Yii::$app->user->identity->id);
            if ($profile) {
                $this->_message = 'Profile fetched successfully';
                $this->_data = ApplicationHelper::_formatUser($profile);
            }
        } elseif ($request->isPost) {
            $post_request = Yii::$app->request->post();
            $model = Users::findOne(Yii::$app->user->identity->id);


            if ($model) {
                if (isset($post_request['name']) && !empty($post_request['name'])) {
                    $model->name = $post_request['name'];
                }

                if (isset($post_request['last_name']) && !empty($post_request['last_name'])) {
                    $model->last_name = $post_request['last_name'];
                }

                if (isset($post_request['email']) && !empty($post_request['email'])) {
                    $model->email = $post_request['email'];
                }

                if (!empty($_FILES['profile']['name'])) {  // Check if file exists
                    Yii::debug('Profile image detected in request.', __METHOD__);

                    $profile = UploadedFile::getInstanceByName('profile');

                    if (!$profile) {
                        $this->_message = 'Failed to upload profile.';
                        $this->_data = ApplicationHelper::_formatUser($model);
                    }

                    if (!ImageUploadHelper::validateImage($profile)) {
                        $this->_message = 'Failed to upload profile.';
                        $this->_data = ApplicationHelper::_formatUser($model);
                    }

                    $filePath = ImageUploadHelper::uploadImage($profile);

                    if (!$filePath) {
                        $this->_message = 'Failed to upload profile.';
                        $this->_data = ApplicationHelper::_formatUser($model);
                    }

                    Yii::debug("Image uploaded successfully: $filePath", __METHOD__);
                    $model->profile = $filePath;
                } else {
                    Yii::debug('No profile image detected in request.', __METHOD__);
                }

                if ($model->save()) {
                    $this->_message = 'Profile updated successfully';
                    $this->_data = ApplicationHelper::_formatUser($model);
                } else {
                    $this->_message = 'Something went wrong';
                }
            }
        }

        return $this->_response();
    }
}
