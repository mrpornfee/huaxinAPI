<?php
Route::get('api/message/get_token','api/common.message/makeToken');
Route::post('api/message/send_message','api/common.message/sendMessage');