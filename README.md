SelectPhoto
============================

Web Application for photographers and creative people.
You upload your photos and allow others to select and comment the best ones.


### Use case (photographer)

You have taken ~30 photos. You want your client to select the best ones, which is suitable for him.
You upload these photos and give that man a link. Client goes by that link, selects photos which he consider as suitable.
You (admin) are notified by email when he finishes and can review selected on site.

### Scenarios

1. **Admin:** 

    1. Create link with bunch of options.
    2. Upload images, change order of them if needed.
    3. Copy and send link with photos to the client.
    4. Wait for client to complete (you will be notified by email)

2. **Client**:

    1. Opens link sent by you.
    2. Slides images, comments, selects the best.
    3. Pushes the "complete" button
    
    
FEATURES
--------

- Full screen image gallery
- Thumbnails
- Organize your links by projects
- Drag and drop multiple photo upload
- Change order of uploaded photos
- Handy options for links
- Set your own link instead of generated one

INSTALLATION
------------

1. Clone this and `cd` into repo files

2. Install packages:

    ~~~
    composer install
    ~~~
    
3. Configure app by editing files in the `./config/` directory
