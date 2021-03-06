Requirement: Implement a file selector with multiple file select with following functions.
1. Restrict user from selecting files that are not images
1. Preview of all selected images should be populated on the page without uploading the image to the server.
2. User should be able to  filter images to be actually uploaded to the server from the previews.
3. On clicking upload button, the images listed in the preview should be uploaded to server without page re-load(Ajax)

Implementation plan: 
1. User can select images using multiple file input 
2. Preview of images selected can be  populated by using FileReader object and can be appended to html preview area
3. Index of selected images can be used to generate class name of the respective image container, sothat we can match images selected and the one which is previewed
4. Using a button populated near preview, we can close the preview(Remove the element from the html dom - make sure the container with matching class name is also removed)
5. On clicking the upload button, we can iterate through the images selected in the file selector and search for the corresponding image preview container having matching class name to find if the preview of the image is not removed by the user, if present, add them to data variable and submit them via ajax to the server
