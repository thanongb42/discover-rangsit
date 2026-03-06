<?php
/** @var Router $router */

// Home/Map explorer
$router->get('/', 'MapController', 'index');
$router->get('/api/places', 'ApiController', 'places');
$router->get('/api/search', 'ApiController', 'search');
$router->get('/api/categories', 'ApiController', 'categories');
$router->get('/api/top-places', 'ApiController', 'topPlaces');

// Businesses
$router->get('/place/{slug}', 'PlaceController', 'detail');
$router->post('/place/rate', 'PlaceController', 'rate');
$router->get('/trending', 'PlaceController', 'trending');

// Auth
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'authenticate');
$router->get('/register', 'AuthController', 'register');
$router->post('/register', 'AuthController', 'store');
$router->get('/logout', 'AuthController', 'logout');

// User Profile
$router->get('/profile', 'UserController', 'profile');
$router->post('/profile/update', 'UserController', 'update');
$router->post('/profile/change-password', 'UserController', 'changePassword');


// Dashboard (Protected)
$router->get('/dashboard', 'DashboardController', 'index');
$router->get('/dashboard/add-place', 'PlaceController', 'create');
$router->post('/dashboard/add-place', 'PlaceController', 'store');

// Admin Actions
$router->get('/admin/pending', 'AdminController', 'pending');
$router->post('/admin/approve', 'AdminController', 'approve');
$router->post('/admin/reject', 'AdminController', 'reject');
$router->get('/admin/users', 'AdminController', 'users');
$router->post('/admin/users/update-role', 'AdminController', 'updateUserRole');
$router->post('/admin/users/delete', 'AdminController', 'deleteUser');



