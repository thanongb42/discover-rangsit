<?php
/** @var Router $router */

// Public Pages
$router->get('/', 'HomeController', 'index');
$router->get('/city-map', 'MapController', 'index');
$router->get('/line-manual', 'HomeController', 'lineManual');
$router->get('/privacy', 'HomeController', 'privacy');
$router->get('/terms', 'HomeController', 'terms');
$router->get('/api/places', 'ApiController', 'places');
$router->get('/api/search', 'ApiController', 'search');
$router->get('/api/categories', 'ApiController', 'categories');
$router->post('/api/categories/add', 'ApiController', 'categoriesAdd');
$router->post('/api/categories/update', 'ApiController', 'categoriesUpdate');
$router->post('/api/categories/delete', 'ApiController', 'categoriesDelete');
$router->get('/api/top-places', 'ApiController', 'topPlaces');
$router->post('/api/place/review', 'ApiController', 'placeReview');

// Businesses
$router->get('/place/{slug}', 'PlaceController', 'detail');
$router->post('/place/rate', 'PlaceController', 'rate');
$router->get('/trending', 'PlaceController', 'trending');

// Auth
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'authenticate');
$router->get('/login/line', 'AuthController', 'lineLogin');
$router->get('/login/line/callback', 'AuthController', 'lineCallback');
$router->get('/register', 'AuthController', 'register');
$router->post('/register', 'AuthController', 'store');
$router->get('/logout', 'AuthController', 'logout');

// User Profile
$router->get('/profile', 'UserController', 'profile');
$router->post('/profile/update', 'UserController', 'update');
$router->post('/api/profile/avatar', 'UserController', 'updateAvatar');
$router->post('/profile/change-password', 'UserController', 'changePassword');
$router->get('/my-businesses', 'UserController', 'myBusinesses');
$router->get('/my-reviews', 'UserController', 'myReviews');



// Dashboard (Protected)
$router->get('/dashboard', 'DashboardController', 'index');
$router->get('/dashboard/add-place', 'PlaceController', 'create');
$router->post('/dashboard/add-place', 'PlaceController', 'store');

// Admin Actions
$router->get('/admin/pending', 'AdminController', 'pending');
$router->post('/api/admin/approve', 'ApiController', 'placeApprove');
$router->post('/api/admin/reject', 'ApiController', 'placeReject');
$router->get('/admin/users', 'AdminController', 'users');
$router->get('/admin/users/detail/{id}', 'AdminController', 'userDetail');
$router->get('/api/admin/users/get/{id}', 'ApiController', 'userGet');
$router->post('/api/admin/users/add', 'ApiController', 'userAdd');
$router->post('/api/admin/users/update', 'ApiController', 'userUpdate');
$router->post('/api/admin/users/reset-password', 'ApiController', 'userResetPassword');
$router->post('/api/admin/users/delete', 'ApiController', 'userDelete');
$router->get('/admin/categories', 'AdminController', 'categories');
$router->get('/admin/places', 'AdminController', 'places');
$router->get('/admin/places/edit/{id}', 'AdminController', 'placeEdit');
$router->post('/api/admin/places/update', 'ApiController', 'placeUpdate');
$router->post('/api/admin/places/cover/update', 'ApiController', 'placeCoverUpdate');
$router->post('/api/admin/places/lineqr/update', 'ApiController', 'placeLineQrUpdate');
$router->post('/api/admin/places/delete', 'ApiController', 'placeDelete');
$router->post('/api/admin/places/gallery/upload', 'ApiController', 'galleryUpload');
$router->post('/api/admin/places/gallery/delete', 'ApiController', 'galleryDelete');
$router->get('/admin/logs', 'AdminController', 'logs');





