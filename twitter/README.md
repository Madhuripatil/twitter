Twitter Timeline assignment developed for rtCamp Solutions Pvt. Ltd.
====================================================================
Assigmnet Detail-

Part-1: User Timeline
Start => User visit your script page.
He will be asked to connect using his Twitter account using Twitter Auth.
After authentication, your script will pull latest 10 tweets form his “home” timeline.
10 tweets will be displayed using a jQuery-slideshow.


Part-2: Followers Timeline
Below jQuery-slideshow (in step#4 from part-1), display list 10 followers (you can take any 10 random followers).
Also, display a search followers box. Add auto-suggest support. That means as soon as user starts typing, his followers will start showing.
When user will click on a follower name, 10 tweets from that follower’s user-timeline will be displayed in same jQuery-slider, without page refresh (use AJAX).

Part-3: Download Tweets
There will be a download button to download all tweets for logged in user.
Download can be performed in csv, xls, google-spreadhseet, pdf, xml and json formats. Show user these options.
For Google-spreadsheet export feature, your app-user must have Google account. Your app should ask for permission to create spreadsheet on user’s Google-Drive.
Once user click download button (after choosing option) all tweets for logged in users should be downloaded.


libraries used: 
1. Twitter OAuth library made by Abraham Williams
2. jquery.bxslider 


Changes-
config.php - Need to change callbackurl


