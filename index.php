<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    
    <body>

        <?php
        if(isset($_POST['submit'])){ //Begin with a check to see if the form was submitted!
            //Begin by validating by checking if the destination directory was submitted
            
            // Message variables (shown in HTML, see comments)
            $noDestinationDirectoryError = ""; // Shown next to the destination directory input on the form
            $statusMessage = ""; // Status message shown under the form

            // Variable for button that deletes the uploaded file (essentially an Undo button)
            $deleteUploadedFileButton = ""; // This will be set later in the script, appears under status message

            // Internal script use variables for error handling
            $validationDidError = true; // Used later in this script to proceed with file upload or abort (errors, etc)
            $didErrorFromNoInputDestinationDirectory = true; // Used 

            // Check if the destination directory was provided in the form at all
            if (empty($_POST["destinationDirectory"])) {
                // Error if destination directory was not provided in the form
                $noDestinationDirectoryError = "Destination directory is required";
                $statusMessage = "Could not begin file upload, destination directory is required!";
                $validationDidError = true;
                $didErrorFromNoInputDestinationDirectory = true;
            } else {
                $destination_directory = "../" . $_POST["destinationDirectory"]; // Get the destination directory from the form, prepend with .. to go up one directory by default
                $full_destination_directory = realpath($destination_directory); // Get the full path of the destination directory (from /)

                // create friendly destination directory (turn /home/u464711639/domains/thatstel.la/public_html/files/tegra into /files/tegra)
                $friendly_destination_directory = explode('/', $full_destination_directory); // turn it into an array
                unset($friendly_destination_directory[1], $friendly_destination_directory[2], $friendly_destination_directory[3], $friendly_destination_directory[4], $friendly_destination_directory[5], $friendly_destination_directory[6]); // remove first home/u464711639/domains/thatstel.la/public_html/
                $friendly_destination_directory[0] = "files.thatstel.la"; // replace leading / in old path with site name
                $friendly_destination_directory = implode('/', $friendly_destination_directory); // put it back together

                $validationDidError = false; // Destination directory was provided, proceed with upload
                $didErrorFromNoInputDestinationDirectory = false; // Did not error, allow further path validation
            }

            // Check if the destination directory exists AND that the previous check did not error
            if (is_dir($full_destination_directory) == false && $didErrorFromNoInputDestinationDirectory == false){
                // Error if destination directory does not exist
                $validationDidError = true;
                if (!empty($full_destination_directory)) {
                    $statusMessage = "<p>An error occurred while uploading the file:<br>Destination directory was not found: " . $_POST["destinationDirectory"] . " (raw path: " . $full_destination_directory . "</p>";
                } else {
                    $statusMessage = "<p>An error occurred while uploading the file:<br>Destination directory was not found: " . $_POST["destinationDirectory"] . "</p>";
                }
            }

            if ($validationDidError == false) {
                // Only proceed with upload if everything checks out (no Error)

                $destination_filepath = $destination_directory . "/" . basename($_FILES["inputFile"]["name"]); // construct a destination file path
                $friendly_destination_filepath = $friendly_destination_directory . "/" .  basename($_FILES["inputFile"]["name"]); // construct a friendly destination file path for creating a clickable url
                $full_destination_filepath = $full_destination_directory . "/" .  basename($_FILES["inputFile"]["name"]); // construct a full destination file path from / for use with the move uploaded file php function

                // Uncomment for debug information about what paths are constructed
                //echo("<p>File path before processing: $destination_filepath<br>Full destination file path: $full_destination_filepath<br>Friendly destination file path: $friendly_destination_filepath</p><p>Provided destination directory before resolving: $destination_directory<br>Full destination directory is $full_destination_directory<br>Friendly destination directory: $friendly_destination_directory</p>");

                try {
                    move_uploaded_file($_FILES["inputFile"]["tmp_name"], $full_destination_filepath); // move the file from php temp to the intended destination
                    $statusMessage = "The file has been uploaded to <a href=\"https://$friendly_destination_filepath\">$friendly_destination_filepath</a>";
                } catch (Exception $e) {
                    $statusMessage = "<p>An error occurred while uploading the file:<br>Code {$e->getCode()} - \"{$e->getMessage()}\" at line {$e->GetLine()} in {php}.</p>";
                }
            }
        
        }
        ?>
    
        <div id="master">
            <h1>Stella's File Store</h1>
            <h2>File Uploader</h2>
        
            <div id="form-container">
                <form method="post" action="" enctype="multipart/form-data">
                    <p>Select file to be uploaded: <input type="file" name="inputFile" id="inputFile"><br>File size limit: idfk keep in mind the storage you have free on hostinger</p>
                    <p>Destination path: <input type="text" name="destinationDirectory" id="destinationDirectory">/<br>(relative to files.thatstel.la, do NOT include trailing slash)<br><span class="error"><?php echo $noDestinationDirectoryError;?></span></p>
                     <input type="submit" value="Upload File" name="submit">
                </form>
            </div>

            <div id="status-message">
                <?php echo $statusMessage;?>
            </div>

            <div id="file-browser">
                <h3>File browser for navigating paths</h3>
                <iframe src="//files.thatstel.la" title="Stella's File Store" width="99%" height="700"></iframe>
            </div>

        </div>

    </body>
</html>