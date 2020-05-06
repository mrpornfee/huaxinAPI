<?php
Route::post('api/zhaopin/publish_job','api/zhaopin.company_job/publishJob');
Route::get('api/zhaopin/job_list/:mobile/[:limit]','api/zhaopin.company_job/jobList');
Route::get('api/zhaopin/job_detail/:id','api/zhaopin.company_job/searchJobInfo');
Route::delete('api/zhaopin/job_del','api/zhaopin.company_job/delJobs');
Route::post('api/zhaopin/edit_job','api/zhaopin.company_job/editJob');
Route::put('api/zhaopin/top_job','api/zhaopin.company_job/toTop');
Route::delete('api/zhaopin/top_off_job','api/zhaopin.company_job/topOff');
Route::put('api/zhaopin/recommend_job','api/zhaopin.company_job/recommend');
Route::delete('api/zhaopin/recommend_off_job','api/zhaopin.company_job/recommendOff');
Route::put('api/zhaopin/urgent_job','api/zhaopin.company_job/urgent');
Route::delete('api/zhaopin/urgent_off_job','api/zhaopin.company_job/urgentOff');
Route::put('api/zhaopin/ch_st','api/zhaopin.company_job/changeStatus');

Route::get('api/zhaopin/job_class/:job_class/[:id]','api/zhaopin.select_info/selectJob');
Route::get('api/zhaopin/des_class/:des_class/[:id]','api/zhaopin.select_info/selectDes');
Route::get('api/zhaopin/com_class/:variable','api/zhaopin.select_info/selectComClass');