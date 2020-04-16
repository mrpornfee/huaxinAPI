<?php
Route::post('api/zhaopin/publish_job','api/zhaopin.company_job/publishJob');
Route::get('api/zhaopin/job_list/:mobile/[:limit]','api/zhaopin.company_job/jobList');
Route::get('api/zhaopin/job_detail/:id','api/zhaopin.company_job/searchJobInfo');
Route::delete('api/zhaopin/job_del','api/zhaopin.company_job/delJobs');
Route::post('api/zhaopin/edit_job','api/zhaopin.company_job/editJob');