# VU_CC_week8-lab8
Valparaiso University, Cloud Computing, Spring 2017, Week 8, Lab 8

Lab 8
Change the lab we did in class to store the file as firstname.txt in Cloud Storage. Note that currently filename is the username when it is being stored. 

curl localhost/restapi.php/awolde/Aman -X POST
The above command creates a file named awolde. Your task is for it to create Aman.txt.

Add also a last name field for the user. Take a look at here how I parse the URL: https://github.com/awolde/aws-wp/blob/master/restapi.php#L22.

Hence, doing this should create Aman.txt with my username, first name and last name in it.

curl localhost/restapi.php/awolde/Aman/Wolde -X POST
