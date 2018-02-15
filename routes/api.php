<?php

use Illuminate\Http\Request;

Route::resource('tasks', 'TasksController', [
    
    'except' => ['create', 'edit', 'show']
    
]);




