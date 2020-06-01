<?php

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider
| with the tenancy and web middleware groups. Good luck!
|
*/

Route::prefix('app')->group(function () {
	Route::get('/', function () {
		return redirect()->route('login');
	});

	Auth::routes(['register' => true]);

	Route::get('/home', 'HomeController@index')->name('home')->middleware('tenancy');
	Route::get('/complete-registration', 'Auth\RegisterController@completeRegistration');

	// Forgot password
	Route::get('/password/reset/{token}', 'Auth\ForgotPasswordController@showViewForgotPassword')->name('showViewForgotPassword');
	Route::get('/password/reset/message/{email}', 'Auth\ForgotPasswordController@showViewMessage')->name('showViewMessage');
	Route::get('/password/reset/resend-link/{email}', 'Auth\ForgotPasswordController@resendLinkResetPassword')->name('resendLinkResetPassword');

	Route::group(['middleware' => 'auth'], function () {
		Route::get('/show2faForm', 'TwoFactorController@show2faForm')->name('show2faForm');
		Route::post('/verifyToken', 'TwoFactorController@verifyToken')->name('verifyToken');
		Route::get("/resend-code", "TwoFactorController@resendCode")->name('resendCode');

		// User
		Route::get('/create-user', 'UserController@showCreateUser')->name('showCreateUser');
		Route::post('/create-user', 'UserController@createUser')->name('createUser');

		Route::get('/list-user', 'UserController@showListUser')->name('showListUser');
		Route::get('/detail-user/{id?}', 'UserController@showDetailUser')->name('showDetailUser');
		Route::get('/update-user/{id?}', 'UserController@updateDetailUser')->name('updateUser');
		Route::get('/create-email/{id?}', 'UserController@storeEmail')->name('storeEmail');
		Route::get('/update-user-email', 'UserController@updateUserEmail')->name('updateUserEmail');
		Route::get('/create-phone/{id?}', 'UserController@storePhone')->name('storePhone');
		Route::get('/update-user-phone', 'UserController@updateUserPhone')->name('updateUserPhone');
		Route::get('/delete-user-phone/{id}', 'UserController@deleteUserPhone')->name('deleteUserPhone');
		Route::get('/delete-user-email/{id}', 'UserController@deleteUserEmail')->name('deleteUserEmail');
		Route::post('/user/assign-role/{id}', 'UserController@assignRoleForUser')->name('assignRoleForUser');
		Route::get('/change-status-user/{id}', 'UserController@changeStatusUser')->name('changeStatusUser');
		Route::get('/user/trash', 'UserController@showListTrashUser')->name('showListTrashUser');
		Route::post('/user/restore/{id}', 'UserController@restoreUser')->name('restoreUser');
		Route::post('/user/delete-permanently/{id}', 'UserController@deletePermanentlyUser')->name('deletePermanentlyUser');
		Route::post('/user/delete-trash/{id}', 'UserController@deleteUser')->name('deleteUser');
		Route::post('/user/update-image/{id}', 'UserController@updateImageUser')->name('updateImageUser');
		Route::get('/create-address/{id}', 'UserController@storeAddress')->name('storeAddress');
		Route::get('/delete-user-address/{id}', 'UserController@deleteUserAddress')->name('deleteUserAddress');
		Route::get('/update-user-address', 'UserController@updateAddress')->name('updateAddress');
		Route::post('/create-accreditation/{id}', 'UserController@storeAccreditation')->name('storeAccreditation');
		Route::post('/update-user-accreditation/{id}', 'UserController@updateAccreditation')->name('updateAccreditation');
		Route::post('/delete-accreditation/{id}', 'UserController@deleteUserAccreditation')->name('deleteUserAccreditation');
		Route::get('/delete-img-accreditation/{id}', 'UserController@deleteImageAccreditation')->name('deleteImageAccreditation');

		// user matter
		Route::post('/update-user-type/{userId}', 'UserController@updateUserType')->name('updateUserType');

		// user license
		Route::post('/create-user-license/{userId}', 'UserController@createUserLicense')->name('createUserLicense');
		Route::get('/delete-license/{userId}', 'UserController@deleteLicense')->name('deleteLicense');
		Route::post('/edit-license/{userId}', 'UserController@editLicense')->name('editLicense');
		Route::post('/delete-img-license/{userId}', 'UserController@deleteImageLicense')->name('deleteImageLicense');

		// user equipment
		Route::post('/create-user-equipment/{userId}', 'UserController@createUserEquipment')->name('createUserEquipment');
		Route::post('/edit-user-equipment/{userId}', 'UserController@editUserEquipment')->name('editUserEquipment');
		Route::post('/delete-user-equipment/{userId}', 'UserController@deleteUserEquipment')->name('deleteUserEquipment');

		// Country - State - City
		Route::get('/getStates/{id}', 'UserController@getStates')->name('getStates');
		Route::get('/getCities/{id}', 'UserController@getCities')->name('getCities');

		// change password
		Route::get('/change-password', 'ChangeController@showViewChangePassword')->name('showViewChangePassword');
		Route::post('/change-password', 'ChangeController@changePassword')->name('changePassword');

		// Roles Management
		Route::get('/role/list', 'RolesController@getListRoles')->name('showListRoles');
		Route::get('/role/view-create', 'RolesController@viewcreateRole')->name('viewcreateRole');
		Route::post('/role/create', 'RolesController@createRole')->name('createRole');
		Route::get('/role/view-detail/{id}', 'RolesController@getRoleDetail')->name('getRoleDetail');
		Route::post('/role/update/{id}', 'RolesController@updateRole')->name('updateRole');
		Route::get('/role/update-content/{id}', 'RolesController@updateContentRole')->name('updateContentRole');
		Route::post('/role/delete/{id}', 'RolesController@deleteRole')->name('deleteRole');

		// Password-policies
		Route::get('/password-policies', 'PasswordPoliciesController@index')->name('policies.index');
		Route::post('/password-policies/save', 'PasswordPoliciesController@savePolicies')->name('savePolicies');
		Route::post('/password-policies/update/{id}', 'PasswordPoliciesController@updatePolicies')->name('updatePolicies');

		// Client
		Route::get('/client/list', 'ClientsController@showListClient')->name('showListClient');
		Route::get('/client/view-create', 'ClientsController@showCreateClient')->name('showCreateClient');
		Route::post('/client/create', 'ClientsController@createClient')->name('createClient');
		Route::post('/client/create-add-more', 'ClientsController@createAndAddMoreInfo')->name('createAndAddMoreInfo');
		Route::get('/client/view-detail/{id}', 'ClientsController@showDetailClient')->name('showDetailClient');
		Route::get('/client/update-content/{id}', 'ClientsController@updateContentClient')->name('updateContentClient');
		Route::post('/client/update-image/{id}', 'ClientsController@updateImageClient')->name('updateImageClient');
		Route::get('/client/trash', 'ClientsController@showListTrashClient')->name('showListTrashClient');
		Route::post('/client/restore/{id}', 'ClientsController@restoreClient')->name('restoreClient');
		Route::post('/client/delete-permanently/{id}', 'ClientsController@deletePermanentlyClient')->name('deletePermanentlyClient');
		Route::post('/client/delete-trash/{id}', 'ClientsController@deleteClient')->name('deleteClient');
		Route::get('/client/change-status/{id}', 'ClientsController@changeStatusClient')->name('changeStatusClient');

		// contact list
		Route::get('/client/contact-list/{clientId}', 'ClientsController@showContactListClient')->name('showContactListClient');
		Route::post('/client/contact-list/{clientId}', 'ClientsController@createContactListClient')->name('createContactListClient');

		// edit contact client
		Route::post('/client/edit-contact/{clientId}/{contactId}', 'ClientsController@editContactFromClient')->name('editContactFromClient');

		// delete contact from client
		Route::get('/client/delete-contact/{clientId}', 'ClientsController@deleteContactFromClient')->name('deleteContactFromClient');

		// price list
		Route::get('/client/price-list/{clientId}', 'ClientsController@showPriceListClient')->name('showPriceListClient');
		Route::get('/client/edit-price-list/{clientId}', 'ClientsController@showEditPriceListClient')->name('showEditPriceListClient');
		Route::post('/client/edit-price-list/{clientId}', 'ClientsController@editPriceListClient')->name('editPriceListClient');

		// Location Management
		Route::prefix('locations')->group(function () {
			Route::get('/create/{clientId}', 'LocationsController@showCreateLocation')->name('showCreateLocation');
			Route::post('/create/{clientId}', 'LocationsController@createNewLocation')->name('createNewLocation');
			Route::post('/create-add-more/{clientId}', 'LocationsController@createAndAddMoreInfoLocation')->name('createAndAddMoreInfoLocation');

			// statitic
			Route::get('/statitic/{locationId}', 'LocationsController@showStatiticLocation')->name('showStatiticLocation');

			// contact list
			Route::get('/contact-list/{locationId}', 'LocationsController@showContactListLocation')->name('showContactListLocation');
			Route::post('/contact-list/{locationId}', 'LocationsController@createContactListLocation')->name('createContactListLocation');

			// agreements
			Route::get('/agreements/{locationId}', 'LocationsController@showAgreementLocation')->name('showAgreementLocation');

			// price list
			Route::get('/price-list/{locationId}', 'LocationsController@showPriceListLocation')->name('showPriceListLocation');
			Route::get('/edit-price-list/{locationId}', 'LocationsController@showEditPriceListLocation')->name('showEditPriceListLocation');
			Route::post('/edit-price-list/{locationId}', 'LocationsController@editPriceListLocation')->name('editPriceListLocation');

			// edit location information
			Route::post('/edit-location-info/{routeName?}/{locationId}', 'LocationsController@editLocationInfo')->name('editLocationInfo');
			Route::get('/edit-title-location-info/{clientId}/{locationId}', 'LocationsController@updateTitleLocation')->name('updateTitleLocation');

			// edit company information
			Route::post('/edit-company-info/{routeName?}/{clientId}/{locationId}', 'LocationsController@updateCompanyInfo')->name('updateCompanyInfo');

			// delete contact
			Route::get('/delete-contact/{locationId}', 'LocationsController@deleteContact')->name('deleteContact');

			// edit contact location
			Route::post('/edit-contact/{locationId}/{contactId}', 'LocationsController@editContact')->name('editContact');

			// show list trash location
			Route::get('/trash/{clientId}', 'LocationsController@showListTrashLocation')->name('showListTrashLocation');

			// move location to trash
			Route::post('/move-location-to-trash/{clientId}/{locationId}', 'LocationsController@moveLocationToTrash')->name('moveLocationToTrash');
			Route::post('/restore-location/{clientId}/{locationId}', 'LocationsController@restoreLocation')->name('restoreLocation');
			Route::post('/delete-location/{clientId}/{locationId}', 'LocationsController@deletePermanentlyLocation')->name('deletePermanentlyLocation');
		});

		// Rates
		Route::get('/rate/list', 'RatesController@showListRate')->name('showListRate');
		Route::get('/rate/view-create', 'RatesController@viewcreateRate')->name('viewcreateRate');
		Route::post('/rate/create', 'RatesController@createRate')->name('createRate');
		Route::get('/rate/view-detail/{id}', 'RatesController@showDetailRate')->name('showDetailRate');
		Route::post('/rate/update/{id}', 'RatesController@updateRate')->name('updateRate');
		Route::post('/rate/delete/{id}', 'RatesController@deleteRate')->name('deleteRate');

		// Notification
		Route::get('/notification/list', 'NotificationController@showListNotification')->name('showListNotification');
		Route::get('/notification/read/{id}/{url}/{userId}', 'NotificationController@readUserNotification')->name('readUserNotification');
		Route::get('/notification/load-notifications', 'NotificationController@loadListNotification')->name('loadListNotification');
		Route::get('/notification/mark-all-as-read', 'NotificationController@markAllAsRead')->name('markAllAsRead');

		// Global search
		Route::get('/search', "FullTextSearchController@fullTextSearch")->name('fullTextSearch');

		// Audit log
		Route::get('/audit-log', "AuditController@index")->name('audit-log.index');

		// Matter
		Route::get('/matter', "MatterController@getListMatter")->name('getListMatter');
		Route::post('/matter/create', 'MatterController@createMatter')->name('createMatter');
		Route::get('/matter/locations', "MatterController@ajaxGetListLocation")->name('ajaxGetListLocation');
		Route::get('/matter/users-by-office', "MatterController@getListUserByOfficeAndType")->name('getListUserByOfficeAndType');

		// Matter detail
		Route::prefix('/matter/details')->group(function () {
			Route::get('/information/{matterId}', "MatterController@getMatterDetail")->name('getMatterDetail');
			Route::post('/information/{matterId}', "MatterController@assignInvestigator")->name('assignInvestigator');
			Route::post('/edit-general-info/{matterId}', "MatterController@editGeneralInfo")->name('editGeneralInfo');
			Route::post('/create-account-instructing-information/{matterId}', "MatterController@createAccountInstructingInformation")->name('createAccountInstructingInformation');
			Route::post('/create-insurer-information/{matterId}', "MatterController@createInsurerInformation")->name('createInsurerInformation');
			Route::post('/edit-insurer-information/{insurerId}/{matterId}', "MatterController@editInsurerInformation")->name('editInsurerInformation');
			Route::get('/list-user-by-user-id/{roleId}/{matterId}', 'MatterController@getListUserByRoleId')->name('getListUserByRoleId');
			Route::post('/edit-account-instructing-information/{id}/{matterId}', "MatterController@editAccountInstructingInformation")->name('editAccountInstructingInformation');
			Route::post('/add-date/{matterId}', "MatterController@addDate")->name('addDate');

			Route::get('/delete-user-matter/{roleId}/{matterId}/{userId}', 'MatterController@deleteUserMatter')->name('deleteUserMatter');

			// Notations
			Route::get('/notations/{matterId}', "MatterController@getListNotations")->name('getListNotations');
			Route::get('/notations/delete-trash/{notationId}/{matterId}', 'MatterController@deleteNotation')->name('deleteNotation');
			Route::post('/notations/restore/{notationId}/{matterId}', 'MatterController@restoreNotation')->name('restoreNotation');
			Route::post('/notations/delete-permanently/{notationId}/{matterId}', 'MatterController@deletePermanentlyNotation')->name('deletePermanentlyNotation');
			Route::get('/notations/trash/{matterId}', 'MatterController@getListTrashNotations')->name('getListTrashNotations');
			Route::post('/create-notation/{matterId}', "MatterController@storeNotation")->name('storeNotation');
			Route::get('/update-notation/{notationId}/{matterId}', 'MatterController@updateNotation')->name('updateNotation');

			// Files
			Route::get('/files/{matterId}', "MatterController@getListFiles")->name('getListFiles');
			Route::get('/open-folder', "MatterController@openFolder")->name('openFolder');
			Route::get('/create-folder', "MatterController@createFolder")->name('createFolder');
			Route::post('/upload-files', "MatterController@uploadFiles")->name('uploadFiles');
			Route::get('/delete-folder', "MatterController@deleteFolder")->name('deleteFolder');
			Route::get('/delete-file', "MatterController@deleteFile")->name('deleteFile');
			Route::get('/edit-folder', "MatterController@editFolder")->name('editFolder');
			Route::get('/edit-file', "MatterController@editFile")->name('editFile');
		});

		// Office
		Route::get('/office', "OfficeController@getListOffice")->name('getListOffice');
		Route::get('/office/getStates/{id}', 'OfficeController@getListStates')->name('getListStates');
		Route::get('/office/getCities/{id}', 'OfficeController@getListCities')->name('getListCities');
		Route::post('/office/create', 'OfficeController@createOffice')->name('createOffice');
		Route::get('/office/edit-form/{id}', 'OfficeController@showEditForm')->name('showEditForm');
		Route::post('/office/update/{id}', 'OfficeController@updateOffice')->name('updateOffice');
		Route::post('/office/delete/{id}', 'OfficeController@showDeleteForm')->name('showDeleteForm');

		// Type - Subtype
		Route::get('/type', "SpecificMatterController@showListType")->name('showListType');
		Route::get('/type/edit/{id}', "SpecificMatterController@ajaxEditType")->name('ajaxEditType');
		Route::get('/type/add', "SpecificMatterController@ajaxAddType")->name('ajaxAddType');
		Route::post('/type/delete/{id}', 'SpecificMatterController@deleteType')->name('deleteType');
	});
});