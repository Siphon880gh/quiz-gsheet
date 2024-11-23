# Migration Guide

Project "Quiz" under weng.f.fung@gmail.com
https://console.cloud.google.com/apis/dashboard?project=temporal-fx-381723&authuser=2&inv=1&invt=AbiN1Q&supportedpurview=project

Service key file: quizer-temporal-fx-381723.json

Note: Google will disable your Google Sheet API from inactivity

Number of levels up from inside the app folder for the service accounts:
- 3x
- If it were interactively:
```
cd app1
cd ../
cd ../
cd ../
cd keys
```
- Your app will load the service account from a gsheet categorized php file like: `gsheets/Business/Dropshipping.php`
- So the path could be 5x levels up: `/../../../../../keys`