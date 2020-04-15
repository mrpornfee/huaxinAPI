<?php
Route::post('api/zhaopin/publish_job','api/zhaopin.company_job/publishJob');
Route::get('api/zhaopin/job_list/:mobile/[:limit]','api/zhaopin.company_job/jobList');
