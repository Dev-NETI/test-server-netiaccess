<?php

// return [

//     // Other Livewire configuration settings...

//     'file_upload_max_size' => 100, // Set the maximum file size in megabytes here.

// ];


return [
    
    'temporary_file_upload' => [
        
        'rules' => 'file|mimes:png,jpg,pdf,xlsx,zip,rar,xls|max:102400', // (100MB max, and only pngs, jpegs, and pdfs.)
        
    ],
];

?>