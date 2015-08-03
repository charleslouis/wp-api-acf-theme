# wp-api-acf-theme


## Custom post types
Add custom post types in custom-post-types/
Require custom-post-types/my_CTP.php in function.php


## Images 
Use several custom image sizes byy defining them in libs/images.php

#### Here are the images by default

squared_image_sm: width=300, height=300 // Hard crop
squared_image_med: width=600, height=600 // Hard crop
squared_image_lg: width=800, height=900 // Hard crop
squared_image_xlg: width=1000,height= 1000 // Hard crop

rectangle_no_height_sm: width=400 // No crop -no height
rectangle_no_height_md: width=800 // No crop -no height
rectangle_no_height_lg: width=1200 // No crop -no height
rectangle_no_height_xlg: width=1200 // No crop -no height

rectangle_image_sm: width=400, height=300 // Hard crop
rectangle_image_md: width=800, height=600 // Hard crop
rectangle_image_lg: width=1000,height= 750 // Hard crop
rectangle_image_xlg: width=1200,height= 900 // Hard crop


## Get some posts
#### Retrive several posts
`wp-json/posts?type=participations&filter[ID]=129&filter[ID]=144`

